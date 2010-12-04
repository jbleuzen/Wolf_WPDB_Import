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

/* Security measure */
if (!defined('IN_CMS')) { exit(); }
 
?>

<p class="button">
	<a href="<?php echo get_url('plugin/wpdb_import/'); ?>">
		<img src="<?php echo WPDB_ROOT;?>/images/upload.png" align="middle" alt="upload icon" />
		<?php echo __('Import'); ?>
	</a>
</p>
<p class="button">
	<a href="<?php echo get_url('plugin/wpdb_import/documentation'); ?>">
		<img src="<?php echo WPDB_ROOT;?>/images/page.png" align="middle" alt="documentation icon" />
		<?php echo __('Documentation'); ?>
	</a>
</p>
<?php if( file_exists ("wordpress.xml")){ ?>
<p class="button">
	<a href="<?php echo get_url('plugin/wpdb_import/deleteWPFile'); ?>">
		<img src="<?php echo WPDB_ROOT;?>/images/delete.png" align="middle" alt="delete icon" />
		<?php echo __('Delete WordPress XML file.');?>
	</a>
</p>
<?php } ?>
<div class="box">
	<h2><?php echo __('WordPress Import plugin');?></h2>
	<p><?php echo __('Import data from a WordPress DataBase into WolfCMS.'); ?></p>
	<p style="text-align:center;"><?php echo __('Version'); ?> - <?php echo WPDB_VERSION; ?></p>
</div>
