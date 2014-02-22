<?php

########################################################################
# Extension Manager/Repository config file for ext "sr_sendcard".
#
# Auto generated 09-06-2012 20:53
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Send-A-Card',
	'description' => 'Sender selects, formats, previews and sends a postcard. Recipient receives an email at a set date (sent by cron job if future) with link to the postcard. Sender may be notified that the card was viewed.',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '3.0.1',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 1,
	'lockType' => '',
	'author' => 'Stanislas Rolland',
	'author_email' => 'typo3(arobas)sjbr.ca',
	'author_company' => 'SJBR',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'scheduler' => '',
			'php' => '5.3.0-0.0.0',
			'typo3' => '4.5.0-6.2.99',
		),
		'conflicts' => array(
			'cc_cbrowse' => '',
		),
		'suggests' => array(
			'sr_freecap' => '',
		),
	),
	'_md5_values_when_last_written' => 'a:35:{s:9:"ChangeLog";s:4:"344d";s:16:"ext_autoload.php";s:4:"c6ed";s:12:"ext_icon.gif";s:4:"7952";s:17:"ext_localconf.php";s:4:"ee5a";s:14:"ext_tables.php";s:4:"1dde";s:14:"ext_tables.sql";s:4:"06b9";s:13:"locallang.xml";s:4:"b84f";s:16:"locallang_db.xml";s:4:"ff65";s:7:"tca.php";s:4:"a66e";s:14:"doc/manual.sxw";s:4:"fd84";s:15:"fonts/koala.ttf";s:4:"78d7";s:15:"fonts/ninos.ttf";s:4:"f7fa";s:33:"lib/class.tx_srsendcard_email.php";s:4:"a0f7";s:39:"mod1/class.tx_srsendcard_statistics.php";s:4:"1f9c";s:13:"mod1/conf.php";s:4:"3b1b";s:14:"mod1/index.php";s:4:"7efb";s:18:"mod1/locallang.xml";s:4:"aa63";s:22:"mod1/locallang_mod.xml";s:4:"25e7";s:19:"mod1/moduleicon.gif";s:4:"7952";s:24:"music/OverTheRainbow.mid";s:4:"dc9b";s:31:"music/Pomp_and_Circumstance.mid";s:4:"4b05";s:22:"music/autumnleaves.mid";s:4:"d0b8";s:14:"pi1/ce_wiz.gif";s:4:"7952";s:31:"pi1/class.tx_srsendcard_pi1.php";s:4:"56ba";s:40:"pi1/class.tx_srsendcard_pi1_deferred.php";s:4:"b773";s:39:"pi1/class.tx_srsendcard_pi1_wizicon.php";s:4:"d872";s:16:"pi1/imprimir.gif";s:4:"eee3";s:17:"pi1/locallang.xml";s:4:"3e5d";s:31:"pi1/sello-la-matatena100x61.jpg";s:4:"3a87";s:30:"pi1/tx_srsendcard_htmlmail.css";s:4:"8b08";s:28:"pi1/tx_srsendcard_xhtml.tmpl";s:4:"8774";s:31:"static/css_styled/constants.txt";s:4:"ca81";s:27:"static/css_styled/setup.txt";s:4:"8da3";s:40:"tasks/class.tx_srsendcard_cardmailer.php";s:4:"e245";s:64:"tasks/class.tx_srsendcard_cardmailer_additionalfieldprovider.php";s:4:"3d70";}',
);

?>