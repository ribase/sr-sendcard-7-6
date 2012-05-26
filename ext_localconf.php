<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::addUserTSConfig('options.saveDocNew.tx_srsendcard_card=1');
	// Register the plugin
t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_srsendcard_pi1.php', '_pi1', 'list_type', 0);
	// Register card mailer task
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_srsendcard_cardMailer'] = array(
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:' . $_EXTKEY . '/locallang.xml:cardMailer.name',
	'description'      => 'LLL:EXT:' . $_EXTKEY . '/locallang.xml:cardMailer.description',
	'additionalFields' => 'tx_srsendcard_cardMailer_AdditionalFieldProvider'
);

?>
