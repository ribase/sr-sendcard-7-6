<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}
$typo3Version = t3lib_utility_VersionNumber::convertVersionNumberToInteger(TYPO3_version);

// Register Send-A-Card static templates
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/PluginSetup', 'Send-A-Card Setup');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/DefaultStyles', 'Send-A-Card CSS Styles');

t3lib_extMgm::allowTableOnStandardPages('tx_srsendcard_card');
t3lib_extMgm::addToInsertRecords('tx_srsendcard_card');

if ($typo3Version < 6001000) {
	t3lib_div::loadTCA('tt_content');
}
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1'] = 'layout,select_key';
t3lib_extMgm::addPlugin(array('LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xlf:tt_content.list_type', $_EXTKEY.'_pi1'), 'list_type');

if (TYPO3_MODE == 'BE') {
	$GLOBALS['TBE_MODULES_EXT']['xMOD_db_new_content_el']['addElClasses']['tx_srsendcard_pi1_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_srsendcard_pi1_wizicon.php';
	t3lib_extMgm::addModule('web', 'txsrsendcardM1', '', t3lib_extMgm::extPath($_EXTKEY).'mod1/');
}
unset($typo3Version);
?>
