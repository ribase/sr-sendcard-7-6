<?php
/*
 * Register necessary class names with autoloader
 */
$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('sr_sendcard');
return array(
	'tx_srsendcard_statistics' => $extensionPath . 'mod1/class.tx_srsendcard_statistics.php',
);