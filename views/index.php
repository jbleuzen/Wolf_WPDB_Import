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
 *   https://github.com/spacez320/wpdb_import
 */

/* Security measure */
if (!defined('IN_CMS')) { exit(); }

$directory = 'wolf/plugins/wpdb_import/uploads/';
$filename = 'wordpress.xml';

?>
<h1><?php echo __('WordPress Import');?></h1>
<fieldset style="padding: 0.5em; margin-top:5px;">
	<h2><?php echo __('Backup your WolfCMS database before you do this.');?></h2>
	<h3><?php echo __('This will make life easier if you change your mind or make a mistake.');?></h3>
	<hr />
	<div style="margin-left: 100px;">
	<p style="font-weight: bold;">Usage:</p>
	<ol>
		<li><span style="font-weight: bold;">Upload</span> a Wordpress eXtended RSS file.</li>
		<li><span style="font-weight: bold;">Import</span> the file's contents.</li>
	</ol>
	</div>
	<div style="text-align:center;">
	<?php if( file_exists ($directory . $filename)){ ?>
		<!-- print some information and confirmation that the file is uploaded -->
		<form action="<?php echo get_url('plugin/wpdb_import/WPDB_import'); ?>" method="post">
			<input style="height:25px;margin:25px 0px 15px;width:100px;" type="submit" value="<?php echo __('Import'); ?>">
		</form>
	<?php } else {?>
		<form  action="<?php echo get_url('plugin/wpdb_import/WPDB_upload'); ?>" method="post" enctype="multipart/form-data">
			<!-- max file size is 25MB -->
			<input type="hidden" name="MAX_FILE_SIZE" value="25000000" />
			<input type="file" name="wpdb_file">
			<input style="height:25px;margin:25px 0px 15px;" type="submit" name="Upload" value="<?php echo __('Upload file'); ?>">
		</form>
	<?php } ?>
	</div>
</fieldset>