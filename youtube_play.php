<?php

$video = "";
if ( isset($_POST["url"]) ) {
	$url = $_POST["url"];
	$video = substr($url, -11);
}

if ( preg_match("/^[A-Za-z0-9_-]{11}$/", $video)===1 ) {
	exec("youtube-dl -g -f140 ". $video, $ex_output);

	if ( isset($_POST["clear"]) ) {
		exec("mpc clear");
	}

	exec("mpc add \"". $ex_output[0] ."\"");
	exec("mpc play");
}

header('Location: /#youtube');

