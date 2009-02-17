<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_srsendcard_card'] = Array (
	'ctrl' => $TCA['tx_srsendcard_card']['ctrl'],
	'interface' => Array (
		'showRecordFieldList' => 'hidden,card,image,cardaltText,img_width,img_height,selection_image,selection_imagealtText,selection_image_width,selection_image_height,link_pid'
	),
	'columns' => Array (
		'hidden' => Array (
			'exclude' => 0,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => Array (
				'type' => 'check',
				'default' => '0'
			)
		),
		'sys_language_uid' => Array (
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => Array (
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => Array(
					Array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages',-1),
					Array('LLL:EXT:lang/locallang_general.xml:LGL.default_value',0)
				)
			)
	    	),
		'l18n_parent' => Array (
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('', 0),
				),
				'foreign_table' => 'tx_srsendcard_card',
				'foreign_table_where' => 'AND tx_srsendcard_card.pid=###CURRENT_PID### AND tx_srsendcard_card.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => Array (
			'config' => Array (
	    			'type' => 'passthrough'
			)
	    	),
		'starttime' => Array (
			'exclude' => 0,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config' => Array (
				'type' => 'input',
				'size' => '8',
				'max' => '20',
				'eval' => 'date',
				'checkbox' => '0',
				'default' => '0'
			)
		),
		'endtime' => Array (
			'exclude' => 0,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config' => Array (
				'type' => 'input',
				'size' => '8',
				'max' => '20',
				'eval' => 'date',
				'checkbox' => '0',
				'default' => '0',
				'range' => Array (
					'upper' => mktime(0,0,0,12,31,2020),
					'lower' => mktime(0,0,0,date('m')-1,date('d'),date('Y'))
				)
			)
		),
		'card' => Array (
			'exclude' => 0,
			'label' => 'LLL:EXT:sr_sendcard/locallang_db.xml:tx_srsendcard_card.card',
			'config' => Array (
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim,required',
			)
		),
		'image' => Array (
			'exclude' => 0,
			'label' => 'LLL:EXT:sr_sendcard/locallang_db.xml:tx_srsendcard_card.image',
			'config' => Array (
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => 'jpg,jpeg,gif,png,mov,swf',
				'max_size' => 1200,
				'uploadfolder' => 'uploads/tx_srsendcard',
				'show_thumbs' => 1,
				'size' => 1,
				'minitems' => 1,
				'maxitems' => 1,
			)
		),
		'cardaltText' => Array (
			'exclude' => 0,
			'label' => 'LLL:EXT:sr_sendcard/locallang_db.xml:tx_srsendcard_card.altText',
			'config' => Array (
				'type' => 'input',
				'size' => '30',	
				'eval' => 'trim,required',
			)
		),
		'img_width' => Array (		
			'exclude' => 0,	
			'label' => 'LLL:EXT:sr_sendcard/locallang_db.xml:tx_srsendcard_card.img_width',
			'config' => Array (
				'type' => 'input',
				'size' => '8',
				'max' => '20',
				'eval' => 'number',
				'default' => '0',
				'range' => Array (
					'upper' => 2000,
					'lower' => 0)
			)
		),
		'img_height' => Array (		
			'exclude' => 0,	
			'label' => 'LLL:EXT:sr_sendcard/locallang_db.xml:tx_srsendcard_card.img_height',
			'config' => Array (
				'type' => 'input',
				'size' => '8',
				'max' => '20',
				'eval' => 'number',
				'default' => '0',
				'range' => Array (
					'upper' => 2000,
					'lower' => 0 )
			)
		),
		'selection_image' => Array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:sr_sendcard/locallang_db.xml:tx_srsendcard_card.selection_image',		
			'config' => Array (
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => 'jpg,jpeg,gif,png',
				'max_size' => 500,	
				'uploadfolder' => 'uploads/tx_srsendcard',
				'show_thumbs' => 1,	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'selection_imagealtText' => Array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:sr_sendcard/locallang_db.xml:tx_srsendcard_card.selectionaltText',		
			'config' => Array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'selection_image_width' => Array (		
			'exclude' => 0,	
			'label' => 'LLL:EXT:sr_sendcard/locallang_db.xml:tx_srsendcard_card.selection_image_width',
			'config' => Array (
				'type' => 'input',
				'size' => '8',
				'max' => '20',
				'eval' => 'number',
				'default' => '0',
				'range' => Array (
					'upper' => 2000,
					'lower' => 0)
			)
		),
		'selection_image_height' => Array (		
			'exclude' => 0,	
			'label' => 'LLL:EXT:sr_sendcard/locallang_db.xml:tx_srsendcard_card.selection_image_height',
			'config' => Array (
				'type' => 'input',
				'size' => '8',
				'max' => '20',
				'eval' => 'number',
				'default' => '0',
				'range' => Array (
					'upper' => 2000,
					'lower' => 0 )
			)
		),
		'link_pid' => Array (
			'exclude' => 0,	
			'label' => 'LLL:EXT:sr_sendcard/locallang_db.xml:tx_srsendcard_card.link_pid',
			'config' => Array (
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'pages',
				'size' => '1',
				'maxitems' => '1',
				'minitems' => '0',
				'show_thumbs' => '1'
			)
		)
	),
	'types' => Array (
		'0' => Array( 'showitem' => 'sys_language_uid;;;;1-1-1, l18n_parent, l18n_diffsource,hidden;;1;;2-2-2, card, image, cardaltText, img_width, img_height, selection_image, selection_imagealtText, selection_image_width, selection_image_height, link_pid')
	),
	'palettes' => Array (
		'1' => Array('showitem' => 'starttime, endtime')
	)
);

if ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sr_sendcard']['keepCardLanguageOverlay']) {
$TCA['tx_srsendcard_card_language_overlay'] = Array (
	'ctrl' => $TCA['tx_srsendcard_card_language_overlay']['ctrl'],
	'interface' => Array (
		'showRecordFieldList' => 'hidden,card_uid,sys_language_uid,card,cardaltText,selection_imagealtText'
	),
	'columns' => Array (
		'hidden' => Array (		
			'exclude' => 0,	
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => Array (
				'type' => 'check',
				'default' => '0'
			)
		),
		'starttime' => Array (
			'exclude' => 0,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config' => Array (
				'type' => 'input',
				'size' => '8',
				'max' => '20',
				'eval' => 'date',
				'default' => '0',
				'checkbox' => '0'
			)
		),
		'endtime' => Array (
			'exclude' => 0,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config' => Array (
				'type' => 'input',
				'size' => '8',
				'max' => '20',
				'eval' => 'date',
				'checkbox' => '0',
				'default' => '0',
				'range' => Array (
					'upper' => mktime(0,0,0,12,31,2020),
					'lower' => mktime(0,0,0,date('m')-1,date('d'),date('Y'))
				)
			)
		),
		'card_uid' => Array (
			'exclude' => 0,	
			'label' => 'LLL:EXT:sr_sendcard/locallang_db.xml:tx_srsendcard_card',
			'config' => Array (
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_srsendcard_card',
				'size' => '1',
				'maxitems' => '1',
				'minitems' => '0',
				'show_thumbs' => '0'
			)
		),
		'sys_language_uid' => Array (
			'exclude' => 0,	
			'label' => 'LLL:EXT:sr_sendcard/locallang_db.xml:tx_srsendcard_card_language_overlay.sys_language_uid',
			'config' => Array (
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'sys_language',
				'size' => '1',
				'maxitems' => '1',
				'minitems' => '0',
				'show_thumbs' => '0'
			)
		),
		'card' => Array (
			'exclude' => 0,
			'label' => 'LLL:EXT:sr_sendcard/locallang_db.xml:tx_srsendcard_card.card',
			'config' => Array (
				'type' => 'input',
				'size' => '30',	
				'eval' => 'trim,required',
			)
		),
		'cardaltText' => Array (
			'exclude' => 0,
			'label' => 'LLL:EXT:sr_sendcard/locallang_db.xml:tx_srsendcard_card.altText',
			'config' => Array (
				'type' => 'input',
				'size' => '30',	
				'eval' => 'trim,required',
			)
		),
		'selection_imagealtText' => Array (
			'exclude' => 0,
			'label' => 'LLL:EXT:sr_sendcard/locallang_db.xml:tx_srsendcard_card.selectionaltText',
			'config' => Array (
				'type' => 'input',
				'size' => '30',	
				'eval' => 'trim',
			)
		),
	),
	'types' => Array (
		'0' => Array( 'showitem' => 'hidden;;1;;1-1-1, card_uid, sys_language_uid, card, cardaltText, selection_imagealtText')
	),
	'palettes' => Array (
		'1' => Array('showitem' => 'starttime, endtime')
	)
);
}

?>