<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_srsendcard_card');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_srsendcard_card');

if (TYPO3_MODE === 'BE') {
	// Backend module
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule('web', 'txsrsendcardM1', '', \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'mod1/');
}