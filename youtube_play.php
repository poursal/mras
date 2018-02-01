<?php

$cache_dir = "/var/lib/mpd/music/youtube";
$cache_enabled = 1;
$cli_debug = false;

if ( $cli_debug ) {
	$_POST["url"] = "https://www.youtube.com/watch?v=R6WboW4XjXk&list=PLthyxzm5T8bb2JstePOVBeN-hV_KizdK8";
}

$videos = null;
if ( isset($_POST["url"]) ) {
	$videos = getYoutubeList($_POST["url"]);

	if ( $videos==null ) {
		$video = getYoutubeVideoID($_POST["url"]);

		if ( $video!=null ) {
			$videos[] = $video;
		}
	}
}

if ( $videos!==null ) {
	$streams = getYoutubeVideosFromPlaylist($videos);

	if ( !$cli_debug ) {
		if ( isset($_POST["clear"]) ) {
			exec("mpc clear");
		}
	}

	foreach($streams as $key => $value) {
		if ( $key=='downloads' )
			continue;

		if ( $cli_debug ) {
			echo $key ."=>". $value ."\n";
		}
		else {
			exec("mpc add \"". $value ."\"");
		}
	}

	if ( !$cli_debug ) {
		exec("mpc play");

		if ( isset($streams['downloads']) ) {
			exec("/var/www/html/youtube_background.sh ". $streams['downloads'] ." > /dev/null &");
		}
	}
}

if ( !$cli_debug ) {
	header('Location: /#youtube');
}

/** Check the URL and return only the ID */
function getYoutubeVideoID($url) {
	$patterns = [
		'/youtu\.be\/([^#\&\?]{11})/',	// youtu.be/<id>
		'/\?v=([^#\&\?]{11})/',		// ?v=<id>
		'/\&v=([^#\&\?]{11})/',		// &v=<id>
		'/embed\/([^#\&\?]{11})/',	// embed/<id>
		'/\/v\/([^#\&\?]{11})/',	// /v/<id>
	];

	if ( preg_match('/youtu\.?be/', $url)==1 ) {
		foreach($patterns as $pattern) {
			if ( preg_match($pattern, $url, $match)==1 ) {
				$youtube_id = $match[1];

				return $youtube_id;
			}
		}

	}

	return null; //URL invalid
}

/** Check the URL (if it contains a playlist) and return all of IDs */
function getYoutubeList($url) {
	$parts = parse_url($url, PHP_URL_QUERY);
	parse_str($parts, $params);

	if ( !empty($params['list']) ) {
		$videos = [];

		//youtube-dl -i -J --flat-playlist -- PLthyxzm5T8bb2JstePOVBeN-hV_KizdK8
		$output = shell_exec('youtube-dl -i -J --flat-playlist -- '. escapeshellarg($params['list']));

		$json = json_decode($output, true);
		if ( $json!==null ) {
			foreach($json['entries'] as $entry) {
				$videos[] = $entry['id'];
			}
		}

		if ( count($videos)>0 ) {
			return $videos;
		}
	}

	return null;
}

/** Get the direct stream URL based on the video ID */
function getYoutubeVideoURL($id) {
	exec("youtube-dl -g -f140 -- ". $id, $ex_output, $return_var);

	if ( $return_var==0 ) {
		return $ex_output[0];
	}
	else {
		return null;
	}
}

/** Get an array of video IDs and replace it with the cache location or the direct stream URL */
function getYoutubeVideosFromPlaylist($videos) {
	global $cache_dir, $cache_enabled;

	$streams = [];

	if ( $videos!=null ) {
		$downloads = '';
		$counter = 1;

		foreach($videos as $video) {
			$location = $cache_dir ."/". $video .".mp3";

			if ( $cache_enabled==0 || !file_exists($location) ) {
				$downloads .= $video .' ';
				$location = getYoutubeVideoURL($video);

				if ( $location==null ) {
					continue;
				}
			}

			$streams['v'. $counter] = $location;
			$counter++;
		}

		if ( count($streams)>0 ) {
			if ( $cache_enabled==1 && strlen($downloads)>0 ) {
				$streams['downloads'] = substr($downloads, 0, -1);
			}

			return $streams;
		}
	}

	return null;
}

