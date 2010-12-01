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

		private $categories = array();

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
			$maxFileSize = 100000;
			$result = "error";

			$fileTmpName = $_FILES['wpdb_file']['tmp_name'];
			$fileSize = filesize($fileTmpName);
			$extension = substr($_FILES['wpdb_file']['name'], -3); 

			if($extension != "xml") {
				$message = "Uploaded file is not valid xml.";
			}
			if($fileSize > $maxFileSize) {
				$message = "Uploaded file is too heavy, it must not be over $maxFileSize.";
			}
			if(!isset($message)) {
				// Setting the name of the uploaded file
				$filename = "wordpress.xml";
				if(move_uploaded_file($fileTmpName, $filename)) {
					$result = "success";
				  $message = "File uploaded successfully!";
				} else {
					$message = "An error occurs during file upload.";
				}
			}	
			Flash::set($result, $message);
			redirect(get_url('plugin/wpdb_import/'));
		}

		
		public function import(){
			// Get current User if user don't exist in import file
			$userId = AuthUser::getRecord()->id;

      $xml = self::_removeNameSpacesInXml();

			self::_importCategory($xml, $userId);
			self::_importContent($xml, $userId);

			Flash::set('success', 'Import successful !');
			redirect(get_url('page'));
			
		}
	
//  Private methods  -----------------------------------------------------

		/**
		 *	This methods "cleans" the XML file by removing useless namespace.
		 * 	We don't need them and it clutters the source.
		 */
		private function _removeNameSpacesInXml(){
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
		
			$sql = "INSERT INTO ".TABLE_PREFIX."page (title, slug, created_on, published_on, parent_id, layout_id, status_id, created_by_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
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

	/**
	 * This methods create a new part for $pageId
	 */
		private function _insertComment($data){
			error_reporting(E_ALL);

		  $sql = "INSERT INTO ".TABLE_PREFIX."comment (page_id, author_name, author_email, author_link, body, ip, created_on, is_approved) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
			$pdo = Record::getConnection();
			$stm = $pdo->prepare($sql); 
			$stm->execute($data);
		}


		private function _importCategory($xml, $userId){
			$tmp = array();
			foreach($xml->channel->category as $category){
				$title = $category->cat_name;
				$slug = $category->category_nicename;
				$commentStatus = false; // Can't write comments on pages
				$datePublished = $category->post_date;				
				$parentId = 1; // As a category, it's a child of Homepage which ID is 1;
				$layoutId = 0; // Inherit layout from parent
				$statusId = 101; // We hide catgories
				$data = array($title, $slug, $datePublished, $datePublished, $parentId, $layoutId, $statusId, $userId);
				

				// TODO Test if a category already exists
				$categoryId = self::_insertPage($data);
				// Storing category ID in array
				$tmp[(string)$title] = $categoryId;

				// TODO insert default content that will display posts
				// self::_insertPart($pageId, categoryContent);

			}
			$this->categories = $tmp;
			print_r($this->categories);
		}				
		         
		private function _importContent($xml, $userId){	
			foreach ($xml->channel->item as $item){
				$postType = $item->post_type;
				$status = $item->status;
				// We import only published posts
				if($status == "publish"){
					$userId = self::_checkUserExist($item->creator);
					if($userId == -1)
						continue;

					$title = $item->title;
					$slug = $item->post_name;
					$status = $item->status;
					$datePublished = $item->post_date;				
					$commentStatus = ($item->comment_status != "open") ? 0 : 1;
					if($postType == "post")
						$parentId = $this->categories[(string)$item->category];
					elseif($postType == "page"){
						$parentId = 1;
					}
					$layoutId = 0; // Inherit
					
					// TODO : Handle status, now we import only published content
					$statusId = 100;					
					$page_array = array($title, $slug, $datePublished, $datePublished, $parentId, $layoutId, $statusId, $userId);
					$pageId = self::_insertPage($page_array);

					$content = $item->children('content', TRUE);
					self::_insertPart($pageId, $content);

					self::_importComment($pageId, $item->comment);
				}
			}
		}
		
		private function _importComment($pageId, $xml){

			foreach($xml as $comment){
				$author =  (string) $comment->comment_author;
				$mail = (string) $comment->comment_author_mail;
				$url =  (string) $comment->comment_author_url;
				$body = (string) $comment->comment_content;
				$ip = (string) $comment->comment_author_IP;
				$date = (string) $comment->comment_date;
				$approved = (string) $comment->comment_approved;				
				
				$data = array($pageId, $author, $mail, $url, $body, $ip, $date, $approved);
				self::_insertComment($data);
			}
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
