<?php
defined('TYPO3_MODE') or die();
// Compatibility with 6.2
if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(\TYPO3\CMS\Core\Utility\VersionNumberUtility::getNumericTypo3Version()) < 7000000) {
	$GLOBALS['TCA']['tx_srsendcard_domain_model_card']['ctrl']['iconfile'] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('sr_sendcard') . 'Resources/Public/Images/tx_srsendcard_domain_model_card.svg';
}