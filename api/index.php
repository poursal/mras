<?php

require 'vendor/autoload.php';
require 'common.php';

$app = new \Slim\Slim(array(
	'debug' => false,
	'log.enabled' => false
));

/*
$app = new \Slim\Slim(array(
	'debug' => true,
	'log.enabled' => true,
	'log.level' => \Slim\Log::DEBUG,
	'log.writer' => new \Slim\LogWriter(fopen('/tmp/log', 'a'))
));
*/

$app->response->headers->set('Content-Type', 'application/json');

$app->get('/', function () use ($app) {
	global $_SETTINGS;

	if ( loadSettings() ) {
		$app->response->setBody(json_encode($_SETTINGS));
		unlockSettings();
	}
	else {
		$app->response->setStatus(409);
	}
});

$app->get('/speaker/:name/:status', function ($name, $status) use ($app) {
	global $_SETTINGS;

	if ( loadSettings(true) ) {
		$speakerStatus = toggleSpeaker($name, $status, true);

		if ( $speakerStatus==200 ) {
			$app->response->setBody(json_encode($_SETTINGS));
			saveSettings();
		}
		else {
			$app->response->setStatus($speakerStatus);
			unlockSettings();
		}
	}
	else {
		$app->response->setStatus(409);
	}
});

$app->get('/speaker/group/:name/:status', function ($name, $status) use ($app) {
	global $_SETTINGS;

	if ( loadSettings(true) ) {
		$groups = $_SETTINGS["speakers"]["groups"];
		$speakers = null;

		foreach($groups as $group) {
			if ( $group["name"]==$name ) {
				$speakers = $group["speakers"];
				break;
			}
		}

		if ( $speakers!=null ) {
			$overallStatus = 204;

			if ( $_SETTINGS["HomeTheater"]["status"]=="on" ) {
				executeCommand($_SETTINGS["HomeTheater"]["commands"]["VolumeMute"]);
				sleep(1);
			}

			foreach($speakers as $speaker) {
				$speakerStatus = toggleSpeaker($speaker, $status);

				if ( $speakerStatus==200 ) {
					$overallStatus = 200;
				}
				else if ( $speakerStatus!=204 ) {
					$overallStatus = 500;
					break;
				} 
			}

			if ( $_SETTINGS["HomeTheater"]["status"]=="on" ) {
				executeCommand($_SETTINGS["HomeTheater"]["commands"]["VolumeMute"]);
			}

			if ( $overallStatus==204 ) {
				$app->response->setStatus($overallStatus);
				unlockSettings();
			}
			else {
				$app->response->setStatus($overallStatus);
				$app->response->setBody(json_encode($_SETTINGS));
				saveSettings();
			}
		}
		else {
			$app->response->setStatus(404);
			unlockSettings();
		}
	}
	else {
		$app->response->setStatus(409);
	}
});

$app->get('/command/:name', function ($name) use ($app) {
	global $_SETTINGS;

	if ( loadSettings(true) ) {
		$status = checkStatus();

		if ( $status==200 ) {
			if ( isset($_SETTINGS["HomeTheater"]["commands"][$name]) ) {
				executeCommand($_SETTINGS["HomeTheater"]["commands"][$name]);

				if ( $name=="power" ) {
					handlePowerToggle();

					$app->response->setBody(json_encode($_SETTINGS));
					saveSettings();
				}
				else if ( $name=="VolumeMute" ) {
					if ( $_SETTINGS["HomeTheater"]["status"]=="muted" )
						$_SETTINGS["HomeTheater"]["status"] = "on";
					else if ( $_SETTINGS["HomeTheater"]["status"]=="on" )
						$_SETTINGS["HomeTheater"]["status"] = "muted";

					$app->response->setBody(json_encode($_SETTINGS));
					saveSettings();
				}
				else {
					$app->response->setStatus(204);
					unlockSettings();
				}
			}
			else {
				$app->response->setStatus(404);
				unlockSettings();
			}
		}
		else {
			$app->response->setStatus($status);
			unlockSettings();
		}
	}
	else {
		$app->response->setStatus(409);
	}
});

$app->get('/media/play/:id', function ($id) use ($app) {
	global $_SETTINGS;

	$id = intval($id);

	if ( $id>0 ) {
		if ( loadSettings(false, "play ". $id) ) {
			$app->response->setBody(json_encode($_SETTINGS));
			unlockSettings();
		}
		else {
			$app->response->setStatus(409);
		}
	}
	else {
		$app->response->setStatus(404);
	}
});

$app->get('/media/:name', function ($name) use ($app) {
	global $_SETTINGS;

	//Available commands
	$commands["toggle"] = true;
	$commands["stop"] = true;
	$commands["prev"] = true;
	$commands["next"] = true;
	$commands["repeat"] = true;
	$commands["random"] = true;
	$commands["clear"] = true;

	if ( isset($commands[$name]) && $commands[$name] ) {
		if ( loadSettings(false, $name) ) {
			$app->response->setBody(json_encode($_SETTINGS));
			unlockSettings();
		}
		else {
			$app->response->setStatus(409);
		}
	}
	else {
		$app->response->setStatus(404);
	}
});

$app->get('/media/content/lsplaylists', function () use ($app) {
	mediaExecute($app, "lsplaylists");
});

$app->get('/media/content/playlist', function () use ($app) {
	mediaExecute($app, "-f %file% playlist");
});

$app->get('/media/content/load/:playlist+', function ($playlist) use ($app) {
	if ( is_array($playlist) )
		$combined = implode("/", $playlist);
	else
		$combined = $playlist;

	mediaExecute($app, "load \"". $combined ."\"");
});

function mediaExecute($app, $command) {
	global $_SETTINGS;

	$output = null;
	exec('mpc '. $command, $output);

	if ( loadSettings() ) {
		if ( $command=="lsplaylists" )
			$_SETTINGS["media"]["playlist"]["all"] = $output;
		else if ( $command=="-f %file% playlist" ) {
			for($i=0; $i<count($output); $i++) {
				$pathinfo = pathinfo($output[$i]);
				$_SETTINGS["media"]["playlist"]["current"][] = $pathinfo['filename'];
			}
		}

		$app->response->setBody(json_encode($_SETTINGS));
		unlockSettings();
	}
	else {
		$app->response->setStatus(409);
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

$app->run();

