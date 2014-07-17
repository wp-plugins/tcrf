<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
{
    exit();
}

delete_option('ar_tcrf_account_data');

?>