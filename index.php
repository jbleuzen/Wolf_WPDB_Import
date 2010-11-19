<?php

/*
 * WPDB Import - WolfCMS importing WordPress DataBase plugin
 *
 * Copyright (c) 2010 Johan BLEUZEN
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   https://github.com/jbleuzen/Wolf_WPDB_Import
 *
 */

Plugin::setInfos(array(
    'id'          => 'wpdb_import',
    'title'       => __('WordPress DataBase Import'), 
    'description' => __('Import data from a WordPress DataBase into WolfCMS'), 
    'version'     => '0.0.1', 
    'license'     => 'MIT',
    'author'      => 'Johan BLEUZEN',
    'require_wolf_version' => '0.7.0',
//    'update_url'  => '',
    'website'     => 'https://github.com/jbleuzen/Wolf_WPDB_Import'
));

/* Stuff for backend. */
if (defined('CMS_BACKEND'))  {   
    Plugin::addController('wpdb_import', 'WP Import');
}