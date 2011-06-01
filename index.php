<?php

/*
 * Wordpress Database Import - Wordpress to WolfCMS importing plugin
 *
 * Copyright (c) 2010 Johan BLEUZEN  and  Matthew COLEMAN
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   https://github.com/jbleuzen/Wolf_WPDB_Import
 */

/* Security measure */
if (!defined('IN_CMS')) { exit(); }

/**
 * Root location where WPDB Import lives.
 */
define( 'WPDB_ROOT', URI_PUBLIC.'wolf/plugins/wpdb_import');

/**
 * Version of the plugin
 */
define( 'WPDB_VERSION', '1.0.1');

Plugin::setInfos(array(
    'id'          			=> 'wpdb_import',
    'title'       			=> __('Wordpress Database Import'),
    'description' 			=> __('Used to populate a WolfCMS installation from a Wordpress eXtended RSS file.'),
    'version'     			=> WPDB_VERSION,
   	'license'     			=> 'MIT',
	'author'      			=> 'Johan Bleuzen, Matthew Coleman',
    'website'     			=> '',
    'update_url'  			=> '',
    'require_wolf_version' 	=> '0.7.0'
));

Plugin::addController('wpdb_import', __('WPDB Import'));
