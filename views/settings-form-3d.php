<?php 

    wp_enqueue_style('ar_tcrf_colorpicker_stylesheet', plugins_url('../assets/css/colpick.css', __FILE__));
    wp_enqueue_script('ar_tcrf_colorpicker_js', plugins_url('../assets/js/jscolor/jscolor.js', __FILE__));

    $ar_tcrf_account_data = get_option('ar_tcrf_account_data'); 

?>

<style>
  a { text-decoration: none; }
  .button-no-bold { font-weight: normal; }
</style>

<div class="wrap">
<h2><a href="http://www.theclientrelationsfactory.com" target="_blank"> The Client Relations Factory </a></h2>

<p>Your linked account is <?php echo '<strong>' . $ar_tcrf_account_data['username'] . '</strong>'; ?></p>

<table class="form-table">
    <form method="post" id="ar_tcrf_vr_settings">
    	<input type="hidden" name="ar_tcrf_action" value="enable-scriptlet">
        <tr valign="top">
            <th scope="row">Start Button Text</th>
            <td>
                <input type="text" name="ar_tcrf_start_button_text" minlength="1" maxlength="15"
                	value="<?php echo isset($ar_tcrf_account_data['start_button_text']) ? $ar_tcrf_account_data['start_button_text'] : "Chat with me!" ?>" 
                <?php 
                    if ($ar_tcrf_account_data['scriptlet_enabled']) echo " readonly "; 
                ?>
                title="Defines the text that will be displayed inside Virtual Robot's start button"
                > 
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Chat Button Text</th>
            <td>
                <input type="text" name="ar_tcrf_chat_button_text" minlength="1" maxlength="10"
                	value="<?php echo isset($ar_tcrf_account_data['chat_button_text']) ? $ar_tcrf_account_data['chat_button_text'] : "Send" ?>" 
                <?php 
                    if ($ar_tcrf_account_data['scriptlet_enabled']) echo " readonly "; 
                ?>
                title="Defines the text that will be displayed into the Send button of Virtual Robot chat dialog"
                > 
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Avatar Size</th>
            <td>
            	<select name="ar_tcrf_window_size"
                <?php 
                    if ($ar_tcrf_account_data['scriptlet_enabled']) echo " disabled "; 
                ?>
                title="Sets the size of your Virtual Robot display frame" 
                >
      				<option 
                    <?php 
                        if (strcasecmp($ar_tcrf_account_data['window_size'], "Small") == 0) echo " selected "; 
                    ?>
                    value="Small">Small
                    </option>
      				<option 
                    <?php 
                        if (strcasecmp($ar_tcrf_account_data['window_size'], "Big") == 0 || !isset($ar_tcrf_account_data['window_size'])) 
                        	echo " selected "; 
                    ?>
                    value="Big">Big
                    </option>
    			</select>
    		</td>
        </tr>
        <tr valign="top">
            <th scope="row">Top bar widget color</th>
            <td>
            	<input type="text" name="ar_tcrf_top_bar_color" 
            		<?php if (isset($ar_tcrf_account_data['dialog_top_bar_color'])) 
            			echo 'value="' . $ar_tcrf_account_data['dialog_top_bar_color'] . '"'; 
            		?>
            		class="color {pickerFaceColor:'transparent', pickerFace:3, pickerBorder:0, pickerInsetColor:'black', slider:false, pickerPosition:'right', hash:true}"
                <?php 
                    if ($ar_tcrf_account_data['scriptlet_enabled']) echo " disabled "; 
                ?>
                title="Sets the color of your Virtual Robot's widget dialog top bar" 
                >
    		</td>
        </tr>

        <!--<tr valign="top">
            <th scope="row">Allow Camera</th>
            <td>
            <input 
            <?php
                if ($ar_tcrf_account_data['scriptlet_enabled']) echo " disabled "; 
                if ($ar_tcrf_account_data['allow_camera']) echo " checked ";
            ?>
            type="checkbox" name="ar_tcrf_allow_camera" value="true"  
            title="Allows users to use their camera when interacting with your Virtual Robot!"/>

            </td>
        </tr>-->
    </form>
        <tr valign="top">
            <th>
                <form method="post">
                    <input type="hidden" name="ar_tcrf_action" value="logout">
                    <input class="button button-primary button-no-bold" type="submit" value="Unlink Account" title="Detach your account from the plugin"/>
                </form>
            </th>
            <?php 
                if (!$ar_tcrf_account_data['scriptlet_enabled']) 
                { 
            ?>
            <td>
                <button class="button button-primary" class="" title="Enables your Virtual Robot integration for this site" onclick='document.getElementById("ar_tcrf_vr_settings").submit();'>Enable Virtual Robot!</button>
            </td>
            <?php 
                }
                else 
                { 
            ?>
            <td>
                <form method="post">
                    <input type="hidden" name="ar_tcrf_action" value="disable-scriptlet">
                    <input class="button button-primary" type="submit" value="Disable Virtual Robot" title="Disables your Virtual Robot's integration for this site"/>
                </form>
            </td>
            <?php 
            }
            ?>
        </tr>
    </table>
</div>
