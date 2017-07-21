<?php

$cache_dir = "/var/lib/mpd/music/youtube";
$cache_enabled = 1;

$video = "";
if ( isset($_POST["url"]) ) {
	$url = $_POST["url"];
	$video = substr($url, -11);
}

if ( preg_match("/^[A-Za-z0-9_-]{11}$/", $video)===1 ) {
	$location = $cache_dir ."/". $video .".mp3";

	//Check if the file is cached
	if ( $cache_enabled==0 || !file_exists($location) ) {
		exec("youtube-dl -g -f140 -- ". $video, $ex_output);

		//Download the file in the background
		if ( $cache_enabled==1 ) {
			exec("/var/www/html/youtube_background.sh ". $video ." > /dev/null &");
		}

		$location = $ex_output[0];
	}

	if ( isset($_POST["clear"]) ) {
		exec("mpc clear");
	}

	exec("mpc add \"". $location ."\"");
	exec("mpc play");
}

header('Location: /#youtube');

