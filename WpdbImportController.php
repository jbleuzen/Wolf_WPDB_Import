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

define( 'MAX_FILE_SIZE', 20);				// maximum filesize for uploaded .xml in megabytes

// TODO this should reflect the user's database and required usage
define( 'DB_TIME_FORMAT', 'Y-m-d H:i:s');	// DB time format
 
class WPDBImportController extends PluginController {

    private static function _checkPermission() {
        
    	AuthUser::load();
    	
        if ( !AuthUser::isLoggedIn()) redirect(get_url('login'));
    }

    function __construct() {
    	
        self::_checkPermission();
        
        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('../../plugins/wpdb_import/views/sidebar'));
    }

// Public view methods -------------------------------------------------------------------------------------------------

    function index() {
    	
        $this->display('wpdb_import/views/index', array());
    }
    
    function documentation() {
    	
        $this->display('wpdb_import/views/documentation', array());
    }
    
    function settings() {
    	
    	// get settings
    	
    	$result = self::getSettings();
    	$settings = $result[0];
    	
    	// display with settings
    	
    	$this->display( 'wpdb_import/views/settings',
    	 
    	array( 
    		'cat_import' 		=> $settings['cat_import'],
    		'cat_slug' 			=> $settings['cat_import'],
    		'cat_status' 		=> $settings['cat_status'],
    		'cat_content' 		=> $settings['cat_content'],
    		'cat_content_inc' 	=> $settings['cat_content_inc'],
    		'cat_user_sel' 		=> $settings['cat_user_sel'],
    		'cat_date' 			=> $settings['cat_date'],
    		'cat_date_entry' 	=> $settings['cat_date_entry'],
    		'cat_place' 		=> $settings['cat_place'],
    		'cat_place_sel'		=> $settings['cat_place_sel'],
    		'page_import'		=> $settings['page_import'],
    		'page_slug'			=> $settings['page_slug'],
    		'page_status'		=> $settings['page_status'],
    		'page_users'		=> $settings['page_users'],
    		'page_users_sel1'	=> $settings['page_users_sel1'],
    		'page_users_sel2'	=> $settings['page_users_sel2'],
    		'page_date'			=> $settings['page_date'],
    		'page_date_entry'	=> $settings['page_date_entry'],
    		'page_place'		=> $settings['page_place'],
    		'page_place_sel'	=> $settings['page_place_sel'],
    		'post_import'		=> $settings['post_import'],
    		'post_slug'			=> $settings['post_slug'],
    		'post_status'		=> $settings['post_status'],
    		'post_users'		=> $settings['post_users'],
    		'post_users_sel1'	=> $settings['post_users_sel1'],
    		'post_users_sel2'	=> $settings['post_users_sel2'],
    		'post_date'			=> $settings['post_date'],
    		'post_date_entry'	=> $settings['post_date_entry'],
    		'post_place'		=> $settings['post_place'],
    		'post_place_sel'	=> $settings['post_place_sel'],
    		'post_uncat'		=> $settings['post_uncat'],
    		'post_uncat_sel'	=> $settings['post_uncat_sel'],
    		'post_cat'			=> $settings['post_cat']
    	));
    }

//  Public methods, can be accessed with navigator  --------------------------------------------------------------------

    	/*
    	 * 
    	 */
		public function install() {
			
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
    		
	    	if( !self::_callDB( $sql)) {
				$result = false;
				$message = 'Could not install: creation of table failed.';	    		
	    	};

	    	// create initial entry
	    	
	    	$sql = 
	    	
			   	"INSERT INTO ".TABLE_PREFIX."wpdb_import (
			   	cat_import, 
			   	cat_slug, 
			   	cat_status, 
			   	cat_content, 
			   	cat_user_sel,
			   	cat_date,
			   	cat_date_entry,
			   	cat_place,
			   	cat_place_sel,
			   	page_import,
			   	page_slug,
			   	page_status,
			   	page_users,
			   	page_users_sel1,
			   	page_users_sel2,
			   	page_date, 
			   	page_date_entry,
			   	page_place,
			   	page_place_sel,
			   	post_import,
			   	post_slug,
			   	post_status,
			   	post_users,
			   	post_users_sel1,
			   	post_users_sel2,
			   	post_date,
			   	post_date_entry,
			   	post_place,
			   	post_place_sel,
			   	post_uncat,
			   	post_uncat_sel,
			   	post_cat) 
			   	VALUES ('1', '1', 'Published', '2', NULL, '0', NULL, '0', NULL, '1', '1', 'Published', '0', NULL, NULL, '0', NULL, '0', NULL, '1', '1', 'Published', '0', NULL, NULL, NULL, NULL, '0', NULL, '0', NULL, '0');";
	    	
	    	if( !self::_callDB( $sql)) {
				$result = false;
				$message = 'Could not install: initialization of table failed.';	    		
	    	};
	    	
	    	Flash::set( $result, __( $message));
	    	
			redirect( get_url( 'plugin/wpdb_import/'));
    	}
    	
    	/**
    	 * 
    	 *   TODO error checking on sql call
    	 */
    	public function uninstall() {
    		
    		// remove table
    		
    		$sql = "DROP TABLE ".TABLE_PREFIX."wpdb_import;";
    		
    		self::_callDB( $sql);
    		
    		Flash::set( 'success', __( 'Uninstall complete.'));
			redirect( get_url( 'plugin/'));
    	}
    	
    	
    	/**
    	 * 
    	 * TODO add php checks for invalid entries not caught by js
    	 * TODO add calendar functionality
    	 * TODO error checking on sql call
    	 */
    	public function setSettings() {
    		
    		$cat_content_inc = '\'\'';	// contents of category include
    		
    		// build sql statement
    		
    		$sql = "UPDATE ".TABLE_PREFIX."wpdb_import SET ";
    		
    		$sql .= 'cat_import = '.( $_POST['cat_import'] ? 1 : 0 );
    		
    		if( $_POST['cat_import']) {
    			$sql .= ', ';
	    		$sql .= 'cat_slug = '.( $_POST['cat_slug'] ? 1: 0 ) . ', ';
	    		$sql .= 'cat_status = \''.$_POST['cat_status'] . '\', ';
	    		$sql .= 'cat_content = '.$_POST['cat_content'] . ', ';
				if( $_POST['cat_content'] == 1 && isset( $_FILES['cat_content_inc'])) {
					if( $_FILES['cat_content_inc']['error'] != 0) { 
						// TODO handle error, upload fail
						exit('File upload failed.'); 
					}
					if( !( $cat_content_inc = '\''.file_get_contents( $_FILES['cat_content_inc']['tmp_name']). '\'')) {
						// TODO handle error, content read fail
						exit('File content read failed.');
					}
					$sql .= 'cat_content_inc = '. $cat_content_inc . ', ';
				}
				$sql .= 'cat_user_sel = \''.$_POST['cat_user_sel'] . '\', ';    		
	    		$sql .= 'cat_date = '.$_POST['cat_date'] . ', ';
	    		if( $_POST['cat_date'] == 1 && isset( $_POST['cat_date_entry']))
	    			$sql .= 'cat_date_entry = \''.$_POST['cat_date_entry'] . '\', ';
	    		$sql .= 'cat_place = '.$_POST['cat_place'];
	    		if( $_POST['cat_place'] == 1) {
	    			$sql .= ', ';
	    			$sql .= 'cat_place_sel = \''.$_POST['cat_place_sel'] . '\'';
	    		}
    		}
    		
    		$sql .= ', ';
    		$sql .= 'page_import = '.( $_POST['page_import'] ? 1 : 0 );
    		
    		if( $_POST['page_import']) {
    			$sql .= ', ';
	    		$sql .= 'page_slug = '.( $_POST['page_slug'] ? 1: 0 ) . ', ';
	  			$sql .= 'page_status = \''.$_POST['page_status'] . '\', ';
				$sql .= 'page_users = '.$_POST['page_users'] . ', ';
				if( $_POST['page_users'] == 1) 
					$sql .= 'page_users_sel1 = \''.$_POST['page_users_sel'] . '\', ';
				if( $_POST['page_users'] == 2)
					$sql .= 'page_users_sel2 = \''.$_POST['page_users_sel'] . '\', ';
				$sql .= 'page_date = '.$_POST['page_date'] . ', ';
				if( $_POST['page_date'] == 2 && isset( $_POST['page_date_entry']))
					$sql .= 'page_date_entry = \''.$_POST['page_date_entry'] . '\', ';
				$sql .= 'page_place = '.$_POST['page_place'];
				if( $_POST['page_place'] == 1) {
					$sql .= ', ';
					$sql .= 'page_place_sel = \''.$_POST['page_place_sel'] . '\'';
				}
    		}
    		
    		$sql .= ', ';
    		$sql .= 'post_import = '.( $_POST['post_import'] ? 1 : 0);
    		
    		if( $_POST['post_import']) {
    			$sql .= ', ';
				$sql .= 'post_slug = '.( $_POST['post_slug'] ? 1 : 0 ) . ', ';
				$sql .= 'post_status = \''.$_POST['post_status'] . '\', ';
				$sql .= 'post_users = '.$_POST['post_users'] . ', ';
				if( $_POST['post_users'] == 1) 
					$sql .= 'post_users_sel1 = \''.$_POST['post_users_sel'] . '\', ';
				if( $_POST['post_users'] == 2)
					$sql .= 'post_users_sel2 = \''.$_POST['post_users_sel'] . '\', ';
				$sql .= 'post_date = '.$_POST['post_date'] . ', ';
				if( $_POST['post_date'] == 2 && isset( $_POST['post_date_entry']))
					$sql .= 'post_date_entry = \''.$_POST['post_date_entry'] . '\', ';
				$sql .= 'post_place = '.$_POST['post_place'] . ', ';
				if( $_POST['post_place'] == 1)
					$sql .= 'post_place_sel = \''.$_POST['post_place_sel'] . '\', ';
	    		$sql .= 'post_uncat = '.( $_POST['post_uncat'] ? 1 : 0) . ', '; 
	    		if( $_POST['post_uncat'])
	    			$sql .= 'post_uncat_sel = \''. $_POST['post_uncat_sel'] . '\', ';
	    		$sql .= 'post_cat = '.$_POST['post_cat'];
    		}
    		$sql .= ' LIMIT 1;';
    		
    		self::_callDB( $sql);
	    	
	    	Flash::set( 'success', __( 'Settings updated.'));
	    	
			redirect( get_url( 'plugin/wpdb_import/settings'));
    	}
    	
    	/**
    	 * 
    	 * Enter description here ...
    	 */
    	public function getSettings() {
    		
    		// get settings
    		
    		$sql = 'SELECT * FROM '.TABLE_PREFIX.'wpdb_import LIMIT 1;';
    		
    		if( $result = self::_callDB( $sql, true)) return $result;
    		else return false;	
    	}
    
    	/**
    	 * 
    	 * Enter description here ...
    	 */
		public function upload() {
			
			$result = 'success';						// result status
			$message = 'File uploaded successfully.';	// result message
			
			$fileTmpName = '';							// the actual uploaded file name
			$filename = 'wordpress.xml';				// .xml name to force

			// check the HTML/PHP upload
			
			if( $result != 'error' && !isset( $_FILES['wpdb_file'])) {
				$result = 'error';
				$message = 'Upload failed: the server did not complete the request.';
			} 
			else {
				// get upload temp name
				
				$fileTmpName = $_FILES['wpdb_file']['tmp_name'];
			}
			
			// check upload for PHP success
			
			if( $result != 'error' && ( $_FILES['wpdb_file']['error'] != UPLOAD_ERR_OK)) {
				$result = 'error';
				
				// TOD0 change message according to error code
				$message = 'Upload failed: PHP upload error code '.$_FILES['wpdb_file']['error'].'.';
			}

			// check file extension
			
			if( $result != 'error' && substr( $_FILES['wpdb_file']['name'], -3) != "xml") {
				$result = 'error';
				$message = 'Uploaded file is not a valid XML file.';
			}
			
			// check file size
			
			if( $result != 'error' && ( $_FILES['wpdb_file']['size'] > MAX_FILE_SIZE * 1024 * 1024)) {
				$result = 'error';
				$message = sprintf( 'Uploaded file is too heavy, it must not be over %d MB.',  
					MAX_FILE_SIZE);
			}
			
			// force the name of the uploaded file
			
			if( $result != 'error' && !move_uploaded_file( $fileTmpName, $filename)) {
				$result = 'error';
				
				// TODO give a proper error message
				$message = 'File could not be renamed.  Permissions error?';
			}
			
			// finish and redirect
			
			Flash::set( $result, __( $message));
			
			redirect( get_url( 'plugin/wpdb_import/'));
		}

		/**
		 * 
		 * Enter description here ...
		 */
		public function import() {
			
			$result = 'success';				// result status
			$message = 'Import successful!';	// result message
			
			// get settings
			
			$results = self::getSettings();
			$settings = $results[0];

			// remove xml namespaces
			
			if ( $result != 'error' && !( $xml = self::_removeNameSpacesInXml())) {
				$result = 'error';
				
				// TODO give a proper error message
				$message = 'Prepping the XML file failed for some reason.';
			}
			
			// import categories
			
			if( $settings['cat_import']) {
				if( $result != 'error' && !self::_importCategories( $xml, $settings)) {
					$result = 'error';
					$message = 'Failed to import WP categories.';
				}
			}
			
			// import pages & posts
			
			if( $settings['page_import'] || $settings['post_import']) {
				if( result != 'error' && !self::_importContents( $xml, $settings)) {
					$result = 'error';
					$message = 'Failed to import WP pages and posts.';
				}
			}
			
			Flash::set( $result, __( $message));
			
			$result  == 'error' ?  redirect( get_url( 'plugin/wpdb_import/')) :
				redirect( get_url( 'page'));	
		}
		
		/**
		 * 
		 * Enter description here ...
		 */
		public function deleteWPFile() {
			
			$result = 'success';						// result status
			$message = 'The file has been deleted.';	// result message
			
			// unlink file
			
			if( !unlink( 'wordpress.xml')) {
				$result = 'error';
				$message = 'The file could not be deleted.';
			}
			
			Flash::set( $result, $message);
			
			redirect( get_url('plugin/wpdb_import'));
		}
	
//  Private methods  ---------------------------------------------------------------------------------------------------

		/**
		 *	Cleans the XML file by removing useless namespace.
		 * 	We don't need them and it clutters the source.
		 * 
		 *  @return SimpleXML
		 */
		private function _removeNameSpacesInXml(){
			
			// get file contents
			// TODO error check file_get_contents
			
			$feed = file_get_contents( "wordpress.xml");
			
			// remove namespaces
			
			$cleaned = str_replace( '<wp:'	, '<'	, $feed);
			$cleaned = str_replace( '</wp:'	, '</'	, $cleaned);
			$cleaned = str_replace( '<dc:'	, '<'	, $cleaned);
			$cleaned = str_replace( '</dc:'	, '</'	, $cleaned);
			
			// return a SimpleXML object
			
			return( simplexml_load_string( $cleaned));
		}
		
		/**
		 * 
		 * 
		 */
		private function _insertPage( $data) {
						
			$result = $data[0];		// return value
		
			// form PDOStatement with ? placeholders
			
			$sql = 'INSERT INTO '.TABLE_PREFIX.'page (id, title, slug, created_on, published_on, parent_id, layout_id, status_id, created_by_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
			
			// access Wolf CMS DB to insert page
			
			if( !self::_callDB( $sql, false, $data)) $result = false;
			
			// return result
			
			return $result;
		}

		/**
		 * 
		 * 
		 */
		private function _insertPart( $pageId, $content){
			
			$result = $pageId;		// return value

			// form PDOStatement with ? placeholders and data array
			
		  	$sql = 'INSERT INTO '.TABLE_PREFIX.'page_part (name, content, content_html, page_id) VALUES (?, ?, ?, ?)';
		  	
			$part_array = array( 'body', $content, $content, $pageId);
			
			// access Wolf CMS DB to insert page part
			
			if( !self::_callDB( $sql, false, $part_array)) $result = false;
			
			// return result
			
			return $result;
		}

		/**
		 * 
	 	 * Creates a new comment
	 	 */
		private function _insertComment( $data){
			
			$result = $data[0];		// return value

			// form PDOStatement with ? placeholders and data array
			
		  	$sql = "INSERT INTO ".TABLE_PREFIX."comment (page_id, author_name, author_email, author_link, body, ip, created_on, is_approved) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		  		
		  	// access Wolf CMS DB to insert comment
		  	
			if( !self::_callDB( $sql, false, $data)) $result = false;
			
			// return result
			
			return $result;
		}
		
		/**
		 * 
		 * TODO Custom error messages?
		 */
		private function _importCategories( $xml, $settings){
			
			$result 		= true;	// output
			$recordOffset 	= 1;	// offset from Record::lastInsertId()
			
			$id 			= '';
			$slug 			= '';
			$title 			= '';
			$userId 		= '';
			$statusId 		= '';
			$parentId 		= '';
			$datePublished 	= '';
			$layoutId 		= '';
			$content 		= '';
			
			/* GET CATEGORY DATA */
			
			// get upload user
				
			$userId = $settings['cat_user_sel'];
				
			// get upload status
    			
			switch( $settings['cat_status']) {
				
				case 'Draft' :
					$statusId = Page::STATUS_DRAFT;
					break;
					
				case 'Preview ':
					$statusId = Page::STATUS_PREVIEW;
					break;
					
				case 'Published' :
					$statusId = Page::STATUS_PUBLISHED;
					break;
					
				case 'Hidden' :
					$statusId = Page::STATUS_HIDDEN;
					break;
					
				case 'Archived' :
					$statusId = Page::STATUS_ARCHIVED;
					break;
			}
			
			// get content
				
			switch( $settings['cat_content']) {
				
				case '0' :
					// TODO make this work
					
					if( !( $content = file_get_contents( 'defaultCatContent.php'))) return false;
					break;
					
				case '1' :
					// TODO make this work
					
					if( !( $content = file_get_contents( '???'))) return false;	
					break;
					
				case '2' :
					$content = '';
					break;
			}
			
			// get parent id
				
			switch( $settings['cat_place']) {
				
				case '0' :
					$parentId = 1;
					break;
					
				case '1' :
					$parentId = self::_getCategoryByName( $settings['cat_place_sel']);
					break;
			}
			
			// get publish date
			// TODO check interoperability with other database types
			 
			switch( $settings['cat_date']) {
				
				case '0' :
					$datePublished = date( DB_TIME_FORMAT);
					break;
					
				case '1' :
					$datePublished = $settings['cat_date_entry'];
					break;	
			}

			// get layout id
			// TODO make this customizable with settings
			
			$layoutId = 0;	// inherit layout from parent
	
			foreach( $xml->channel->category as $category ) {
							
				$id		= $category->term_id;			// get WP:term_id for Wolf:id
				$slug 	= $category->category_nicename;	// get WP:nicename for Wolf:slug
				$title 	= $category->cat_name;			// get WP:name for Wolf:title
				
				// ignore 'Uncategorized' WP:Category
				// TODO make this behavior customizable
				
				if( $slug == 'uncategorized') continue;
				
				// check if slug exists and rename if needed
				
				$iteration = 0;
				
				if( $settings['cat_slug']) {
					if( self::_checkSlugExists( $slug) !== false) continue;
				}
				else {
					if( self::_checkSlugExists( $slug) !== false) {
						while( self::_checkSlugExists( $slug . $iteration) !== false) {
							$iteration++;
						}
						$slug = $slug . $iteration;
					}
				}
				
				// check if the id exists and rename if needed
				
				while( self::_checkPageIdExists( $id)) {	
					$id = Record::lastInsertId() + $recordOffset++;	
				}
								
				/* BUILD CATEGORY IMPORT */

				// set up data
				// TODO turn this into a string-indexed array
				
				$data = array(
					$id,
					$title, 
					$slug, 
					$datePublished, 
					$datePublished, 
					$parentId, 
					$layoutId, 
					$statusId, 
					$userId
				);
					
				/* INSERT CATEGORY */
				
				// insert category
				
				$categoryId = self::_insertPage( $data);
				
				// TODO just because one fails doesn't mean they all should fail
				
				if( !$categoryId) {
					$result = false;
					break;
				}
				
				// insert category content
				
				$partId = self::_insertPart( $categoryId, $content);
				
				// TODO just because one fails doesn't mean they all should fail
	
				if( !$partId) {
					$result = false;
					break;
				}
			}
			
			return $result;
		}
		         
		/**
		 * 
		 *
		 */
		private function _importContents( $xml, $settings) {
			
			$result = true;			// return value
			
			$recordOffset 	= 1;	// offset from Record::lastInsertId()
			
			$id 			= '';
			$slug 			= '';
			$title 			= '';
			$userId 		= '';
			$statusId 		= '';
			$parentId 		= '';
			$datePublished 	= '';
			$layoutId 		= '';
			$content 		= '';
			$category 		= '';
			$postType 		= '';
			$commentStatus	= '';
			
	
			$dateParse			= '';	// parsed date
			$currentYear 		= '';	// the current year being used
			$currentMonth 		= '';	// the current month being used
			$currentDate 		= '';	// the current day being used
			$currentYearCatId 	= '';	// the current categorization year id being used
			$currentMonthCatId 	= '';	// the current categorization month id being used
			$currentDateCatId	= '';	// the current categorization date id being used
			
			$category_array 	= '';	// array for placing a new categorization page

			foreach ( $xml->channel->item as $item){
				
				$id 			= $item->post_id;
				$slug 			= (string) $item->post_name;
				$title 			= (string) $item->title;
				$userId			= (string) $item->creator;
				$category 		= isset( $item->category) ? $item->category : null;
				$postType 		= (string) $item->post_type;
				$commentStatus 	= ( $item->comment_status != 'open') ? 0 : 1;
			
				// TODO make the layout editable
				
				$layoutId = 0;			// inherit
				
				// differentiate between pages and posts
				
				if( $postType)
				
				if( $postType == "page" && $settings['page_import']) {
					
					/* GET PAGE DATA */
				
					// get upload user
					
					$checkUser = self::_checkUserExists( $user);
					
					switch( $settings['page_user']) {
						
						case '0' :
							// TODO add custom user
							
							break;
							
						case '1' :
							if( !$checkUser) $user = $settings['page_user_sel1'];
							break;
							
						case '2' :
							$user = $settings['page_user_sel2'];
							break;		
					}
					
					// get upload status
	    			
					switch( $settings['page_status']) {
						
						case 'Draft' :
							$statusId = Page::STATUS_DRAFT;
							break;
							
						case 'Preview' :
							$statusId = Page::STATUS_PREVIEW;
							break;
							
						case 'Published' :
							$statusId = Page::STATUS_PUBLISHED;
							break;
							
						case 'Hidden' :
							$statusId = Page::STATUS_HIDDEN;
							break;
							
						case 'Archived' :
							$statusId = Page::STATUS_ARCHIVED;
							break;
					}
					
					// get publish date
					// TODO check interoperability with other database types
					
					switch( $settings['page_date']) {
						
						case '0' :
							$datePublished = date( DB_TIME_FORMAT);
							break;
							
						case '1' :
							$datePublished = $item->post_date;
							break;
							
						case '2' :
							$datePublished = $settings['page_date_entry'];
							break;	
					}

					// get parent id
					
					switch( $settings['page_place']) {
						
						case '0' :
							$parentId = 1;
							break;
							
						case '1' :
							$parentId = self::_getCategoryByName( $settings['page_place_sel']);
							break;
					}
					
					// check if slug exists and rename if needed
					
					$iteration = 0;
					
					if( $settings['page_slug'])
						if( self::_checkSlugExists($slug) !== false) continue;
					else {
						if( self::_checkSlugExists( $slug) !== false) {
							while( self::_checkSlugExists( $slug . $iteration) !== false) {
								$iteration++;
							}
							$slug = $slug . $iteration;
						}
					}
				} elseif( $postType == "post" && $settings['post_import']) {
					
					if( $title == 'Sample Page') {
						exit( 'yep');
					}
					
					/* GET POST DATA */
				
					// get upload user
					
					$checkUser = self::_checkUserExists( $user);
					
					switch( $settings['post_user']) {
						
						case '0' :
							// TODO make a new user
							
							break;
							
						case '1' :
							if( !$checkUser) $user = $settings['post_user_sel1'];
							break;
							
						case '2' :
							$user = $settings['post_user_sel2'];
							break;
					}
					
					// get upload status
	    			
					switch( $settings['post_status']) {
						
						case 'Draft' :
							$statusId = Page::STATUS_DRAFT;
							break;
							
						case 'Preview' :
							$statusId = Page::STATUS_PREVIEW;
							break;
							
						case 'Published' :
							$statusId = Page::STATUS_PUBLISHED;
							break;
							
						case 'Hidden' :
							$statusId = Page::STATUS_HIDDEN;
							break;
							
						case 'Archived' :
							$statusId = Page::STATUS_ARCHIVED;
							break;
					}
				
					// get publish date
					// TODO check interoperability with other database types
					
					switch( $settings['post_date']) {
						
						case '0' :
							$datePublished = date( DB_TIME_FORMAT);
							break;
							
						case '1' :
							$datePublished = $item->post_date;
							break;
							
						case '2' :
							$datePublished = $settings['post_date_entry'];
							break;	
					}

					// get parent id
					
					switch( $settings['post_place']) {
						
						case '0' :
							$parentId = 1;
							break;
							
						case '1' :
							if( $category != null && $category != 'Uncategorized')
								$parentId = self::_getCategoryByName( $category);
							else {
								if( $settings['post_uncat'] )
									$parentId = self::_getCategoryByName( $settings['post_uncat_sel']);
								else $parentId = 1;
							}
							break;
							
						case '2' :
							$parentId = self::_getCategoryByName( $settings['post_place_sel']);
							break;
					}
					
					// check categorization options
					
					// the parent Id is currently placed, what needs to happen is
					// 1 the date needs to be checked
					// 2 it needs to be checked whether or not the date/month/year page is already placed
					// (which can be done via code instead of checking for it each time)
					// 3 if it doesn't exist, make it underneath the current parent id
					// 4 if it does exist, change parentID to that page
					
					// parse the date
					
					$dateParse = date_parse( $datePublished);
					
					switch( $settings['post_cat']) {
						
						case '0' :
							break;
							
						case '1' :
							
							$year = $dateParse['year'];
							if( $currentYear != $year) {
								
								// get a new id
								
								// TODO check that we're not trying to duplicate a page (checkSlug)
								
								$newId 			= Record::lastInsertId() + 1;
								$newIdOffset 	= 0;
								
								// TODO get check page id working and make it a seperate function
								
								while( self::_checkPageIdExists( $newId)) {
									$newId += $newIdOffset++;
								}

								// place new year page
								
								$category_array = array(
									$newId,
									$year, 
									$year, 
									$datePublished, 
									$datePublished, 
									$parentId, 
									$layoutId, 
									$statusId, 
									$userId
								);
								
								$currentYear = $year;
								
								// TODO check insert Page success
					
								$currentYearCatId = self::_insertPage( $category_array);
							}
							
							// change the parentId to the current categorization page
							
							$parentId = $currentYearCatId;
							
							break;
						
						case '2' :
							
							$year 	= $dateParse['year'];
							$month 	= $dateParse['month'];
							
							// TODO check that we're not trying to duplicate a page (checkSlug)
								
							if( $currentYear != $year) {
								
								// need to change year and add a new year page
								
								// get a new id
								
								$newId 			= Record::lastInsertId() + 1;
								$newIdOffset 	= 0;
								
								while( self::_checkPageIdExists( $newId)) {
									$newId += $newIdOffset++;
								}
								
								// place a new year page
								
								$category_array = array(
									$newId,
									$year, 
									$year, 
									$datePublished, 
									$datePublished, 
									$parentId, 
									$layoutId, 
									$statusId, 
									$userId
								);
								
								$currentYear = $year;
								
								// TODO check insert Page success
					
								$currentYearCatId = self::_insertPage( $category_array);
							}
								
							if( $currentMonth != $month) {
								
								// need to change month and add a new month page
								
								// get a new id

								$newId 			= Record::lastInsertId() + 1;
								$newIdOffset 	= 0;
								
								while( self::_checkPageIdExists( $newId)) {
									$newId += $newIdOffset++;
								}
								
								// place a new month page
								
								$category_array = array(
									$newId,
									$month . '-' . $year, 
									$month . '-' . $year, 
									$datePublished, 
									$datePublished, 
									$currentYearCatId, 
									$layoutId, 
									$statusId, 
									$userId
								);
								
								$currentMonth = $month;
								
								$currentMonthCatId = self::_insertPage( $category_array);
								
							}
							
							// change the parentId to the current categorization page
							
							$parentId = $currentMonthCatId;
							
							break;
							
						case '3' :
							
							$year 	= $dateParse['year'];
							$month 	= $dateParse['month'];
							$date	= $dateParse['day'];
							
							// TODO check that we're not trying to duplicate a page (checkSlug)
								
							if( $currentYear != $year) {
								
								// need to change year and add a new year page
								
								// get a new id
								
								$newId 			= Record::lastInsertId() + 1;
								$newIdOffset 	= 0;
								
								while( self::_checkPageIdExists( $newId)) {
									$newId += $newIdOffset++;
								}
								
								// place a new year page
								
								$category_array = array(
									$newId,
									$year, 
									$year, 
									$datePublished, 
									$datePublished, 
									$parentId, 
									$layoutId, 
									$statusId, 
									$userId
								);
								
								$currentYear = $year;
								
								// TODO check insert Page success
					
								$currentYearCatId = self::_insertPage( $category_array);
							}
								
							if( $currentMonth != $month) {
								
								// need to change month and add a new month page
								
								// get a new id

								$newId 			= Record::lastInsertId() + 1;
								$newIdOffset 	= 0;
								
								while( self::_checkPageIdExists( $newId)) {
									$newId += $newIdOffset++;
								}
								
								// place a new month page
								
								$category_array = array(
									$newId,
									$month, 
									$year . '-' . $month, 
									$datePublished, 
									$datePublished, 
									$currentYearCatId, 
									$layoutId, 
									$statusId, 
									$userId
								);
								
								$currentMonth = $month;
								
								$currentMonthCatId = self::_insertPage( $category_array);
								
							}
							
							if( $currentDate != $date) {
								
								// need to change date and add a new date page
								
								// get a new id

								$newId 			= Record::lastInsertId() + 1;
								$newIdOffset 	= 0;
								
								while( self::_checkPageIdExists( $newId)) {
									$newId += $newIdOffset++;
								}
								
								// place a new date page
								
								$category_array = array(
									$newId,
									$month, 
									$year . '-' . $month . '-' . $date, 
									$datePublished, 
									$datePublished, 
									$currentMonthCatId, 
									$layoutId, 
									$statusId, 
									$userId
								);
								
								$currentDate = $date;
								
								$currentDateCatId = self::_insertPage( $category_array);
								
							}
							
							// change the parentId to the current categorization page
							
							$parentId = $currentDateCatId;
							
							break;					
					}
					
					// check if slug exists and rename if needed
					
					$iteration = 0;
					
					if( !$settings['post_slug'])
						if( self::_checkSlugExists( $slug) !== false) continue;
					else {	
						if( self::_checkSlugExists( $slug) !== false) {
							while( self::_checkSlugExists( $slug . $iteration) !== false) {
								$iteration++;
							}
							$slug = $slug . $iteration;
						}
					}
				} else continue;
				
				// check if the id exists and rename if needed
				
				while( self::_checkPageIdExists( $id)) {
					$id = Record::lastInsertId() + $recordOffset++;	
				}
				
				// get content
				
				$content = $item->children( 'content', TRUE);
				
				/* BUILD PAGE/POST IMPORT */

				// set up data
				// TODO turn this into a string-indexed array
				
				$page_array = array(
					$id,
					$title, 
					$slug, 
					$datePublished, 
					$datePublished, 
					$parentId, 
					$layoutId, 
					$statusId, 
					$userId
				);
					
				/* INSERT PAGE/POST */
	
				// insert page
				
				// TODO just because one fails doesn't mean they all should fail
				
				$pageId = self::_insertPage( $page_array);
				
				if( !$pageId) {
					$result = false;
					break;
				}

				// insert page content
				
				$partId = self::_insertPart( $pageId, $content);
	
				if( !$partId) {
					$result = false;
					break;
				}

				// TODO insert comments
			}
			
			return $result;
		}
		
		/**
		 * Imports comments of $pageId post
		 */
		private function _importComments( $pageId, $xml) {
			
			$author		= '';
			$mail		= '';
			$url		= '';
			$body		= '';
			$ip			= '';
			$date		= '';
			$approved 	= '';
			
			foreach($xml as $comment) {
				
				$author		= (string) $comment->comment_author;
				$mail 		= (string) $comment->comment_author_email;
				$url 		= (string) $comment->comment_author_url;
				$body 		= (string) $comment->comment_content;
				$ip 		= (string) $comment->comment_author_IP;
				$date 		= (string) $comment->comment_date;
				$approved 	= (string) $comment->comment_approved;				
				
				$data = array(
					$pageId, 
					$author, 
					$mail, 
					$url, 
					$body, 
					$ip, 
					$date, 
					$approved
				);
					
				self::_insertComment( $data);
			}
		}

		/**
		 * 
		 * Import users.
		 * 
		 * TODO 
		 */
		private function _importUsers( $xml, $defaultPassword) {
			
			$id			= '';
			$username	= '';
			$email		= '';
			$fullname	= '';
			
			foreach( $xml->author as $author ) {
				
				// get user information
				
				$id 		= (string) $author->author_id;
				$username	= (string) $author->author_login;
				$email		= (string) $author->author_email;
				$fullname	= (string) ( $author->author_first_name . ' ' . $author->author_last_name);
				
				// make user 

			}
		}
		
		/**
		 * 
		 * 
		 */
		private function _renameSlug( $slug) {
			
		}
		
		/**
		 * 
		 * 
		 */
		private function _checkUserExists( $username){
			
			$userId = false;	// return id
			
			$filter = array(
				'where' => User::tableNameFromClassName('User').'.username="'.(string)$username.'"',
				'limit' => 1
			);
			
			$user = User::find( $filter);
			
			// TODO check for a more efficient way to do this
			
			if( $user != null) $userId = $user->id;
				
			return $userId;
		}
		
		/**
		 * 
		 */
		private function _makeUser() {
			
			$name 		= '';
			$password 	= '';
			$email 		= '';
			$language 	= '';
								
			$userData = '';
			$user = new User( $userData);
								
			// generate a salt and create encrypted password
			
        	$user->salt = AuthUser::generateSalt();
        	$user->password = AuthUser::generateHashedPassword($user->password, $user->salt);
        						
        	if ($user->save()) {
						        
        	}
		}

		/**
		 * Checks if $slug is a valid page in WolfCMS
		 * 
		 */
		private function _checkSlugExists( $slug) {
		
			$filter = array(
				'where' => 'slug = "' . $slug . '"',
				'limit' => 1
			);
			
			return Page::find( $filter);
		}
		
		/**
		 * 
		 */
		private function _checkPageIdExists( $id) {
			
			return Page::findById( $id);
		}
		
		/** 
		 * 
		 */
		private function _getCategoryByName( $category){
			
			$result = false;
			
			$filter = array(
				'where' => 'slug = "' . $category . '"',
				'limit' => 1
			);
			
			$page = Page::find($filter);
			
			// TODO determine if this is best
			if( isset( $page->id)) $result = $page->id;
			
			return $result;
		}
		
		/**
		 * 
		 */
		private function _callDB( $sql, $fetch = false, $data = null) {
			
			// TODO should we use $pdo->setAttribute
			// $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
			
			if( !( $pdo = Record::getConnection())) {
				
				$error = $stm->errorInfo(); 
				print_r( $error[2]);
				$errorInfo = ob_get_clean();
				
				exit( "\nPDO::errorInfo(): $errorInfo\n"); //temp
				return false;
			}  
			
			// TODO error checking?
			if( !( $stm = $pdo->prepare( $sql))) {
				
				$error = $stm->errorInfo(); 
				print_r( $error[2]);
				$errorInfo = ob_get_clean();
				
				exit( "\nPDO::errorInfo(): $errorInfo\n"); //temp
				return false;
			} 
						
			// TODO error checking?
			if( !( $stm->execute( $data)))  {
			
				$error = $stm->errorInfo();
				print_r( $error[2]);
				print_r( $data);
				$errorInfo = ob_get_clean();
				
				exit( "\nPDO::errorInfo(): $errorInfo\n"); //temp
				return false;
			}
			
			return $fetch ? $stm->fetchAll() : true;
		}
}