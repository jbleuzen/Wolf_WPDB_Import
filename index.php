<?php

/*
 * Funky Cache - Wolf CMS caching plugin
 *
 * Copyright (c) 2008-2009 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.appelsiini.net/projects/funky_cache
 *
 * ported to Wolf CMS by sartas (http://sartas.ru)
 *
 */

/**
 * Root location where WPDB Import plugin lives.
 */
 
//define('FUNKY_CACHE_ROOT', URI_PUBLIC.'wolf/plugins/wpdb_import');

Plugin::setInfos(array(
    'id'          => 'wpdb_import',
    'title'       => __('WordPress DataBase Import'), 
    'description' => __('Import data from a WordPress DataBase into WolfCMS'), 
    'version'     => '0.0.1', 
    'license'     => 'MIT',
    'author'      => 'Johan BLEUZEN',
    'require_wolf_version' => '0.7.0',
//    'update_url'  => 'http://www.appelsiini.net/download/frog-plugins.xml',
    'website'     => 'http://blog.johanbleuzen.fr'
));

/* Stuff for backend. */
if (defined('CMS_BACKEND'))  {
    
    Plugin::addController('wpdb_import', 'WP Import');
    
}