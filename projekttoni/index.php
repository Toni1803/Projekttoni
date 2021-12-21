<?php 
	# Stop Hacking attempt
	define('__APP__', TRUE);
	
	# Start session
    session_start();
	
	# Database connection
	include ("konekcijanabazu.php");
	
	# Variables MUST BE INTEGERS
    if(isset($_GET['menu'])) { $menu   = (int)$_GET['menu']; }
	if(isset($_GET['action'])) { $action   = (int)$_GET['action']; }
	
	# Variables MUST BE STRINGS A-Z
    if(!isset($_POST['_action_']))  { $_POST['_action_'] = FALSE;  }
	
	if (!isset($menu)) { $menu = 1; }
	
	# Classes & Functions
    include_once("functions.php");
 
	
print '
<html>
<head>
<title>Poliklinika "Osmijeh"</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="description" content="Poliklinika Osmijeh">
<meta name="keywords" content="HTML,CSS,XML">
<meta name="author" content="Antonio Starinec">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="stil.css">
</head>
<body>
<header>
		<div class="blok1"></div>
		<nav>';
           include("menu.php");
		print '</nav>
	</header>
	<main>';
		if (isset($_SESSION['message'])) {
			print $_SESSION['message'];
			unset($_SESSION['message']);
		}
	
	# Homepage
	if (!isset($menu) || $menu == 1) { include("home.php"); }
	
	# News
	else if ($menu == 2) { include("vijesti.php"); }
	
	# Contact
	else if ($menu == 3) { include("kontakt.php"); }
	
	# About us
	else if ($menu == 4) { include("o nama.php"); }
	
	# Examinations
	else if ($menu == 5) { include("pretrage.php"); }
	
	# Gallery
	else if ($menu == 6) { include("galerija.php"); }
	
	# Register
	else if ($menu == 7) { include("registracija.php"); }
	
	# Signin
	else if ($menu == 8) { include("prijava.php"); }
	
	# Admin webpage
	else if ($menu == 9) { include("admin.php"); }
	
	print '
	</main>
	<footer>
	<p>Copyright &copy; 2021 Antonio Starinec. <a href="https://github.com/Toni1803?tab=repositories"><img src="GitHub-Mark.png" title="Github" alt="Github"></a></p>
<body/>
</html>';
?>