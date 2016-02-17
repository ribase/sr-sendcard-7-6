<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_srsendcard_domain_model_card');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_srsendcard_domain_model_card');

if (TYPO3_MODE === 'BE') {
	// Backend module
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
		'web',
		'txsrsendcardM1',
		'',
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Modules/Statistics/',
		array(
			'script' => '_DISPATCH',
			'access' => 'group,user',
			'name' => 'web_txsrsendcardM1',
			'labels' => array(
				'tabs_images' => array(
					'tab' => 'EXT:' . $_EXTKEY .'/Resources/Public/Images/tx_srsendcard_domain_model_card.svg'
				),
				'll_ref' => 'LLL:EXT:' . $_EXTKEY .'/Resources/Private/Language/locallang_mod.xlf'
			)
		)
	);
}