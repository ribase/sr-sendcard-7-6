<?php
defined('TYPO3_MODE') or die();

// Configure Send-A-Card static templates
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('sr_sendcard', 'Configuration/TypoScript/PluginSetup', 'Send-A-Card Setup');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('sr_sendcard', 'Configuration/TypoScript/DefaultStyles', 'Send-A-Card CSS Styles');