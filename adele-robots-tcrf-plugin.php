<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
/**
 * Plugin Name: The Client Relations Factory
 * Plugin URI: http://www.theclientrelationsfactory.com/
 * Description: This plugin let you have your own Virtual Robot from TCRF fully integrated within WordPress.
 * Version: 2.0
 * Author: Adele Robots Inc
 * Author URI: http://www.adelerobots.com/
 * License: GPLv3
 */

include_once("config-manager.php");

ar_tcrf_init();

function ar_tcrf_create_admin_menu()
{
    add_menu_page('Adele Robots - The Client Relations Factory Plugin - Settings',
                    'TCRF Settings',
                    'manage_options',
                    __FILE__,
                    'ar_tcrf_user_settings_page',
                    plugins_url( '/images/favicon.png', __FILE__)
                );
}

function ar_tcrf_user_settings_page()
{
    if (!is_admin())
    {
        ar_tcrf_show_noadmin_view();
        exit();
    }

    if (isset($_POST['ar_tcrf_action'])) //User is trying to perform an action
    {
        if (get_option('ar_tcrf_account_data')) //User is logged in
        {
            switch($_POST['ar_tcrf_action'])
            {
                case 'logout':
                {
                    ar_tcrf_logout();
                    break;
                }
                case 'enable-scriptlet':
                {
                    ar_tcrf_enable_scriptlet();
                    break;
                }
                case 'disable-scriptlet':
                {
                    ar_tcrf_disable_scriptlet();
                    break;
                }
                default:
                {
                    ar_tcrf_show_settings_view();
                    break;
                }
            }
        }
        else if ($_POST['ar_tcrf_action'] == 'login') //User is trying to log in
        {
            ar_tcrf_login();
        }
        else
        {
          ar_tcrf_show_login_view();
        }
    }
    else
    {
        if (get_option('ar_tcrf_account_data'))
        {
            ar_tcrf_show_settings_view();
        }
        else
        {
            ar_tcrf_show_login_view();
        }
    }
}

/**
 * Enables admin menu for plugin and checks if plugin is enabled (Linked Account), 
 * in this case adds plugin's CSS and JS Scriptlet to the contents of the blog.
 */
function ar_tcrf_init()
{
    add_action('admin_menu', 'ar_tcrf_create_admin_menu');

    $ar_tcrf_account_data = get_option('ar_tcrf_account_data');

    if ($ar_tcrf_account_data && $ar_tcrf_account_data['scriptlet_enabled'])
    {
        add_action( 'wp_enqueue_scripts', 'ar_tcrf_add_stylesheets' );
        add_action( 'wp_enqueue_scripts', 'ar_tcrf_add_js_scripts' );
        add_action('wp_footer', 'ar_tcrf_insert_scriptlet');
    }
}

function ar_tcrf_enable_scriptlet()
{
    $ar_tcrf_account_data = get_option('ar_tcrf_account_data');
    $ar_tcrf_account_data['scriptlet_enabled'] = true;
    $ar_tcrf_account_data['window_size'] = $_POST['ar_tcrf_window_size'];
    $ar_tcrf_account_data['allow_camera'] = isset($_POST['ar_tcrf_allow_camera']) ? true : false;
    $ar_tcrf_account_data['start_button_text'] = $_POST['ar_tcrf_start_button_text'];
    $ar_tcrf_account_data['chat_button_text'] = $_POST['ar_tcrf_chat_button_text'];
    $ar_tcrf_account_data['dialog_top_bar_color'] = $_POST['ar_tcrf_top_bar_color'];

    update_option('ar_tcrf_account_data', $ar_tcrf_account_data);

    ar_tcrf_show_settings_view();
}

function ar_tcrf_disable_scriptlet()
{
    $ar_tcrf_account_data = get_option('ar_tcrf_account_data');
    $ar_tcrf_account_data['scriptlet_enabled'] = false;

    update_option('ar_tcrf_account_data', $ar_tcrf_account_data);

    ar_tcrf_show_settings_view();
}

function ar_tcrf_logout()
{
    delete_option('ar_tcrf_account_data');
    ar_tcrf_show_login_view();
}

function ar_tcrf_login()
{
    if (!($username = filter_var($_POST['ar_tcrf_username'], FILTER_VALIDATE_EMAIL)))
    {
        ar_tcrf_show_login_view('E-mail format is invalid!');
        exit();
    }
     
    $response = get_scriptlet_data($username, $_POST["ar_tcrf_password"]);

    if (!$response)
    {
   		$response = (object) array(
   						'error' => true,
   						'error_message' => "Connection with The Client Relations Factory failed, please retry in a few moments."
    				);
    }

    if (!$response->error && $response->found)
    {
        update_option('ar_tcrf_account_data', 
            array(
                    'username' => $username,
		    		'avatar_type' => $response->avatar_type,
                    'avatar_id' => $response->avatar_id,
                    'avatar_name' => $response->avatar_name,
                    'avatar_password' => $response->avatar_password,
                    'scriptlet_enabled' => false,
                    'window_size' => 'Big',
                    'allow_camera' => false,
                    'button_text' => 'Chat with me!'
				
                )
        );
        
        ar_tcrf_show_settings_view();
    }
    else
    {
        ar_tcrf_show_login_view($response->error_message);
    }
}

function get_scriptlet_data($username, $password)
{
    
    $ar_tcrf_option_manager = new ConfigManager("config.ini");
    $scriplet_service_url  = $ar_tcrf_option_manager->get_option('scriptlet_service_url');
    //$scriplet_service_url = 'http://www.theclientrelationsfactory.com/services/scriptlet-generator/get-data.php';
    //$scriplet_service_url = 'http://adele01.treelogic.local/services/scriptlet-generator/get-data.php';

    $public_key = file_get_contents(plugin_dir_path(__FILE__) . 'pub.key');

    $login_data = json_encode(array('username' => $username, 'password' => $password));

    openssl_public_encrypt($login_data, $login_data, $public_key);

    $response = wp_remote_post($scriplet_service_url, array(
		'method' => 'POST',
		'timeout' => 45,
		'redirection' => 5,
		'httpversion' => '1.0',
        'content-type' => 'application/octet-stream',
		'blocking' => true,
		'headers' => array(),
		'body' => $login_data,
		'cookies' => array()
    	)
	);

    return json_decode($response['body']);
}

function ar_tcrf_insert_scriptlet()
{
	$ar_tcrf_account_data = get_option('ar_tcrf_account_data');

	if($ar_tcrf_account_data['avatar_type'] == 4 || $ar_tcrf_account_data['avatar_type'] == 5)
	{
    	    include(plugin_dir_path(__FILE__) . '/generate-scriptlet-chat.php');
	}
	else
    	{
            include(plugin_dir_path(__FILE__) . '/generate-scriptlet-3d.php');
	}   
}

function ar_tcrf_show_login_view($error_message = false)
{
    include(plugin_dir_path(__FILE__) . '/views/login-form.php');
}

function ar_tcrf_show_settings_view($error_message = false)
{
    $ar_tcrf_account_data = get_option('ar_tcrf_account_data');

    if($ar_tcrf_account_data['avatar_type'] == 4 || $ar_tcrf_account_data['avatar_type'] == 5)
    {
        include(plugin_dir_path(__FILE__) . '/views/settings-form-chat.php');
    }
    else
    {
        include(plugin_dir_path(__FILE__) . '/views/settings-form-3d.php');
    }    
}

function ar_tcrf_show_noadmin_view()
{
    include(plugin_dir_path(__FILE__) . '/views/no-admin.php');
}

function ar_tcrf_add_stylesheets() 
{
    wp_enqueue_style('ar_tcrf_stylesheet', plugins_url('/assets/css/fiona-avatar.css', __FILE__));
}

function ar_tcrf_add_js_scripts()
{
}

?>