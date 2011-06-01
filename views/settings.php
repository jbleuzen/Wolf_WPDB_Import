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

// generate statuses options list

$statuses = array( 'Draft', 'Preview', 'Published', 'Hidden', 'Archived');

// generate top pages options list

$children = Page::childrenOf( 1);

// generate authors options

$authors = User::findAll();

// check import settings for automatic call to disabling function

?>

<h1>WPDB Import Settings</h1>
<?php echo( $page_users_sel1); ?>

<div id="button"><a href="#" id="testbutton">Test button</a></div>

	<form id="import_settings" name="import_settings" action="<?php echo get_url('plugin/wpdb_import/setSettings'); ?>" method="post" enctype="multipart/form-data">
		<fieldset style="padding: 8px" id="cat">
			<legend>
				<input type="checkbox" name="cat_import" id="cat_import" <?php if( $cat_import): ?>checked="checked"<?php endif; ?> onchange="check_cat( this.id); cat_import_toggle( this.id);" value="true" /> 
					<label for="cat_import">Import Categories</label>
			</legend>
				
			<h4>General</h4>	
				
			<input type="checkbox" name="cat_slug" id="cat_slug" <?php if( $cat_slug): ?>checked="checked"<?php endif; ?> value="true"/> 
				<label for="cat_slug">Skip slug conflicts</label><br /><br />
				
			<label for="cat_status">Import as status: </label>
			<select name="cat_status" id="cat_status">
				<?php 
					foreach( $statuses as $status) {
						echo( $cat_status == $status ? '<option selected' : '<option');
						echo( ' value="'.$status.'">'. $status . '</option>');
					}				 
				?>				
			</select><br />
			
			<h4>Content</h4>
			
			<input type="radio" class="disabled" name="cat_content" id="cat_content0" <?php if( $cat_content == '0'): ?>checked="checked"<?php endif; ?> value="0" onchange="element_toggle( 'cat_content1', 'cat_content_inc');" disabled="disabled" /> 
				<label for="cat_content0">Include default page listing</label><br />
				
			<input type="radio" class="disabled" name="cat_content" id="cat_content1" <?php if( $cat_content == '1'): ?>checked="checked"<?php endif; ?> value="1" onchange="element_toggle( 'cat_content1', 'cat_content_inc');" disabled="disabled" /> 
				<label for="cat_content1">Include the following content: </label>
			<input type="hidden" name="MAX_FILE_SIZE" value="2500000000000000" />
			<input type="file" name="cat_content_inc" id="cat_content_inc" <?php if( $cat_content != '1'): ?>disabled="disabled"<?php endif; ?> ><br />
			<!-- TODO add file name if already uploaded -->
			
			<input type="radio" name="cat_content" id="cat_content2" <?php if( $cat_content == '2'): ?>checked="checked"<?php endif; ?> value="2" onchange="element_toggle( 'cat_content1', 'cat_content_inc');" /> 
				<label for="cat_content2">Leave pages blank</label><br />
				
			<h4>User</h4>
			
			<label for="cat_user_sel">Import as user</label>
			<select name="cat_user_sel" id="cat_user_sel">
				<?php 
					foreach( $authors as $author) {
						echo( $cat_user_sel == $author->name ? '<option selected' : '<option');
						echo( ' value="'.$author->name.'">'. $author->name . '</option>');
					}				 
				?>
			</select><br />
			
			<h4>Time-stamp</h4>
				 
			<input type="radio" name="cat_date" id="cat_date0" <?php if( $cat_date == '0'): ?>checked="checked"<?php endif; ?> value="0" onchange="element_toggle( 'cat_date1', 'cat_date_entry');" />
				<label for="cat_date0">Now</label>
			<input type="radio" name="cat_date" id="cat_date1" <?php if( $cat_date == '1'): ?>checked="checked"<?php endif; ?> value="1" onchange="element_toggle( 'cat_date1', 'cat_date_entry');" />
				<label for="cat_date1">Custom date:</label>
				<input type="text" name="cat_date_entry" id="cat_date_entry" value="<?php echo ( $cat_date_entry); ?>" <?php if( $cat_date != '1'): ?>disabled="disabled"<?php endif; ?> /><br />
			
			<h4>Placement</h4>
			
			<input type="radio" name="cat_place" id="cat_place0" <?php if( $cat_place == '0'): ?>checked="checked"<?php endif; ?> value="0" onchange="element_toggle( 'cat_place1', 'cat_place_sel');" />
				<label for="cat_place0">Wolf Home page</label><br />
			<input type="radio" name="cat_place" id="cat_place1" <?php if( $cat_place == '1'): ?>checked="checked"<?php endif; ?> value="1" onchange="element_toggle( 'cat_place1', 'cat_place_sel');" />
				<label for="cat_place1">Custom parent page</label>
				<select name="cat_place_sel" id="cat_place_sel" <?php if( $cat_place != '1'): ?>disabled="disabled"<?php endif; ?>>
					<?php 
						foreach( $children as $child) {
							echo( $cat_place_sel == $child->title ? '<option selected' : '<option');
							echo( ' value="'.$child->title.'">'. $child->title . '</option>');
						}
					?>
				</select>
			
		</fieldset>
	
		<fieldset style="padding: 8px" id="page">
			<legend>
				<input type="checkbox" name="page_import" id="page_import" <?php if( $page_import): ?>checked="checked"<?php endif; ?> onchange="check_cat( this.id)" value="true" /> 
				<label for="page_import">Import Pages</label><br />
			</legend>
				
			<h4>General</h4>
				
			<input type="checkbox" name="page_slug" id="page_slug" <?php if( $page_slug): ?>checked="checked"<?php endif; ?> value="true" /> 
				<label for="page_slug">Skip slug conflicts</label><br /><br />
				
			<label for="page_status">Import as status</label>
			<select name="page_status" id="page_status">
				<?php 
					foreach( $statuses as $status) {
						echo( $page_status == $status ? '<option selected' : '<option');
						echo( ' value="'.$status.'">'. $status . '</option>');
					}				 
				?>
			</select><br />
			
			<h4>Users</h4>
			
			<input type="radio" class="disabled" name="page_users" id="page_users0" <?php if( $page_users == '0'): ?>checked="checked"<?php endif; ?> value="0" onchange="element_toggle( 'page_users1', 'page_users_sel1'); element_toggle( 'page_users2', 'page_users_sel2');" disabled="disabled" /> 
				<label for="page_users1">Create New Users</label><br />
				
			<input type="radio" class="disabled" name="page_users" id="page_users1" <?php if( $page_users == '1'): ?>checked="checked"<?php endif; ?> value="1" onchange="element_toggle( 'page_users1', 'page_users_sel1'); element_toggle( 'page_users2', 'page_users_sel2');" disabled="disabled" /> 
				<label for="page_users1">Associate New Users as</label>
				<select name="page_users_sel" id="page_users_sel1" <?php if( $page_users != '1'): ?>disabled="disabled"<?php endif; ?>>
					<?php 
					foreach( $authors as $author) {
						echo( $page_users_sel1 == $author->name ? '<option selected' : '<option');
						echo( ' value="'.$author->name.'">'. $author->name . '</option>');
					}				 
					?>
				</select><br />
				
			<input type="radio" name="page_users" id="page_users2" <?php if( $page_users == '2'): ?>checked="checked"<?php endif; ?> value="2" onchange="element_toggle( 'page_users1', 'page_users_sel1'); element_toggle( 'page_users2', 'page_users_sel2');" />
				<label for="page_users2">Associate all users as</label>
				<select name="page_users_sel" id="page_users_sel2" <?php if( $page_users != '2'): ?>disabled="disabled"<?php endif; ?>>
					<?php 
					foreach( $authors as $author) {
						echo( $page_users_sel2 == $author->name ? '<option selected' : '<option');
						echo( ' value="'.$author->name.'">'. $author->name . '</option>');
					}				 
					?>
				</select><br />
				
			<h4>Time-stamp</h4>
				
			<input type="radio" name="page_date" id="page_date0" <?php if( $page_date == '0'): ?>checked="checked"<?php endif; ?> value="0" onchange="element_toggle( 'page_date2', 'page_date_entry');" />
				<label for="page_date0">Now</label>
			<input type="radio" name="page_date" id="page_date1" <?php if( $page_date == '1'): ?>checked="checked"<?php endif; ?> value="1" onchange="element_toggle( 'page_date2', 'page_date_entry');" />
				<label for="page_date1">WP Date</label>
			<input type="radio" name="page_date" id="page_date2" <?php if( $page_date == '2'): ?>checked="checked"<?php endif; ?> value="2" onchange="element_toggle( 'page_date2', 'page_date_entry');" />
				<label for="page_date2">Custom date:</label>
				<input type="text" name="page_date_entry" id="page_date_entry" value="<?php echo( $page_date_entry); ?>" <?php if( $page_date != '2'): ?>disabled="disabled"<?php endif; ?>/><br />
			
			<h4>Placement</h4>
			
			<input type="radio" name="page_place" id="page_place0" <?php if( $page_place == '0'): ?>checked="checked"<?php endif; ?> value="0" onchange="element_toggle( 'page_place1', 'page_place_sel');" />
				<label for="page_place0">Wolf Home page</label><br />
			<input type="radio" name="page_place" id="page_place1" <?php if( $page_place == '1'): ?>checked="checked"<?php endif; ?> value="1" onchange="element_toggle( 'page_place1', 'page_place_sel');" />
				<label for="page_place1">Custom parent page</label>
				<select name="page_place_sel" id="page_place_sel" <?php if( $page_place != '1'): ?>disabled="disabled"<?php endif; ?> class="required">
					<?php 
						foreach( $children as $child) {
							echo( $page_place_sel == $child->title ? '<option selected' : '<option');
							echo( ' value="'.$child->title.'">'. $child->title . '</option>');
						}
					?>
				</select>
			
		</fieldset>
			
		<fieldset style="padding: 8px" id="post">
			<legend>
				<input type="checkbox" name="post_import" id="post_import" <?php if( $post_import): ?>checked="checked"<?php endif; ?> onchange="check_cat( this.id)" value="true" /> 
				<label for="post_import">Import Posts</label><br />
			</legend>
			
			<h4>General</h4>
			
			<input type="checkbox" name="post_slug" id="post_slug" <?php if( $post_slug): ?>checked="checked"<?php endif; ?> value="true" /> 
				<label for="post_slug">Skip slug conflicts</label><br /><br />
				
			<label for="post_status">Import as status</label>
			<select id="post_status" name="post_status">
				<?php 
					foreach( $statuses as $status) {
						echo( $post_status == $status ? '<option selected>' : '<option>');
						echo( $status . '</option>');
					}				 
				?>
			</select><br />
			
			<h4>Users</h4>
			
			<input type="radio" class="disabled" name="post_users" id="post_users0" <?php if( $post_users == '0'): ?>checked="checked"<?php endif; ?> value="0" onchange="element_toggle( 'post_users1', 'post_users_sel1'); element_toggle( 'post_users2', 'post_users_sel2');" disabled="disabled" /> 
				<label for="post_users0">Create new users</label><br />
				
			<input type="radio" class="disabled" name="post_users" id="post_users1" <?php if( $post_users == '1'): ?>checked="checked"<?php endif; ?> value="1" onchange="element_toggle( 'post_users1', 'post_users_sel1'); element_toggle( 'post_users2', 'post_users_sel2');" disabled="disabled" /> 
				<label for="post_users1">Associate new users as</label>
				<select name="post_users_sel1" id="post_users_sel1" <?php if( $post_users != '1'): ?>disabled="disabled"<?php endif; ?>>
					<?php 
					foreach( $authors as $author) {
						echo( $post_users_sel1 == $author->name ? '<option selected>' : '<option>');
						echo( $author->name . '</option>');
					}				 
					?>
				</select><br />
				
			<input type="radio" name="post_users" id="post_users2" <?php if( $post_users == '2'): ?>checked="checked"<?php endif; ?> value="2" onchange="element_toggle( 'post_users1', 'post_users_sel1'); element_toggle( 'post_users2', 'post_users_sel2');" />
				<label for="post_users2">Associate all users as</label>
				<select name="post_users_sel2" id="post_users_sel2" <?php if( $post_users != '2'): ?>disabled="disabled"<?php endif; ?>>
					<?php 
					foreach( $authors as $author) {
						echo( $post_users_sel2 == $author->name ? '<option selected>' : '<option>');
						echo( $author->name . '</option>');
					}				 
					?>
				</select><br />
			
			<h4>Time-stamp</h4>
				
			<input type="radio" value="0" name="post_date" id="post_date0" <?php if( $post_date == '0'): ?>checked="checked"<?php endif; ?> onchange="element_toggle( 'post_date2', 'post_date_entry');" />
				<label for="post_date0">Now</label>
			<input type="radio" value="1" name="post_date" id="post_date1" <?php if( $post_date == '1'): ?>checked="checked"<?php endif; ?> onchange="element_toggle( 'post_date2', 'post_date_entry');" />
				<label for="post_date1">WP Date</label>
			<input type="radio" value="2" name="post_date" id="post_date2" <?php if( $post_date == '2'): ?>checked="checked"<?php endif; ?> onchange="element_toggle( 'post_date2', 'post_date_entry');" />
				<label for="post_date2">Custom date:</label>
				<input type="text" name="post_date_entry" id="post_date_entry" value="<?php echo( $post_date_entry); ?>" <?php if( $post_date != '2'): ?>disabled="disabled"<?php endif; ?>/><br />
			
			<h4>Placement</h4>
			
			<input type="radio" name="post_place" id="post_place0" <?php if( $post_place == '0'): ?>checked="checked"<?php endif; ?> value="0" onchange="element_toggle( 'post_place1', 'post_place_sel');" />
				<label for="post_place0">Wolf Home page</label><br />
			<input type="radio" name="post_place" id="post_place1" <?php if( $post_place == '1'): ?>checked="checked"<?php endif; ?> <?php if( !$cat_import): ?>disabled="disabled"<?php endif; ?> value="1" onchange="element_toggle( 'post_place1', 'post_place_sel');" />
				<label for="post_place1">Under WP Category</label><br />
			<input type="radio" name="post_place" id="post_place2" <?php if( $post_place == '2'): ?>checked="checked"<?php endif; ?> value="2" onchange="element_toggle( 'post_place1', 'post_place_sel');" />
				<label for="post_place2">Custom parent page</label>
				<select name="post_place_sel" id="post_place_sel" <?php if( $post_place != '2'): ?>disabled="disabled"<?php endif; ?>>
					<?php 
						foreach( $children as $child) {
							echo( $post_place_sel == $child->title ? '<option selected' : '<option');
							echo( ' value="'.$child->title.'">'. $child->title . '</option>');
						}
					?>
				</select><br /><br />
			
			<input type="checkbox" name="post_uncat" id="post_uncat" <?php if( $post_uncat): ?>checked="checked"<?php endif; ?> value="true" onchange="element_toggle( 'post_uncat', 'post_uncat_sel');" />
				<label for="post_uncat">Map category "Uncategorized" to</label> 
				<select name="post_uncat_sel" id="post_uncat_sel" <?php if( !$post_uncat): ?>disabled="disabled"<?php endif; ?>>
					<?php 
						foreach( $children as $child) {
							echo( $post_uncat_sel == $child->title ? '<option selected' : '<option');
							echo( ' value="'.$child->title.'">'. $child->title . '</option>');
						}
					?>
				</select>
				
			<h4>Categorization</h4>
			
			<input type="radio" name="post_cat" id="post_cat0" <?php if( $post_cat == '0'): ?>checked="checked"<?php endif; ?> value="0" />
				<label for="post_cat0">No categorization</label><br />
			<input type="radio" name="post_cat" id="post_cat1" <?php if( $post_cat == '1'): ?>checked="checked"<?php endif; ?> value="1" />
				<label for="post_cat1">Categorize by year</label><br />
			<input type="radio" name="post_cat" id="post_cat2" <?php if( $post_cat == '2'): ?>checked="checked"<?php endif; ?> value="2" />
				<label for="post_cat2">Categorize by month</label><br />
			<input type="radio" name="post_cat" id="post_cat3" <?php if( $post_cat == '3'): ?>checked="checked"<?php endif; ?> value="3" />
				<label for="post_cat3">Categorize by date</label><br />
			
		</fieldset>				
		<br />
		<input type="submit" value="Submit" />
	</form>