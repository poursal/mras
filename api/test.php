<?php

require 'common.php';

loadSettings(true);

executeCommand($_SETTINGS["HomeTheater"]["commands"]["power"]);

if ( $_SETTINGS["HomeTheater"]["status"]=="on" ||
	$_SETTINGS["HomeTheater"]["status"]=="mute" ) {
	powerOff();
}
else {
	powerOn();
}

saveSettings();

function powerOff() {
	global $_SETTINGS;
	$goodbyeSecs = 30;

	$_SETTINGS["HomeTheater"]["status"] = "off";
	$_SETTINGS["HomeTheater"]["WaitUntil"] = time() + $goodbyeSecs + $_SETTINGS["HomeTheater"]["BackOff"]["PowerOff"];

	echo("Sleeping for: ". $_SETTINGS["HomeTheater"]["BackOff"]["PowerOff"] ."\n");
	sleep($_SETTINGS["HomeTheater"]["BackOff"]["PowerOff"]);

	echo("Disable power\n");
	writeGPIO($_SETTINGS["HomeTheater"]["wirringPI"], "off");

	echo("Sleeping -for-: ". $goodbyeSecs ."\n");
	sleep($goodbyeSecs);

	for($i=0; $i<10; $i++) {
		executeCommand($_SETTINGS["HomeTheater"]["commands"]["VolumeMute"]);
		usleep(100000);
	}

	echo("Send power on IR\n");
	executeCommand($_SETTINGS["HomeTheater"]["commands"]["power"]);

	echo("DONE\n");
}

function powerOn() {
	global $_SETTINGS;
	$helloSecs = 10;

	$_SETTINGS["HomeTheater"]["status"] = "on";
	$_SETTINGS["HomeTheater"]["WaitUntil"] = time() + $helloSecs + $_SETTINGS["HomeTheater"]["BackOff"]["PowerOn"];

	echo("Enable power\n");
	writeGPIO($_SETTINGS["HomeTheater"]["wirringPI"], "on");

	echo("Sleeping -for-: ". $helloSecs ."\n");
	sleep($helloSecs);

	echo("Send power on IR\n");
	executeCommand($_SETTINGS["HomeTheater"]["commands"]["power"]);

	echo("Sleeping for: ". $_SETTINGS["HomeTheater"]["BackOff"]["PowerOn"] ."\n");
	sleep($_SETTINGS["HomeTheater"]["BackOff"]["PowerOn"]);

	echo("DONE\n");
}
