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
<h1><?php echo __('Documentation'); ?></h1>

<h2>You have a question ?</h2>
<p>If you find a bug or have a request, please drop me word here :</p>
<ul>
	<li><a href="http://www.wolfcms.org/forum/topic1006.html" target="_blank">WolfCMS Forum</a></li>
	<li><a href="https://github.com/jbleuzen/Wolf_WPDB_Import/issues" target="_blank">Github repository</a></li>
</ul>

<h2>How to use this plugin ?</h2>
<h3>Export your data from WordPress</h3>
<p>Go to your administration in WordPress, then expand the Tool tabs and click on the Export link.<br/>
Tune the export to your needs and hit the download button, you should get a new file named wordpress with a date following.
</p>
<h3>Upload your WordPress DataBase XML File</h3>
<p>The upload page is the <a href=".">same page</a> that import data.</p>
<p>By default, the plugin ask you to upload a WordPress database XML file. Now that we have generate it, just select the file you downloaded in previous step</p>
<h3>Import your WordPress DataBase XML File</h3>
<p>Once the WordPress XML file is uploaded, you now have a new button "Import". You just have to click on that button to make the magic happen.</p>