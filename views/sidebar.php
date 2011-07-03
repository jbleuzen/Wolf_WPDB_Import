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
 
?>

<p class="button">
	<a href="<?php echo get_url('plugin/wpdb_import/'); ?>">
		<?php if( file_exists ("wordpress.xml")){ ?>	
		<img src="<?php echo WPDB_ROOT;?>/images/import.png" align="middle" alt="upload icon" />
		<?php echo __('Import file'); ?>
		<?php } else { ?>
			<img src="<?php echo WPDB_ROOT;?>/images/upload.png" align="middle" alt="upload icon" />
			<?php echo __('Upload file'); ?>
		<?php } ?>
	</a>
</p>
<?php if( file_exists ('wolf/plugins/wpdb_import/uploads/wordpress.xml')){ ?>
<p class="button">
	<script>
		$(document).ready(function() {
			$('#wpdb_delete').click(function(event){
				if(confirm("<?php echo __('Are you sure you want to delete the currently uploaded file?');?>") == false){
					event.preventDefault();
				}
			});
		});
	</script>
	<a id="wpdb_delete" href="<?php echo get_url('plugin/wpdb_import/WPDB_deleteFile'); ?>" onclick="ConfirmMessage();">
		<img src="<?php echo WPDB_ROOT;?>/images/delete.png" align="middle" alt="delete icon" />
		<?php echo __('Delete file');?>
	</a>
</p>
<?php } ?>
<p class="button">
	<a href="<?php echo get_url('plugin/wpdb_import/settings'); ?>">
		<img src="<?php echo WPDB_ROOT;?>/images/settings.png" align="middle" alt="settings icon" />
		<?php echo __('Settings');?>
	</a>
</p>
<p class="button">
	<a href="<?php echo get_url('plugin/wpdb_import/documentation'); ?>">
		<img src="<?php echo WPDB_ROOT;?>/images/page.png" align="middle" alt="documentation icon" />
		<?php echo __('Documentation'); ?>
	</a>
</p>
<div class="box">
	<h2><?php echo __('Wordpress Database Import');?></h2>
	<p><?php echo __('Used to populate a WolfCMS installation from a Wordpress eXtended RSS file.'); ?></p>
	<p style="text-align:center;"><?php echo __('Version'); ?> - <?php echo WPDB_VERSION; ?></p>
</div>
