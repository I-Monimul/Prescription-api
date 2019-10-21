<?php

namespace Utils;

class Tools extends \Prefab
{
	public static function generateAPIKey()
	{
		$app = \Base::instance();

		return md5($app->get('SALT') . uniqid('', true));
	}

	public static function emailsFromTextField($string)
	{
		$emails = explode(',', $string);
		return array_filter(array_map('trim', $emails));
	}

	public static function toFixed($num)
	{
		return number_format($num, 2, '.', '');
	}
}
