<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "sr_sendcard".
 *
 * Auto generated 22-03-2014 18:15
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'Send-A-Card',
	'description' => 'Sender selects, formats, previews and sends a postcard. Recipient receives an email at a set date with link to the postcard. Sender may be notified that the card was viewed.',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '3.2.0',
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
	'constraints' => 
	array (
		'depends' => 
		array (
			'scheduler' => '',
			'php' => '5.3.0-0.0.0',
			'typo3' => '6.2.0-6.2.99'
		),
		'conflicts' => 
		array (
			'cc_cbrowse' => '',
		),
		'suggests' => 
		array (
			'sr_freecap' => '',
		)
	)
);
?>