<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="http://scioly.org/favicon.png">
	<link href="http://scioly.org/themes/1/js-image-slider.css" rel="stylesheet" type="text/css" />
    <script src="http://scioly.org/themes/1/js-image-slider.js" type="text/javascript"></script>
    <link href="http://scioly.org/generic.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="http://scioly.org/favicon.ico" type="image/x-icon"/>
    <title>Scioly.org Quizzing</title>

    <!-- JQuery note: this uses Google's CDN, because it is faster-->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

    <!-- Bootstrap core CSS -->
	<script src="http://scioly.org/dist/js/bootstrap.min.js"></script>
    <link href="http://scioly.org/dist/css/bootstrap.css" rel="stylesheet">
    <link href="http://scioly.org/main.css" rel="stylesheet">
    <link href="main.css" rel="stylesheet">
  </head>

  <body class="section-index ltr hasjs hastouch">

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php"><img src="http://scioly.org/logo-white.png" height="50px" width="170px"/></a>
        </div>
        <div class="collapse navbar-collapse in">
          <ul class="nav navbar-nav">
			<li ><a href="http://scioly.org/index.html">Home</a></li>
            <li><a href="http://scioly.org/phpBB3/index.php">Forums</a></li>
            <li><a href="http://scioly.org/wiki">Wiki</a></li>
            <li><a href="http://scioly.org/wiki/index.php/Test_Exchange">Test Exchange</a></li>
            <li><a href="http://scioly.org/chat.php">Chat</a></li>
	    <li class="active"><a href="http://scioly.org/phpBB3/Quiz" >Quizzing</a></li>
            <li><a href="http://gallery.scioly.org/">Image Gallery</a></li>
              <li><a href="http://scioly.org/phpBB3/ucp.php?mode=login">Login</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
    <div id="submenu">
       <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
	    <li class="active"><a href="index.php">Home</a></li>
            <li><a href="new_question.php">Add Questions</a></li>
            <li><a href="leaders.php">Leader Boards</a></li>
	    <li><a href="user.php">Your Statistics</a></li>

          </ul>
        </div>
    </div> 
<div class="container">