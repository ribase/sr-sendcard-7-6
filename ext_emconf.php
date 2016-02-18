<?php
/***************************************************************
 * Extension Manager/Repository config file for ext "sr_sendcard".
 *
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'Send-A-Card',
	'description' => 'Sender selects, formats, previews and sends a postcard. Recipient receives an email at a set date with link to the postcard. Sender may be notified that the card was viewed.',
	'category' => 'plugin',
	'version' => '4.0.0',
	'state' => 'stable',
	'uploadfolder' => 1,
	'clearcacheonload' => 1,
	'author' => 'Stanislas Rolland',
	'author_email' => 'typo3(arobas)sjbr.ca',
	'author_company' => 'SJBR',
	'constraints' => array(
		'depends' => array(
			'scheduler' => '',
			'php' => '5.3.0-0.0.0',
			'typo3' => '6.2.0-7.6.99'
		),
		'conflicts' => array(
			'cc_cbrowse' => '',
		),
		'suggests' => array(
			'sr_freecap' => ''
		)
	)
);