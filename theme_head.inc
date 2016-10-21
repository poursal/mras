<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Multiroom Music</title>

    <head profile="http://www.w3.org/2005/10/profile">
    <link rel="icon" type="image/png" href="music.png">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/select2/select2.css">
    <link rel="stylesheet" href="bootstrap/select2-bootstrap.css">
    <link rel="stylesheet" href="fontawesome/css/font-awesome.min.css">

    <style>
	.btn { height: 60px; width: 60px; margin: 6px; }
	.btn2 { height: 60px; width: 136px; margin: 6px; }
	.btn-small { height: 40px; width: 40px; margin: 6px; }
	h4 { margin-top: 25px; }
    </style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body style="padding-top: 60px;">
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Multiroom Music</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="menu" id="menu-home"><a href="#">Home</a></li>
            <li class="menu" id="menu-speakers"><a href="#speakers">Speakers</a></li>
            <li class="menu" id="menu-commands"><a href="#commands">Commands</a></li>
            <li class="menu" id="menu-media"><a href="#media">Media</a></li>
            <li class="menu" id="menu-youtube"><a href="#youtube">Youtube</a></li>
            <li class="menu" id="menu-status"><a href="#status">Status</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <div id="main-content" class="container">
