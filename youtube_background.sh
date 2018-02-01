#!/bin/sh

LANG=C.UTF-8
BASE=/var/lib/mpd/music/youtube/

for var in "$@"
do
	youtube-dl --extract-audio --audio-format mp3 -o "$BASE%(id)s\\%(title)s.%(ext)s" -- $var
	FILENAME=`ls $BASE$var*`

	TITLE=${FILENAME%.*}
	TITLE=${TITLE#*\\}

	eyeD3 -t "$TITLE" --set-encoding=utf8 -- "$FILENAME"

	mv "$FILENAME" "$BASE$var.mp3"
	mpc update
done

