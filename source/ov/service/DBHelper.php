<?php 

namespace ov\service;

/**
 * Wraps DB fucntionality so it can be ued by different plugins
 * @author navid
 *
 */
class DBHelper {
	
	public static function getAccessKey(){
		return get_option('access_key');
	}
	
	public static function getAssociatesTag(){
		return get_option('associate_tag');
	}
	
	
	public static function getSecretKey(){
		return get_option('secret_key');
	}
	
	
}


?>