<?php 

$ar_tcrf_account_data = get_option('ar_tcrf_account_data');

echo '
    <div id="fiona" class="fiona_main" style="display:none;">
        </div>
        <div id="fiona-button">
        <!-- Position bottom-right -->
        <span class="span_wrap">
            <span class="span_wrapped" id="starttalk" onclick="initFiona(this, usermail, usrid2, usrid1, avatarId)">
         ' . $ar_tcrf_account_data['start_button_text'] . '
        </span>
        </span>
  	</div>

    <script type="text/javascript"><!--//--><![CDATA[//><!--
            usermail ="' . $ar_tcrf_account_data['username'] . '";
            usrid2 = "' . md5($ar_tcrf_account_data['avatar_password']) . '";
            usrid1 = "' . md5($ar_tcrf_account_data['username'] . $ar_tcrf_account_data['avatar_id']) . '";
            avatar_size = "' . strtolower($ar_tcrf_account_data['window_size']) . '";
            avatarId = "' . $ar_tcrf_account_data['avatar_id'] . '";
            dialogTopBarColor = "' . $ar_tcrf_account_data['dialog_top_bar_color'] . '";
            btnText = "' . $ar_tcrf_account_data['chat_button_text'] . '";

            show_pop_up = true;

            allow_camera = ' . ($ar_tcrf_account_data['allow_camera'] ? 'true' : 'false') . ';

            (function() {
             var fi = document.createElement("script");
             fi.type = "text/javascript";
             fi.async = true;
             fi.src = "http://sm.adelerobots.com/js/servicemng.js";
             var s = document.getElementsByTagName("script")[0];
             s.parentNode.insertBefore(fi, s);
            })();
        //--><!]]></script>';
?>
