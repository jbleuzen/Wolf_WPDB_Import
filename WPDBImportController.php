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
 
class WPDBImportController extends PluginController {

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

		public function upload(){
		
		}

		
		public function import(){
			// Get current User if user don't exist in import file
			$userId = AuthUser::getRecord()->id;
      
      $xml = self::_prepareXmlFile();
/*			if(isset($_POST['importCategory']) && $_POST['importCategory'] == true){
				self::importCategory($xml, $userId);
			}*/
			if((isset($_POST['importPage']) && $_POST['importPage']) || (isset($_POST['importPost']) && $_POST['importPost'])){
				self::_importContent($xml, $userId);
			}
/*			if(isset($_POST['importComment']) && $_POST['importComment'] == true){
				self::_importComment($xml, $userId);
			}*/
			
		}
	
	  //  Private methods  -----------------------------------------------------
		/**
		 *	This methods "cleans" the XML file by removing useless namespace.
		 * 	We don't need them and the clutter the source.
		 */
		private function _prepareXmlFile(){
			$feed = file_get_contents("wordpress.xml");
			$cleaned = str_replace('<wp:', '<', $feed);
			$cleaned = str_replace('</wp:', '</', $cleaned);
			$cleaned = str_replace('<dc:', '<', $cleaned);
			$cleaned = str_replace('</dc:', '</', $cleaned);
			$xml = new SimpleXmlElement($cleaned);
			return $xml;
		}
		
	/**
	 * This methods create a new page in DB
	 */
		private function _insertPage($data) {
			error_reporting(E_ALL);
		
			$sql = "INSERT INTO ".TABLE_PREFIX."page (title, slug, created_on, published_on, parent_id, layout_id, created_by_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
			$pdo = Record::getConnection();
			$stm = $pdo->prepare($sql); 
			$stm->execute($data);
		  return Record::lastInsertId();
		}

	/**
	 * This methods create a new part for $pageId
	 */
		private function _insertPart($pageId, $content){
			error_reporting(E_ALL);

		  $sql = "INSERT INTO ".TABLE_PREFIX."page_part (name, content, content_html, page_id) VALUES (?, ?, ?, ?)";
			$pdo = Record::getConnection();
			$stm = $pdo->prepare($sql); 
			$part_array = array('body',$content, $content, $pageId);
			$stm->execute($part_array);
		}

		private function _importCategory(){
			foreach($xml->channel->category as $category){
				$title = $category->cat_name;
				$slug = $category->category_nicename;
				$commentStatus = false; // Can't write comments on pages
				$date = date('y-m-j h-i-s');
				$parentId = 4; // As a category, it's a child of Homepage which ID is 1;
				$layoutId = 0; // Inherit layout from parent
				$data = array($title, $slug, $date, $date, $parentId, $layoutId, $userId);
				
				self::_insertPage($data);
			}		
		}				
		         
		private function _importContent($xml, $userId){	
			// Creating a default page where we'll import posts with HomePage as parent
			$data = array("WP Import", "wp-import", date('y-m-j h-i-s'), date('y-m-j h-i-s'), 1, 0, $userId);
			$importParentId = self::_insertPage($data, $userId);

			foreach ($xml->channel->item as $item){
				$postType = $item->post_type;
				$status = $item->status;
				// We import only published posts
				if($status == "publish"){
					if($postType == "post")
						$parentId = $importParentId;
					elseif($postType == "page"){
						$parentId = 1;
					}

					$title = $item->title;
					$slug = $item->post_name;
					$status = $item->status;
					$datePublished = $item->pubDate;				
					$commentStatus = ($item->comment_status != "open") ? 0 : 1;
					$userId = self::_checkUserExist($item->creator);
					$page_array = array($title, $slug, $datePublished, $datePublished, $parentId, 0,$userId);
					$pageId = self::_insertPage($page_array);

					$content = $item->children('content', TRUE);
					self::_insertPart($pageId, $content);
				}
			}
			echo "done !";
		}
		
		private function _importComment(){
			echo "import comments";
		}   

		public function _checkUserExist($username){
			$userId = -1;
			$data = array(
				'where' => User::tableNameFromClassName('User').'.name="'.(string)$username.'"',
				'limit' => 1
			);
			$user = User::find($data);
			if(count($user) != 0)
				$userId = $user->id;
			return $userId;
		}
		
}
