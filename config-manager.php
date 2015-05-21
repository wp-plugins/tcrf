<?php

class ConfigManager
{

	public function __construct($file_name){
        	$this->tcrf_options = parse_ini_file($file_name);
	}

	public function get_array(){
		return $this->tcrf_options;
	}

	public function get_option($option_name){
        	return $this->tcrf_options[$option_name];
	}
}
?>
