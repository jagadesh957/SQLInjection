<?php

    /**
     *	Users plugin
     *
     *	@package Monstra
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2012 Romanenko Sergey / Awilum
     *	@version 1.0.0
     *
     */


    // Register plugin
    Plugin::register( __FILE__,                    
                    __('Users', 'users'),
                    __('Users manager', 'users'),  
                    '1.0.0',
                    'Awilum',                 
                    'http://monstra.org/',
                    null,
                    'box');

    // Include Users Admin
    Plugin::Admin('users', 'box');