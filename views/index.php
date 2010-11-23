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
	<h1>Import</h1>
	<fieldset style="padding: 0.5em;">
    <legend style="padding: 0em 0.5em 0em 0.5em; font-weight: bold;">Warning!</legend>
		<h2 style="text-align: center;">Please save your WolfCMS DataBase first !!!</h2>
		<h3 style="text-align: center;">I won't be held responsible of any data loss cause I've warned you !!!</h3>

<form style="text-align:center;" action="<?php echo get_url('plugin/wpdb_import/import'); ?>" method="post">
	<input type="hidden" name="importPost" value="1" />
<!--	<div>
		<p>Choisir quel contenu à importer</p>
		<p><input type="checkbox" checked name="importCategory"/><label for="pages">Category</label></p>
		<p><input type="checkbox" name="importPage"/><label for="pages">Pages</label></p>
		<p><input type="checkbox" name="importPost"/><label for="pages">Posts</label></p>
		<p><input type="checkbox" name="importComment"/><label for="posts">Comments</label></p>
	</div>-->
	<input style="width:100px;" type="submit" value="Import">
</form>



  </fieldset>

<!--<form action="<?php echo get_url('plugin/wpdb_import/upload'); ?>" method="post" enctype="multipart/form-data">
 <input type="hidden" name="MAX_FILE_SIZE" value="10240000">
     File : <input type="file" name="wpdb_file">
     <input type="submit" name="envoyer" value="Envoyer le fichier">
</form>-->

