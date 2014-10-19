<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('options.saveDocNew.tx_srsendcard_card=1');

// Register the plugin
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43($_EXTKEY, 'pi1/class.tx_srsendcard_pi1.php', '_pi1', 'list_type', 0);

// Register card mailer task
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['SJBR\\SrSendcard\\Task\\CardMailerTask'] = array(
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xlf:cardMailer.name',
	'description'      => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xlf:cardMailer.description',
	'additionalFields' => 'SJBR\\SrSendcard\\Task\\CardMailerTaskAdditionalFieldProvider'
);
