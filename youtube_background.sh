#!/bin/sh

LANG=C.UTF-8
BASE=/var/lib/mpd/music/youtube/

youtube-dl --extract-audio --audio-format mp3 -o "$BASE%(id)s\\%(title)s.%(ext)s" -- $1
FILENAME=`ls $BASE$1*`

TITLE=${FILENAME%.*}
TITLE=${TITLE#*\\}

eyeD3 -t "$TITLE" --set-encoding=utf8 "$FILENAME"

mv "$FILENAME" "$BASE$1.mp3"
mpc update

