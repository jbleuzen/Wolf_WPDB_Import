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
    'version'     => '0.0.6', 
    'license'     => 'MIT',
    'author'      => 'Johan BLEUZEN',
    'require_wolf_version' => '0.7.0',
    'update_url'  => 'http://www.johanbleuzen.fr/wolfcms_update.xml',
    'website'     => 'https://github.com/jbleuzen/Wolf_WPDB_Import',
		'type'        => 'backend'
));

/* Stuff for backend. */
if (defined('CMS_BACKEND'))  {   
    Plugin::addController('wpdb_import', 'WP Import');
}
