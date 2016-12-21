    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="bootstrap/js/bootstrap-waitingfor.min.js"></script>
    <script src="bootstrap/sammy-latest.min.js"></script>
    <script src="bootstrap/select2/select2.min.js"></script>

    <script type='text/javascript'>//<![CDATA[
	var speakerTemplate = '<div class="row-fluid"><div class="col-xs-6" style="text-align: right;"><h4>%SPEAKER_NAME%:</h4></div><div class="col-xs-6" style="text-align: left;"><button class="btn btn-%BUTTON_CLASS%" onclick="toggleSpeaker(\'%SPEAKER_NAME%\', \'%VOLUME_ICON%\')"><i class="fa fa-volume-%VOLUME_ICON% fa-3x"></i></button></div></div>';
	var commandTemplate = '<div class="row-fluid"><div class="col-xs-6" style="text-align: right;"><h4>%COMMAND_DISPLAY%:</h4></div><div class="col-xs-6" style="text-align: left;"><button class="btn btn-%BUTTON_CLASS%" onclick="executeCommand(\'%COMMAND_NAME%\')"><i class="fa fa-%COMMAND_ICON% fa-3x"></i></button></div></div>';
	var homeLine1 = '<div class="row-fluid"><div class="col-xs-12 col-md-offset-3 col-md-6" style="text-align: center;"><h3 style="white-space: nowrap; verflow: hidden; ext-overflow: ellipsis; height: 45px; line-height: 2.0;"><marquee direction="left">%SONG_DETAILS%</marquee></h3></div></div>';
	var homeLine2 = '<div class="row-fluid"><div class="col-xs-12" style="text-align: center;"><button class="btn btn-danger" onclick="executeCommand(\'power\')"><i class="fa fa-power-off fa-3x"></i></button><button class="btn btn-%BUTTON_CLASS_VOL%" onclick="executeCommand(\'VolumeDown\')"><i class="fa fa-volume-down fa-3x"></i></button><button class="btn btn-%BUTTON_CLASS_MUTE%" onclick="executeCommand(\'VolumeMute\')"><i class="fa fa-volume-off fa-3x"></i></button><button class="btn btn-%BUTTON_CLASS_VOL%" onclick="executeCommand(\'VolumeUp\')"><i class="fa fa-volume-up fa-3x"></i></button></div></div>';
	var homeLine3 = '<div class="row-fluid"><div class="col-xs-12" style="text-align: center;"><button class="btn btn-primary" onclick="executeMedia(\'prev\')"><i class="fa fa-step-backward fa-3x"></i></button><button class="btn btn-%BUTTON_CLASS_PLAY%" onclick="executeMedia(\'toggle\')"><i class="fa fa-%BUTTON_PLAY% fa-3x"></i></button><button class="btn btn-primary" onclick="executeMedia(\'next\')"><i class="fa fa-step-forward fa-3x"></i></button><button class="btn btn-%BUTTON_CLASS%" onclick="executeMedia(\'repeat\')"><i class="fa fa-repeat fa-3x"></i></button></div></div>';
	var homeLine4 = '<div class="row-fluid"><div class="col-xs-12" style="text-align: center;"><button class="btn btn-%BUTTON_CLASS1% btn2" onclick="toggleSpeaker(\'group/All\', \'%BUTTON_ICON1%\')"><i class="fa fa-globe fa-3x"></i><i class="fa fa-volume-%BUTTON_ICON1% fa-3x" style="width: 110px"></i></button><button class="btn btn-%BUTTON_CLASS2% btn2" onclick="toggleSpeaker(\'group/Living Room\', \'%BUTTON_ICON2%\')"><i class="fa fa-tv fa-3x"></i><i class="fa fa-volume-%BUTTON_ICON2% fa-3x" style="width: 90px"></i></button></div></div>';
	var homeLine5 = '<div class="row-fluid"><div class="col-xs-12" style="text-align: center;"><button class="btn btn-%BUTTON_CLASS1% btn2" onclick="toggleSpeaker(\'BedRoom3\', \'%BUTTON_ICON1%\')" style="color: #ffc2cd"><i class="fa fa-bed fa-3x"></i><i class="fa fa-volume-%BUTTON_ICON1% fa-3x" style="width: 90px"></i></button><button class="btn btn-%BUTTON_CLASS2% btn2" onclick="toggleSpeaker(\'BedRoom2\', \'%BUTTON_ICON2%\')" style="color: #00308f"><i class="fa fa-bed fa-3x"></i><i class="fa fa-volume-%BUTTON_ICON2% fa-3x" style="width: 90px"></i></button></div></div>';
	var homeLine6 = '<div class="row-fluid"><div class="col-xs-12" style="text-align: center;"><button class="btn btn-%BUTTON_CLASS1% btn2" onclick="toggleSpeaker(\'BedRoom1\', \'%BUTTON_ICON1%\')" style="color: #d3d3d3"><i class="fa fa-bed fa-3x"></i><i class="fa fa-volume-%BUTTON_ICON1% fa-3x" style="width: 90px"></i></button><button class="btn btn-%BUTTON_CLASS2% btn2" onclick="toggleSpeaker(\'Entrance\', \'%BUTTON_ICON2%\')"><i class="fa fa-building fa-3x"></i><i class="fa fa-volume-%BUTTON_ICON2% fa-3x" style="width: 110px"></i></button></div></div>';
	var homePlay1 = '<button class="btn btn-primary" onclick="executeMedia(\'prev\')"><i class="fa fa-step-backward fa-3x"></i></button><button class="btn btn-%BUTTON_CLASS_PLAY%" onclick="executeMedia(\'stop\')"><i class="fa fa-stop fa-3x"></i></button><button class="btn btn-%BUTTON_CLASS_PLAY%" onclick="executeMedia(\'toggle\')"><i class="fa fa-%BUTTON_PLAY% fa-3x"></i></button><button class="btn btn-primary" onclick="executeMedia(\'next\')"><i class="fa fa-step-forward fa-3x"></i></button>';
	var homePlay2 = '<button class="btn btn-%CLASS_REPEAT%" onclick="executeMedia(\'repeat\')"><i class="fa fa-repeat fa-3x"></i></button><button class="btn btn-%CLASS_RANDOM%" onclick="executeMedia(\'random\')"><i class="fa fa-random fa-3x"></i></button><button class="btn btn-danger" onclick="executeMedia(\'clear\')"><i class="fa fa-times fa-3x"></i></button>';
	var homePlay3 = '<button class="btn btn-primary" style="padding-left: 8px;" onclick="executeMedia(\'seek -30\')"><i class="fa fa-fast-backward fa-3x"></i></button><button class="btn btn-primary" style="padding-left: 8px;" onclick="executeMedia(\'seek -5\')"><i class="fa fa-backward fa-3x"></i></button><button class="btn btn-primary" onclick="executeMedia(\'seek 5\')"><i class="fa fa-forward fa-3x"></i></button><button class="btn btn-primary" style="padding-left: 8px;" onclick="executeMedia(\'seek 30\')"><i class="fa fa-fast-forward fa-3x"></i></button>';
	var tableHead = '<table class="table"><thead><tr><th>#</th><th style="width: 100%">Song</th><th>Actions</th></tr></thead><tbody>';
	var tableBody = '<tr><th style="vertical-align: middle;" scope="row">%PLAYLIST_ID%</th><td style="vertical-align: middle; text-align: left;">%PLAYLIST_SONG%</td><td style="text-align: left;"><button class="btn btn-small btn-primary" style="margin-left: 0px" onclick="executeMedia(\'play/%PLAYLIST_ID%\')"><i class="fa fa-chevron-right"></i></button></td></tr>';
	var tableFoot = '</tbody></table></div></div>';
	var statusTemplate = '<div class="row-fluid"><div class="col-xs-6" style="text-align: right;"><h4>%TITLE%:</h4></div><div class="col-xs-6" style="text-align: left;"><h4>%VALUE%</h4></div></div>';
	var youtubeTemplate = '<div class="row-fluid" style="margin-top: 20px;"><div class="col-xs-12 col-md-offset-3 col-md-6" style="text-align: left;"><form action="youtube_play.php" method="post"><div class="form-group"><label for="exampleInputEmail1">Youtube video:</label><input type="text" class="form-control input-lg" id="url" name="url" placeholder="Youtube video URL"></div><div class="checkbox input-lg"><label><input style="margin-top: 6px;" type="checkbox" name="clear"> Clear playlist</label></div><button type="submit" class="btn btn-primary" style="width: 120px;" onClick="$(this).prop(\'disabled\',true);">Enqueue</button></form></div></div>';
	var page = "home";
	var settings = null;
	var max_retries = 3;
	var booting = true;

	$(window).load(function(){
		$(function(){
			ajaxWrapper("/api/");
		});
	});

	function ajaxWrapper(apiURL, retries) {
		if (retries === undefined) {
			waitingDialog.show();
			retries = 0;
		}

		if ( retries>max_retries ) {
			waitingDialog.hide();

			alert("Max errors reached. Please refresh the page!");
			return;
		}

		$.ajax({
			url: apiURL,
			cache: false,
			dataType: "json",
			timeout: 60000,
			success: function(data, textStatus, jqXHR){
				waitingDialog.hide();

				if ( jqXHR.status!=204 ) {
					settings = data;
					if ( booting ) {
						booting = false;

						//Install Sammy
						$.sammy(function(){
							this.get('/', function() {
								page = "home";
								$(".navbar-collapse").collapse('hide');
								refreshPage();
							});

							this.get('#:page',function(){
								page = this.params["page"];
								$(".navbar-collapse").collapse('hide');
								refreshPage();
							});
						}).run();
					}
					else {
						refreshPage();
					}
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				setTimeout(function () { ajaxWrapper(apiURL, retries+1); }, retries*500 + 1000);
			}
		});
	}

	function refreshPage() {
		if ( page=="speakers" ) {
			$('#main-content').empty();

			for (var prop in settings["speakers"]) {
				if ( prop=="groups" )
					continue;

				if (settings["speakers"].hasOwnProperty(prop)) {
					var speakerClass = "info";
					var speakerIcon = "up";

					if ( settings["speakers"][prop]["status"]=="off" ) {
						speakerClass = "default";
						speakerIcon = "off";
					}

					tmpTemplate = speakerTemplate.replace(new RegExp("%SPEAKER_NAME%", 'g'), prop);
					tmpTemplate = tmpTemplate.replace(new RegExp("%BUTTON_CLASS%", 'g'), speakerClass);
					tmpTemplate = tmpTemplate.replace(new RegExp("%VOLUME_ICON%", 'g'), speakerIcon);

					$('#main-content').append(tmpTemplate);
				}
			}
		}
		else if ( page=="commands" ) {
			$('#main-content').empty();

			for (var prop in settings["HomeTheater"]["commands"]) {
				if (settings["HomeTheater"]["commands"].hasOwnProperty(prop)) {
					var commandDisplay = "Unknown";
					var commandIcon = "sign-in";
					var commandClass = "info";

					if ( prop=="power" ) {
						commandDisplay = "Power";
						commandIcon = "power-off";
						commandClass = "danger";
					}
					else if ( prop=="VolumeUp" ) {
						commandDisplay = "Volume up";
						commandIcon = "volume-up";
						commandClass = "info";
					}
					else if ( prop=="VolumeDown" ) {
						commandDisplay = "Volume down";
						commandIcon = "volume-down";
						commandClass = "info";
					}
					else if ( prop=="VolumeMute" ) {
						commandDisplay = "Mute volume";
						commandIcon = "volume-off";

						if ( settings["HomeTheater"]["status"]=="muted" ) {
							commandClass = "default";
						}
					}
					else if ( prop=="function" ) {
						commandDisplay = "Select input";
					}
					else if ( prop=="powerIR" ) {
						commandDisplay = "Send IR power";
						commandIcon = "rss";
					}

					if ( settings["HomeTheater"]["status"]=="off" && prop!="power" ) {
						commandClass = "default";
					}

					tmpTemplate = commandTemplate.replace(new RegExp("%COMMAND_DISPLAY%", 'g'), commandDisplay);
					tmpTemplate = tmpTemplate.replace(new RegExp("%COMMAND_NAME%", 'g'), prop);
					tmpTemplate = tmpTemplate.replace(new RegExp("%COMMAND_ICON%", 'g'), commandIcon);
					tmpTemplate = tmpTemplate.replace(new RegExp("%BUTTON_CLASS%", 'g'), commandClass);

					$('#main-content').append(tmpTemplate);
				}
			}
		}
		else if ( page=="home" ) {
			var songDetails = "<i>Empty</i>";
			var volClass = "info";
			var volMute = "info";
			var repeatClass = "primary";
			var playClass = "default";
			var playIcon = "play";
			var buttonClass = "success";
			var buttonIcon = "up";

			$('#main-content').empty();

			//Song details
			if ( settings["media"]["now"]["id"]!=0 )
				songDetails = "Song: " + settings["media"]["now"]["name"];
			$('#main-content').append(homeLine1.replace(new RegExp("%SONG_DETAILS%", 'g'), songDetails));

			//Sony controls
			if ( settings["HomeTheater"]["status"]=="off" ) {
				volClass = "default";
				volMute = "default";
			}
			if ( settings["HomeTheater"]["status"]=="muted" ) {
				volMute = "default";
			}
			tmpTemplate = homeLine2.replace(new RegExp("%BUTTON_CLASS_VOL%", 'g'), volClass);
			tmpTemplate = tmpTemplate.replace(new RegExp("%BUTTON_CLASS_MUTE%", 'g'), volMute);
			$('#main-content').append(tmpTemplate);

			if ( settings["media"]["repeat"]=="off" )
				repeatClass = "default";
			if ( settings["media"]["now"]["status"]=="playing" ) {
				playClass = "primary";
				playIcon = "pause";
			}
			tmpTemplate = homeLine3.replace(new RegExp("%BUTTON_CLASS%", 'g'), repeatClass);
			tmpTemplate = tmpTemplate.replace(new RegExp("%BUTTON_CLASS_PLAY%", 'g'), playClass);
			tmpTemplate = tmpTemplate.replace(new RegExp("%BUTTON_PLAY%", 'g'), playIcon);
			$('#main-content').append(tmpTemplate);

			if ( isSpeakerGroupMuted("All") ) {
				buttonClass = "default";
				buttonIcon = "off";
			}
			tmpTemplate = homeLine4.replace(new RegExp("%BUTTON_CLASS1%", 'g'), buttonClass);
			tmpTemplate = tmpTemplate.replace(new RegExp("%BUTTON_ICON1%", 'g'), buttonIcon);
			buttonClass = "success";
			buttonIcon = "up";
			if ( isSpeakerGroupMuted("Living Room") ) {
				buttonClass = "default";
				buttonIcon = "off";
			}
			tmpTemplate = tmpTemplate.replace(new RegExp("%BUTTON_CLASS2%", 'g'), buttonClass);
			tmpTemplate = tmpTemplate.replace(new RegExp("%BUTTON_ICON2%", 'g'), buttonIcon);
			$('#main-content').append(tmpTemplate);

			buttonClass = "info";
			buttonIcon = "up";
			if ( settings["speakers"]["BedRoom3"]["status"]=="off" ) {
				buttonClass = "default";
				buttonIcon = "off";
			}
			tmpTemplate = homeLine5.replace(new RegExp("%BUTTON_CLASS1%", 'g'), buttonClass);
			tmpTemplate = tmpTemplate.replace(new RegExp("%BUTTON_ICON1%", 'g'), buttonIcon);
			buttonClass = "info";
			buttonIcon = "up";
			if ( settings["speakers"]["BedRoom2"]["status"]=="off" ) {
				buttonClass = "default";
				buttonIcon = "off";
			}
			tmpTemplate = tmpTemplate.replace(new RegExp("%BUTTON_CLASS2%", 'g'), buttonClass);
			tmpTemplate = tmpTemplate.replace(new RegExp("%BUTTON_ICON2%", 'g'), buttonIcon);
			$('#main-content').append(tmpTemplate);

			buttonClass = "info";
			buttonIcon = "up";
			if ( settings["speakers"]["BedRoom1"]["status"]=="off" ) {
				buttonClass = "default";
				buttonIcon = "off";
			}
			tmpTemplate = homeLine6.replace(new RegExp("%BUTTON_CLASS1%", 'g'), buttonClass);
			tmpTemplate = tmpTemplate.replace(new RegExp("%BUTTON_ICON1%", 'g'), buttonIcon);
			buttonClass = "info";
			buttonIcon = "up";
			if ( settings["speakers"]["Entrance"]["status"]=="off" ) {
				buttonClass = "default";
				buttonIcon = "off";
			}
			tmpTemplate = tmpTemplate.replace(new RegExp("%BUTTON_CLASS2%", 'g'), buttonClass);
			tmpTemplate = tmpTemplate.replace(new RegExp("%BUTTON_ICON2%", 'g'), buttonIcon);
			$('#main-content').append(tmpTemplate);
		}
		else if ( page=="media" ) {
			var rowHeader = '<div class="row-fluid"><div class="col-xs-12" style="text-align: center;">';
			var contentAll = rowHeader;
			var randomClass = "primary";
			var repeatClass = "primary";
			var playClass = "default";
			var playIcon = "play";

			$('#main-content').empty();

			if ( settings["media"]["random"]=="off" )
				randomClass = "default";
			if ( settings["media"]["repeat"]=="off" )
				repeatClass = "default";
			if ( settings["media"]["now"]["status"]=="playing" ) {
				playClass = "primary";
				playIcon = "pause";
			}
			tmpTemplate = homePlay1.replace(new RegExp("%BUTTON_CLASS_PLAY%", 'g'), playClass);
			tmpTemplate = tmpTemplate.replace(new RegExp("%BUTTON_PLAY%", 'g'), playIcon);

			contentAll += tmpTemplate;
			contentAll += '</div></div>' + rowHeader;

			tmpTemplate = homePlay2.replace(new RegExp("%CLASS_RANDOM%", 'g'), randomClass);
			tmpTemplate = tmpTemplate.replace(new RegExp("%CLASS_REPEAT%", 'g'), repeatClass);
			contentAll += tmpTemplate;
			contentAll += '</div></div>' + rowHeader + homePlay3;
			contentAll += '</div></div><div class="row-fluid"><div class="col-xs-12 col-md-6 col-md-offset-3" style="text-align: center; margin-top: 20px; margin-bottom: 20px;">';

			contentAll += '<select id="pls" class="form-control input-lg">';
			contentAll += '<option></option>';
			for (var i=0; i<settings["media"]["playlist"]["all"].length; i++) {
				contentAll += '<option value="' + settings["media"]["playlist"]["all"][i] + '">' + settings["media"]["playlist"]["all"][i] + '</option>';
			}
			contentAll += '</select>';

			contentAll += '</div></div>' + rowHeader + tableHead;

			if ( settings["media"]["playlist"]["current"] !== undefined ) {
				for (var i=0; i<settings["media"]["playlist"]["current"].length; i++) {
					tmpTemplate = tableBody.replace(new RegExp("%PLAYLIST_ID%", 'g'), i+1);
					tmpTemplate = tmpTemplate.replace(new RegExp("%PLAYLIST_SONG%", 'g'), settings["media"]["playlist"]["current"][i]);
					contentAll += tmpTemplate;
				}
			}

			contentAll += tableFoot + '</div></div>';

			$('#main-content').append(contentAll);
			$("#pls").select2({
				placeholder: "Add playlist"
			});
			$("#pls").on("change", function(e) {
				executeMedia("content/load/" + e.val);
			});
		}
		else if ( page=="status" ) {
			$('#main-content').empty();

			$('#main-content').append(statusTemplate.replace(new RegExp("%TITLE%", 'g'), "Temperature").replace(new RegExp("%VALUE%", 'g'), settings["HomeTheater"]["temperature"] + " &deg;C"));
			$('#main-content').append(statusTemplate.replace(new RegExp("%TITLE%", 'g'), "Uptime").replace(new RegExp("%VALUE%", 'g'), settings["HomeTheater"]["uptime"]));
		}
		else if ( page=="youtube" ) {
			$('#main-content').empty();

			$('#main-content').append(youtubeTemplate);
		}

		$(".menu").removeClass("active");
		$("#menu-" + page).addClass("active").find("a").blur();
	}

	function toggleSpeaker(speakerName, speakerStatus) {
		var status = "off";
		if ( speakerStatus=="off" )
			status = "on";

		//console.log("Setting: " + speakerName + ", Status: " + status);
		ajaxWrapper("/api/speaker/" + speakerName + "/" + status);
	}

	function executeCommand(commandName) {
		//console.log("Command: " + commandName);
		ajaxWrapper("/api/command/" + commandName);
	}

	function executeMedia(commandName) {
		//console.log("Media: " + commandName);
		ajaxWrapper("/api/media/" + commandName);
	}

	function isSpeakerGroupMuted(group) {
		outcome = true;
		speakers = [];

		for (var i=0; i<settings["speakers"]["groups"].length; i++) {
			if ( settings["speakers"]["groups"][i]["name"]==group ) {
				speakers = settings["speakers"]["groups"][i]["speakers"];
				break;
			}
		}

		for (var i=0; i<speakers.length; i++) {
			if ( settings["speakers"][speakers[i]]["status"]=="on" ) {
				outcome = false;
				break;
			}
		}

		return outcome;
	}//]]> 
    </script>
  </body>
</html>
