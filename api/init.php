<?php

chdir("/var/www/html/api");

require 'common.php';

unlink("/tmp/boot.random");
file_put_contents("/tmp/boot.random", generateRandomString(5));

loadSettings(true);
setupGPIO();
saveSettings();

function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';

	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}

	return $randomString;
}

