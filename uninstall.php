<?php

require_once( 'WpdbImportController.php');

/*
 * Wordpress Database Import - Wordpress to WolfCMS importing plugin
 *
 * Copyright (c) 2010 Johan BLEUZEN  and  Matthew COLEMAN
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   https://github.com/spacez320/wpdb_import
 */

/*
 * Any code below gets executed when the plugin is uninstalled.
 */

// remove table
    		
$sql = "DROP TABLE ".TABLE_PREFIX."wpdb_import;";
    		
WpdbImportController::WPDB_callDB( $sql);
   		
Flash::set( 'success', __( 'Uninstall complete.'));
redirect( get_url( 'plugin/'));

?>