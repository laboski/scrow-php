<?php 

/**
 * 
 *this is where you pass in your pandascrow app data. 
 * 
 */
function app_config()
{
	$config = (object)[
			   'secret_key'    => '',
			   'public_key'    => '',
			   'app_industry'  => '',
			   'app_version'   => '',
			   'app_id'        => ''
	];

	return $config;
}

/**
 * 
 *this function loads a config function 
 * 
 */
function load_config(string $name = '')
{
	$val = call_user_func($name);
	return $val;
}