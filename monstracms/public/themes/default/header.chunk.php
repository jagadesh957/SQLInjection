<!DOCTYPE html>
<html lang="en">        
    <head>          
        <meta charset="utf-8">
        <title><?php echo Site::name() . ' - ' . Site::title(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <meta name="description" content="<?php echo Site::description(); ?>" />
        <meta name="keywords" content="<?php echo Site::keywords(); ?>" />
        <meta name="robots" content="<?php echo Page::robots(); ?>" />
        <?php Stylesheet::add('public/themes/default/css/style.css'); ?>
        <?php Stylesheet::load(); ?>
        <?php Javascript::load(); ?>
        <?php Action::run('theme_header'); ?>
        
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <link rel="icon" href="<?php echo Site::url(); ?>favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="<?php echo Site::url(); ?>favicon.ico" type="image/x-icon" />
    </head>
        
<body>