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

/**
 * Root location where WordPress Import plugin lives.
 */
define('WPDB_ROOT', URI_PUBLIC.'wolf/plugins/wpdb_import');

/**
 * Version of the plugin
 */
define('WPDB_VERSION', '0.0.8');

Plugin::setInfos(array(
    'id'          => 'wpdb_import',
    'title'       => __('WordPress DataBase Import'), 
    'description' => __('Import data from a WordPress DataBase into WolfCMS.'), 
    'version'     => '0.0.7', 
    'license'     => 'MIT',
    'author'      => 'Johan Bleuzen',
    'update_url'  => 'http://www.johanbleuzen.fr/wolfcms_update.xml',
    'website'     => 'https://github.com/jbleuzen/Wolf_WPDB_Import',
		'type'        => 'backend',
    'require_wolf_version' => '0.7.0'
));

/* Adds the tab in admin */
if (defined('CMS_BACKEND'))  {   
    Plugin::addController('wpdb_import', 'WP Import');
}
