<?php

$_SETTINGS = array();
$_SETTINGS_FP = null;
$_SETTINGS_FILE = "status.json";

function loadSettings($forUpdate = false, $mediaCommand = null) {
	global $_SETTINGS, $_SETTINGS_FP, $_SETTINGS_FILE;

	$_SETTINGS_FP = fopen($_SETTINGS_FILE, "r+");

	if ( $forUpdate )
		$lockMode = LOCK_EX | LOCK_NB;
	else
		$lockMode = LOCK_SH | LOCK_NB;

	if( !flock($_SETTINGS_FP, $lockMode) ) {
		fclose($_SETTINGS_FP);

		$_SETTINGS_FP = null;
		return FALSE;
	}
	else {
		$allLines = "";
		while ( !feof($_SETTINGS_FP) ) {
			$allLines .= fgets ($_SETTINGS_FP);
		}

		$_SETTINGS = json_decode($allLines, true);

		//Read temperature
		$temp = file_get_contents("/sys/class/thermal/thermal_zone0/temp");
		$_SETTINGS["HomeTheater"]["temperature"] = round($temp/1000, 1);

		//Read uptime
		$_SETTINGS["HomeTheater"]["uptime"] = shell_exec('uptime -p');

		//Get media details
		mediaDetails($mediaCommand);

		populateMediaDetails();

		return TRUE;
	}
}

function saveSettings() {
	global $_SETTINGS, $_SETTINGS_FP;

	ftruncate($_SETTINGS_FP, 0);     // truncate file
	rewind($_SETTINGS_FP);

	fwrite($_SETTINGS_FP, json_encode($_SETTINGS));
	fflush($_SETTINGS_FP);           // flush output before releasing the lock

	unlockSettings();
}

function unlockSettings() {
	global $_SETTINGS_FP;

	flock($_SETTINGS_FP, LOCK_UN);    // release the lock
	fclose($_SETTINGS_FP);

	$_SETTINGS_FP = null;
}

function setupGPIO() {
	global $_SETTINGS;

	initGPIO($_SETTINGS["HomeTheater"]["wirringPI"]);
	$_SETTINGS["HomeTheater"]["status"] = "off";

	$speakers = array_keys($_SETTINGS["speakers"]);
	foreach ($speakers as $value) {
		if ( $value=="groups" )
			continue;

		initGPIO($_SETTINGS["speakers"][$value]["wirringPI"]);
		$_SETTINGS["speakers"][$value]["status"] = "off";
	}
}

function initGPIO($id) {
	exec("gpio mode ". $id ." output");
	writeGPIO($id, "off");
}

function writeGPIO($id, $status) {
	if ( $status=="on" ) {
		exec("gpio write ". $id ." 1");
	}
	else {
		exec("gpio write ". $id ." 0");
	}
}

function executeCommand($array) {
	foreach ($array as $value) {
		exec($value);
	}
}

function checkStatus() {
	global $_SETTINGS;

	return 200;
}

function handlePowerToggle() {
	global $_SETTINGS;
	$helloSecs = 20;
	$goodbyeSecs = 5;

	if ( $_SETTINGS["HomeTheater"]["status"]=="on" ||
			$_SETTINGS["HomeTheater"]["status"]=="mute" ) {
		$_SETTINGS["HomeTheater"]["status"] = "off";
		$_SETTINGS["HomeTheater"]["WaitUntil"] = time() + $goodbyeSecs + $_SETTINGS["HomeTheater"]["BackOff"]["PowerOff"];

		sleep($_SETTINGS["HomeTheater"]["BackOff"]["PowerOff"]);
		writeGPIO($_SETTINGS["HomeTheater"]["wirringPI"], "off");

		//Stop playback
		mediaDetails("stop");
		//Turn off speakers
		$speakers = array_keys($_SETTINGS["speakers"]);
		foreach($speakers as $name) {
			if ( $name=="groups" )
				continue;
			toggleSpeaker($name, "off");
		}

		sleep($goodbyeSecs);
	}
	else {
		$_SETTINGS["HomeTheater"]["status"] = "on";
		$_SETTINGS["HomeTheater"]["WaitUntil"] = time() + $helloSecs + $_SETTINGS["HomeTheater"]["BackOff"]["PowerOn"];

		writeGPIO($_SETTINGS["HomeTheater"]["wirringPI"], "on");
		sleep($helloSecs);

		executeCommand($_SETTINGS["HomeTheater"]["commands"]["power"]);
		sleep($_SETTINGS["HomeTheater"]["BackOff"]["PowerOn"]);
	}
}

function mediaDetails($command = null) {
	global $_SETTINGS;

	$output = null;
	$outcome[0] = 0;
	$outcome[1] = "None";
	$outcome[2] = "unknown";
	$outcome[3] = "off";
	$outcome[4] = "off";

	if ( $command==null )
		$command = "status";

	exec('mpc -f "Now: \[%position%\] [[%artist% - ]%title%]|[%file%]" '. $command, $output);

	for($i=0; $i<count($output); $i++) {
		if ( strpos($output[$i], "Now: ")===0 ) {
			$strPos = strpos($output[$i], "[");
			$endPos = strpos($output[$i], "]");

			$outcome[0] = intval(substr($output[$i], $strPos + 1, $endPos - $strPos - 1));

			//$pathinfo = pathinfo(substr($output[$i], $endPos + 2));
			//$outcome[1] = $pathinfo['filename'];
			$outcome[1] = substr($output[$i], $endPos + 2);
			if ( $outcome[1]==false ) {
				$outcome[1] = "Unknown";
			}

			if ( mb_strlen($outcome[1])>30 ) {
				$outcome[1] = mb_substr($outcome[1], 0, 30) ."...";
			}
		}
		else if ( strpos($output[$i], "[")===0 ) {
			$strPos = strpos($output[$i], "[");
			$endPos = strpos($output[$i], "]");

			$outcome[2] = substr($output[$i], $strPos + 1, $endPos - $strPos - 1);
		}
		else if ( strpos($output[$i], "volume")===0 ) {
			$parts = explode("   ", $output[$i]);

			for($j=0; $j<count($parts); $j++) {
				$parts2 = explode(":", $parts[$j]);

				if ( trim($parts2[1])=="on" )
					$onoff = "on";
				else
					$onoff = "off";

				if ( trim($parts2[0])=="repeat" )
					$outcome[3] = $onoff;
				else if (  trim($parts2[0])=="random" )
					$outcome[4] = $onoff;
			}
		}
	}

	$_SETTINGS["media"]["now"]["id"] = $outcome[0];
	$_SETTINGS["media"]["now"]["name"] = $outcome[1];
	$_SETTINGS["media"]["now"]["status"] = $outcome[2];
	$_SETTINGS["media"]["repeat"] = $outcome[3];
	$_SETTINGS["media"]["random"] = $outcome[4];

	return $outcome;
}

function populateMediaDetails() {
	global $_SETTINGS;

	$output = array();
	exec('mpc lsplaylists', $output);
	$_SETTINGS["media"]["playlist"]["all"] = $output;

	$output2 = array();
	$_SETTINGS["media"]["playlist"]["current"] = array();
	exec('mpc -f "[[%artist% - ]%title%]|[%file%]" playlist', $output2);
	for($i=0; $i<count($output2); $i++) {
		//$pathinfo = pathinfo($output2[$i]);
		//$_SETTINGS["media"]["playlist"]["current"][] = $pathinfo['filename'];
		$_SETTINGS["media"]["playlist"]["current"][] = $output2[$i];
	}
}

function toggleSpeaker($name, $status, $withMute = false) {
	global $_SETTINGS;

	if ( isset($_SETTINGS["speakers"][$name]) ) {
		if ( $status==$_SETTINGS["speakers"][$name]["status"] ) {
			return 204;
		}
		else {
			if ( $status=="on" )
				$parsedStatus = "on";
			else
				$parsedStatus = "off";

			if ( $withMute && $_SETTINGS["HomeTheater"]["status"]=="on" ) {
				executeCommand($_SETTINGS["HomeTheater"]["commands"]["VolumeMute"]);
				//sleep(1);
				usleep(500000);
			}

			writeGPIO($_SETTINGS["speakers"][$name]["wirringPI"], $parsedStatus);

			if ( $withMute && $_SETTINGS["HomeTheater"]["status"]=="on" ) {
				executeCommand($_SETTINGS["HomeTheater"]["commands"]["VolumeMute"]);
			}

			$_SETTINGS["speakers"][$name]["status"] = $parsedStatus;

			return 200;
		}
	}
	else {
		return 404;
	}
}

