<?php
	echo '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<head>
	<meta content="text/html" charset="UTF-8"/>
	<title>AuctionBase</title>
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/home.css"/>
	<style type="text/css">	body {padding-top: 60px;} </style>

	<script type="text/javascript" src="jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap-modal.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap-transition.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap-tooltip.js"></script>

	</head>
	<body>';
	include ("./navbar.php");
	include ("./sqlitedb.php");	
	echo '<div class="container">
			<div class="row-fluid">';	
?>