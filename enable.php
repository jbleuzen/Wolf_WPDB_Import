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
 * Any code below gets executed each time the plugin is enabled.
 */

 // TODO error checking on sql call

$result 	= 'success';
$message	= 'Install complete.';

// create table

$sql =
 
	"CREATE TABLE IF NOT EXISTS ".TABLE_PREFIX."wpdb_import (
	cat_import tinyint(1) NOT NULL DEFAULT '1',
	cat_slug tinyint(1) NOT NULL DEFAULT '1',
	cat_status enum('Draft','Preview','Published','Hidden','Archived') NOT NULL DEFAULT 'Published',
	cat_content tinyint(4) NOT NULL DEFAULT '2',
	cat_content_inc longtext,
	cat_user_sel varchar(20) DEFAULT NULL,
	cat_date tinyint(4) NOT NULL DEFAULT '0',
	cat_date_entry date DEFAULT NULL,
	cat_place tinyint(4) NOT NULL DEFAULT '0',
	cat_place_sel varchar(20) DEFAULT NULL,
	page_import tinyint(1) NOT NULL DEFAULT '1',
	page_slug tinyint(1) NOT NULL DEFAULT '1',
	page_status enum('Draft','Preview','Published','Hidden','Archived') NOT NULL DEFAULT 'Published',
	page_users tinyint(4) NOT NULL DEFAULT '0',
	page_users_sel1 varchar(20) DEFAULT NULL,
	page_users_sel2 varchar(20) DEFAULT NULL,
	page_date tinyint(4) NOT NULL DEFAULT '0',
	page_date_entry date DEFAULT NULL,
	page_place tinyint(4) NOT NULL DEFAULT '0',
	page_place_sel varchar(20) DEFAULT NULL,
	post_import tinyint(1) NOT NULL DEFAULT '1',
	post_slug tinyint(1) NOT NULL DEFAULT '1',
	post_status enum('Draft','Preview','Published','Hidden','Archived') NOT NULL DEFAULT 'Published',
	post_users tinyint(4) NOT NULL DEFAULT '0',
	post_users_sel1 varchar(20) DEFAULT NULL,
	post_users_sel2 varchar(20) DEFAULT NULL,
	post_date tinyint(4) DEFAULT NULL,
	post_date_entry date DEFAULT NULL,
	post_place tinyint(4) NOT NULL DEFAULT '0',
	post_place_sel varchar(20) DEFAULT NULL,
	post_uncat tinyint(1) NOT NULL DEFAULT '0',
	post_uncat_sel varchar(20) DEFAULT NULL,
	post_cat tinyint(4) NOT NULL DEFAULT '0' 
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

WpdbImportController::WPDB_callDB( $sql);

// delete any entries that may be present

$sql = "DELETE FROM ".TABLE_PREFIX."wpdb_import;";

WpdbImportController::WPDB_callDB( $sql);

// create initial entry

$defAuthor 		= User::findById(1);
$defAuthorName 	= $defAuthor->name; 

$initFields = array(
	'cat_import' 		=> 1,
	'cat_slug' 			=> 1,
	'cat_status'		=> "'Hidden'",
	'cat_user_sel'		=> "'$defAuthorName'",
	'cat_date'			=> 0,
	'cat_place'			=> 0,
	'page_import'		=> 1,
	'page_slug'			=> 1,
	'page_status'		=> "'Hidden'",
	'page_users'		=> 2,
	'page_users_sel2'	=> "'$defAuthorName'",
	'page_date'			=> 1,
	'page_place'		=> 0,
	'post_import'		=> 1,
	'post_slug'			=> 1,
	'post_status'		=> "'Published'",
	'post_users'		=> 2,
	'post_users_sel2'	=> "'$defAuthorName'",
	'post_date'			=> 1,
	'post_place'		=> 1,
	'post_uncat'		=> 0,
	'post_cat'			=> 0
	);

$sql =  'INSERT INTO '.TABLE_PREFIX.'wpdb_import (';

$count = 0;
foreach( array_keys( $initFields) as $field) {
	$count == 0 ? $count++ : $sql .= ', ';
	$sql .= $field;
}
$sql .= ') VALUES (';

$count = 0;
foreach( $initFields as $value) {
	$count == 0 ? $count++ : $sql .= ', ';
	$sql .= $value;
}
$sql .= ');';

WpdbImportController::WPDB_callDB( $sql);

?> 