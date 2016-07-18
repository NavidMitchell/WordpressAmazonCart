<?php

namespace ov\service;

class AWSHelper {

	
	public static function makeAWSRequest($parameters){
		$query = self::makeAWSUrl($parameters,
								  DBHelper::getAccessKey(),
								  DBHelper::getAssociatesTag(),
								  DBHelper::getSecretKey());
		return simplexml_load_file($query);
	}
	
	public static function makeAWSUrl($parameters,$access_key,$associate_tag,$secret_key, $aws_version = '2008-06-28') {
		$host = 'ecs.amazonaws.com';
		$path = '/onca/xml';

		$query = array(
		    'Service' => 'AWSECommerceService',
		    'AWSAccessKeyId' => $access_key,
		    'AssociateTag' => $associate_tag,
		    'Timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
		    'Version' => $aws_version,
		);

		// Merge in any options that were passed in
		if (is_array($parameters)) {
			$query = array_merge($query, $parameters);
		}

		// Do a case-insensitive, natural order sort on the array keys.
		ksort($query);

		// create the signable string
		$temp = array();
		foreach ($query as $k => $v) {
			$temp[] = str_replace('%7E', '~', rawurlencode($k)) . '=' . str_replace('%7E', '~', rawurlencode($v));
		}
		$signable = implode('&', $temp);

		$stringToSign = "GET\n$host\n$path\n$signable";

		// Hash the AWS secret key and generate a signature for the request.
		$hex_str = hash_hmac('sha256', $stringToSign, $secret_key);
		$raw = '';
		for ($i = 0; $i < strlen($hex_str); $i += 2) {
			$raw .= chr(hexdec(substr($hex_str, $i, 2)));
		}

		$query['Signature'] = base64_encode($raw);
		ksort($query);

		$temp = array();
		foreach ($query as $k => $v) {
			$temp[] = rawurlencode($k) . '=' . rawurlencode($v);
		}
		$final = implode('&', $temp);

		return 'http://' . $host . $path . '?' . $final;
	}
	
}

?>