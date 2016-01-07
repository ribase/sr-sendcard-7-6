<?php
defined('TYPO3_MODE') or die();

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['sr_sendcard_pi1'] = 'layout,select_key';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(array('LLL:EXT:sr_sendcard/Resources/Private/Language/locallang_db.xlf:tt_content.list_type', 'sr_sendcard_pi1'), 'list_type', 'sr_sendcard');