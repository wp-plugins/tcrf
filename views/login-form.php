<?php
	wp_enqueue_style('ar_tcrf_login_form_stylesheet', plugins_url('../assets/css/login-form.css', __FILE__));
?>

<div class="wrap">
<h2><a href="http://www.theclientrelationsfactory.com" target="_blank"> The Client Relations Factory </a></h2>


<?php 
    if ($error_message)
    {
?>
    <div id="login-error">
    	<div class="alert-box error-box"><span>error: </span>
        <?php 
            if ($error_message)
            { 
                echo $error_message; 
            }
            else
            {
                echo "Cannot connect with The Client Relations Factory, please try again in a few minutes. If problem persists, please contact us in support@theclientrelationsfactory.com";
            }
        ?></div>
    </div>
<?php
    }
?>

<form method="post">
	<input type="hidden" name="ar_tcrf_action" value="login">
    <table class="form-table tcrf-form-table">
        <tr valign="top">
        <th scope="row">Username</th>
        <td><input required type="email" name="ar_tcrf_username" value="" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Password</th>
        <td><input required type="password" name="ar_tcrf_password" value="" /></td>
        </tr>
        <tr>
            <th scope="row">

            </td>
            <td>
                <input class="button button-primary" type="submit" value="Link Account!" title="Link your account and embed your Virtual Robot!"/>
            </td>
        </tr>
    </table>

<div class="bubble me tcrf-bubble-login">Please Sign In with your The Client Relations Factory credentials. If you don't have an account yet... <br /><a href="http://www.theclientrelationsfactory.com/#price" target="_blank" title="Get your account now!"><p class="tcrf-register-now"><strong>Register now!</strong></p></a></div>

</form>
</div>