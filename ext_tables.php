<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_srsendcard_domain_model_card');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_srsendcard_domain_model_card');

if (TYPO3_MODE === 'BE') {
	// Backend statistics module
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'SJBR.' . $_EXTKEY,
		// Make module a submodule of 'web'
		'web',
		// Submodule key
		'Statistics',
		// Position
		'',
		// An array holding the controller-action combinations that are accessible
		array(
			'Statistics' => 'index,recent,popular'
		),
		array(
			'access' => 'user,group',
			'icon' => 'EXT:' . $_EXTKEY . '/Resources/Public/Images/tx_srsendcard_domain_model_card.svg',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xlf'
		)
	);
	// Add module configuration setup
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($_EXTKEY, 'setup', '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/Configuration/TypoScript/Statistics/setup.txt">');
}