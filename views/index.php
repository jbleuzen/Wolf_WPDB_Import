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
	<form action="<?php echo get_url('plugin/wpdb_import/import'); ?>" method="post">
	<fieldset style="padding: 0.5em;">
    <legend style="padding: 0em 0.5em 0em 0.5em; font-weight: bold;">Warning!</legend>
		<h2 style="text-align: center;">Remember to save your WolfCMS DataBase first !!!</h2>
		<p style="text-align: center;">I won't be held responsible of any data loose cause I've warned you !!!</p>
  </fieldset>
	<div>
	Choisir l'utilisateur qui importe le contenu
	<select name="userid" id="">
	<?php
		$all_users = User::findAll();
	  $current_user_id = AuthUser::getRecord()->id;
		foreach($all_users as $user){
			$current = $user->id == $current_user_id ? 'selected' : '';
			echo "<option $current value='$user->id'>$user->name</option>";
		}
	?>
	</select>
	</div>
<!--	<div>
		<p>Choisir quel contenu à importer</p>
		<p><input type="checkbox"  name="posts"/><label for="posts">Posts</label></p>
		<p><input type="checkbox"  name="pages"/><label for="pages">Pages</label></p>
	</div>-->
	<input type="submit" value="Import">
</form>
