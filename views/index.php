<?php
/*
 * Wolf CMS - Content Management Simplified. <http://www.wolfcms.org>
 * Copyright (C) 2009 Martijn van der Kleijn <martijn.niji@gmail.com>
 *
 * This file is part of Wolf CMS.
 *
 * Wolf CMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Wolf CMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Wolf CMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Wolf CMS has made an exception to the GNU General Public License for plugins.
 * See exception.txt for details and the full text.
 */

/* Security measure */
if (!defined('IN_CMS')) { exit(); }

/**
 * The BackupRestore plugin provides administrators with the option of backing
 * up their pages and settings to an XML file.
 *
 * @package plugins
 * @subpackage backup_restore
 *
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @version 0.0.1
 * @since Wolf version 0.6.0
 * @license http://www.gnu.org/licenses/gpl.html GPLv3 License
 * @copyright Martijn van der Kleijn, 2009
 */
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
	<div>
		<p>Choisir quel contenu à importer</p>
		<p><input type="checkbox"  name="posts"/><label for="posts">Posts</label></p>
		<p><input type="checkbox"  name="pages"/><label for="pages">Pages</label></p>
	</div>
	<input type="submit" value="Import">
</form>
