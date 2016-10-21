<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Add Youtube music</title>

    <head profile="http://www.w3.org/2005/10/profile">
    <link rel="icon" type="image/png" href="music.png">

    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body style="background-color: #ebeef0">
    <div style="padding: 20px;">
	<form  action="youtube_play.php" method="post">
	  <div class="form-group">
	    <label for="exampleInputEmail1">Youtube video:</label>
	    <input type="text" class="form-control" id="url" name="url" placeholder="Youtube video URL">
	  </div>
	  <div class="checkbox">
	    <label>
	      <input type="checkbox" name="clear"> Clear playlist
	    </label>
	  </div>
	  <button type="submit" class="btn btn-default">Enqueue</button>
	</form>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
