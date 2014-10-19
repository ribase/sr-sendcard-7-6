<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

// Register Send-A-Card static templates
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/PluginSetup', 'Send-A-Card Setup');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/DefaultStyles', 'Send-A-Card CSS Styles');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_srsendcard_card');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_srsendcard_card');

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1'] = 'layout,select_key';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(array('LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xlf:tt_content.list_type', $_EXTKEY.'_pi1'), 'list_type');

if (TYPO3_MODE === 'BE') {
	$GLOBALS['TBE_MODULES_EXT']['xMOD_db_new_content_el']['addElClasses']['tx_srsendcard_pi1_wizicon'] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'pi1/class.tx_srsendcard_pi1_wizicon.php';
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule('web', 'txsrsendcardM1', '', \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'mod1/');
}
