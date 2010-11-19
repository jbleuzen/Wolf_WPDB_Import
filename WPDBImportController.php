<?php

/*
 * Funky Cache - Frog CMS caching plugin
 *
 * Copyright (c) 2008-2009 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.appelsiini.net/projects/funky_cache
 *
 */
 
class WPDBImportController extends PluginController
{

    private static function _checkPermission() {
        AuthUser::load();
        if ( ! AuthUser::isLoggedIn()) {
            redirect(get_url('login'));
        }
    }

    function __construct() {


        self::_checkPermission();
        
        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('../../plugins/wpdb_import/views/sidebar'));

    }

    function index() {
        $this->display('wpdb_import/views/index', array());
    }
    
    function documentation() {
        $this->display('wpdb_import/views/documentation', array());
    }
        
    
    function settings() {
        $this->display('wpdb_import/views/settings', array());
    }

    function import(){
	    if ( ! isset($_POST['userid'])) {
				Flash::set('error', "You need to select a user to import data");
				redirect(get_url('plugin/wpdb_import'));
      }else{
	      $userId = $_POST['userid'];
      }
      
      $parentId = 4;
      $layoutId = 0;

	    $xml = simplexml_load_file("/Users/johan/Sites/test/wordpress.xml");
	    $items = $xml->channel->item;
			foreach ($items as $item){
				$item_wps = $item->children('wp', TRUE);

				// We import only published posts
				if($item_wps[9] == "post" && $item_wps[6] == "publish"){
					//Post Type
					$postType = $item_wps[9];
					//Title
					$title = $item->title;
					//Slug
					$slug = $item_wps[5];
					//Date published
					$date_published = $item_wps[1];
					//Status
					$status = $item_wps[6];
					//CommentStatus
					$commentStatus = ($item_wps[3] != "open") ? 0 : 1;
					// content
					$content = $item->children('content', TRUE);

					//echo "Post Type : " . $postType ."<br/>";
					//echo "Title : " . $title ."<br/>";
					//echo "Slug : " . $slug."<br/>";
					//echo "Date published : " . $date_published."<br/>";
					//echo "Status : " . $status ."<br/>";
					//echo "Content : " . $content[0]."<br/>";
					//comment status
					//echo "Comments are : $commentStatus <br/>";
					$page_array = array((string)$title, (string)$slug, (string)$date_published, (string)$date_published, $parentId, $layoutId,$userId);
					self::storePage($page_array, (string)$content);
				}
			}
			echo "</ul>";
 	    Flash::set('success', "Import done !");
 	    redirect(get_url('plugin/wpdb_import'));
    	return false;
    }
    
    function storePage($my_array, $content) {
   		error_reporting(E_ALL);
    
    	//Insert
    	// INSERT INTO w_page (title, slug, comment_status, created_on, published_on, created_by_id) 
    	// VALUES("Insert manouel", "insert-manouelle", 1,"2010-11-17 10:39:55", "2010-11-17 10:39:55", 1);
      $sql = "INSERT INTO ".TABLE_PREFIX."page (title, slug, created_on, published_on, parent_id, layout_id, created_by_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
      
      $pdo = Record::getConnection();
    	$stm = $pdo->prepare($sql); 
      $stm->execute($my_array);
      
      $sql = "INSERT INTO ".TABLE_PREFIX."page_part (name, content, content_html, page_id) VALUES (?, ?, ?, ?)";
			$stm = $pdo->prepare($sql); 
			$part_array = array('body',$content, $content, Record::lastInsertId());
			$stm->execute($part_array);
      
    }
    
}
