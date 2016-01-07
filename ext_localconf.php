<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('options.saveDocNew.tx_srsendcard_card=1');

// Register card mailer task
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['SJBR\\SrSendcard\\Task\\CardMailerTask'] = array(
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xlf:cardMailer.name',
	'description'      => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xlf:cardMailer.description',
	'additionalFields' => 'SJBR\\SrSendcard\\Task\\CardMailerTaskAdditionalFieldProvider'
);

if (TYPO3_MODE === 'BE') {
	// Configuration du wizard de nouvel élément
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/Configuration/TypoScript/Page/modWizards.txt">');
}