<?php

/*
 * WPDB Import - WolfCMS importing WordPress DataBase plugin
 *
 * Copyright (c) 2010 Johan BLEUZEN  and  Matthew COLEMAN
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   https://github.com/jbleuzen/Wolf_WPDB_Import
 *
 */

/* Security measure */
if (!defined('IN_CMS')) { exit(); }

Plugin::addJavascript('wpdb_import', 'test.js');

?>
<h1><?php echo __('WordPress Import');?></h1>
<fieldset style="padding: 0.5em; margin-top:5px;">
	<h2 style="text-align: center;"><?php echo __('Remember to save your WolfCMS DataBase !!!');?></h2>
	<h3 style="text-align: center;"><?php echo __('I cannot be held responsible of any data loss. Because I have warned you !!!');?></h3>
	<div style="text-align:center;">
	<?php if( file_exists ("wordpress.xml")){ ?>
		<form action="<?php echo get_url('plugin/wpdb_import/import'); ?>" method="post">
			<input style="height:25px;margin:25px 0px 15px;width:100px;" type="submit" value="<?php echo __('Import'); ?>">
		</form>
	<?php } else {?>
		<form  action="<?php echo get_url('plugin/wpdb_import/upload'); ?>" method="post" enctype="multipart/form-data">
			<!-- max file size is 25MB -->
			<input type="hidden" name="MAX_FILE_SIZE" value="25000000" />
			<input type="file" name="wpdb_file">
			<input style="height:25px;margin:25px 0px 15px;" type="submit" name="Upload" value="<?php echo __('Upload file'); ?>">
		</form>
	<?php } ?>
	</div>
</fieldset>

<script type="text/javascript">alert( testFunc());</script>