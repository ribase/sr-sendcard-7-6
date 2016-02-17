<?php
$extensionResourcesLanguagePath = 'LLL:EXT:sr_sendcard/Resources/Private/Language/locallang_db.xlf:';
return array(
	'ctrl' => array(
		'title' => $extensionResourcesLanguagePath . 'tx_srsendcard_sendcard',
		'label' => 'fromwho',
		'default_sortby' => 'ORDER BY uid',
		'sortby' => 'uid',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'iconfile' => 'EXT:sr_sendcard/Resources/Public/Images/tx_srsendcard_sendcard.svg',
		'adminOnly' => 1,
		'hideTable' => 1,
		'readOnly' => 1
	),
	'interface' => array(
		'showRecordFieldList' => 'hidden,starttime,endtime,fromwho'
	),
	'columns' => array(
		'hidden' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'starttime' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => '8',
				'max' => '20',
				'eval' => 'date',
				'checkbox' => '0',
				'default' => '0'
			)
		),
		'endtime' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => '8',
				'max' => '20',
				'eval' => 'date',
				'checkbox' => '0',
				'default' => '0',
				'range' => array(
					'upper' => mktime(0,0,0,12,31,2020),
					'lower' => mktime(0,0,0,date('m')-1,date('d'),date('Y'))
				)
			)
		),
		'fromWho' => array(		
			'exclude' => 0,		
			'label' => $extensionResourcesLanguagePath . 'tx_srsendcard_sendcard.fromwho',		
			'config' => array(
				'type' => 'input',	
				'size' => '360',	
				'eval' => 'trim',
			)
		),
	),
	'types' => array(
		'0' => Array( 'showitem' => 'hidden;;;;1-1-1, fromwho')
	),
	'palettes' => array(
		'1' => Array('showitem' => 'starttime, endtime')
	)
);