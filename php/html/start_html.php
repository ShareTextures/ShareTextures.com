<?php
if (!in_array($_SERVER['PHP_SELF'], $GLOBALS['NO_CACHE']) && strlen(strstr($_SERVER['SCRIPT_FILENAME'], 'search/index'))<5 ){
  
    include($_SERVER['DOCUMENT_ROOT'].'/php/html/cache_top.php');
}  
?>
<html>
<head>
    <title>%TITLE%</title>

    <!-- CSS -->
    <link href='/css/style.css' rel='stylesheet' type='text/css' />
    <link href='/css/style_medium.css' rel='stylesheet' media='screen and (max-width: 1299px)' type='text/css' />
    <link href='/css/style_small.css' rel='stylesheet' media='screen and (max-width: 759px)' type='text/css' />
    <link rel="icon" type="image/png" href="favicon.png" />
    
    <!-- Google Fonts and Icons -->
    
    <link href="https://fonts.googleapis.com/css?family=Lato:900,400|Roboto:900,500,300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons&display=swap" rel="stylesheet">
	<link rel="alternate" href="/feed/" title="Free PBR Textures Feed" type="application/rss+xml" />

    %METAXAS%


    <!-- jQuery -->
    <script src="/js/jquery.min.js"></script>
    <script src="/core/core.js"></script>
    <script src="/js/functions.js"></script>

    <!-- Google analytics -->

<script async src="https://www.googletagmanager.com/gtag/js?id=UA-156618492-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-156618492-1');
</script>

</head>
<body>

<div class="main-wrapper">
