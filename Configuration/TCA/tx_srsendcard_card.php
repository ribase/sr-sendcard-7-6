<?php
$extensionResourcesLanguagePath = 'LLL:EXT:sr_sendcard/Resources/Private/Language/locallang_db.xlf:';
return array(
	'ctrl' => array(
		'title' => $extensionResourcesLanguagePath . 'tx_srsendcard_card',
		'label' => 'card',
		'default_sortby' => 'ORDER BY sorting',
		'sortby' => 'sorting',
		'delete' => 'deleted',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'iconfile' => t3lib_extMgm::extRelPath('sr_sendcard') . 'Resources/Public/Images/moduleicon.gif',
	),
	'interface' => array(
		'showRecordFieldList' => 'hidden,card,image,cardaltText,img_width,img_height,selection_image,selection_imagealtText,selection_image_width,selection_image_height,link_pid'
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
		'sys_language_uid' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => Array(
					Array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages',-1),
					Array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value',0)
				)
			)
	    	),
		'l18n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					Array('', 0),
				),
				'foreign_table' => 'tx_srsendcard_card',
				'foreign_table_where' => 'AND tx_srsendcard_card.pid=###CURRENT_PID### AND tx_srsendcard_card.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => array(
			'config' => array(
	    			'type' => 'passthrough'
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
		'card' => array(
			'exclude' => 0,
			'label' => $extensionResourcesLanguagePath . 'tx_srsendcard_card.card',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim,required',
			)
		),
		'image' => array(
			'exclude' => 0,
			'label' => $extensionResourcesLanguagePath . 'tx_srsendcard_card.image',
			'config' => array(
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
		'cardaltText' => array(
			'exclude' => 0,
			'label' => $extensionResourcesLanguagePath . 'tx_srsendcard_card.altText',
			'config' => array(
				'type' => 'input',
				'size' => '30',	
				'eval' => 'trim,required',
			)
		),
		'img_width' => array(		
			'exclude' => 0,	
			'label' => $extensionResourcesLanguagePath . 'tx_srsendcard_card.img_width',
			'config' => array(
				'type' => 'input',
				'size' => '8',
				'max' => '20',
				'eval' => 'number',
				'default' => '0',
				'range' => array(
					'upper' => 2000,
					'lower' => 0)
			)
		),
		'img_height' => array(		
			'exclude' => 0,	
			'label' => $extensionResourcesLanguagePath . 'tx_srsendcard_card.img_height',
			'config' => array(
				'type' => 'input',
				'size' => '8',
				'max' => '20',
				'eval' => 'number',
				'default' => '0',
				'range' => array(
					'upper' => 2000,
					'lower' => 0 )
			)
		),
		'selection_image' => array(		
			'exclude' => 0,		
			'label' => $extensionResourcesLanguagePath . 'tx_srsendcard_card.selection_image',		
			'config' => array(
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
		'selection_imagealtText' => array(		
			'exclude' => 0,		
			'label' => $extensionResourcesLanguagePath . 'tx_srsendcard_card.selectionaltText',		
			'config' => array(
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'selection_image_width' => array(		
			'exclude' => 0,	
			'label' => $extensionResourcesLanguagePath . 'tx_srsendcard_card.selection_image_width',
			'config' => array(
				'type' => 'input',
				'size' => '8',
				'max' => '20',
				'eval' => 'number',
				'default' => '0',
				'range' => array(
					'upper' => 2000,
					'lower' => 0)
			)
		),
		'selection_image_height' => array(		
			'exclude' => 0,	
			'label' => $extensionResourcesLanguagePath . 'tx_srsendcard_card.selection_image_height',
			'config' => array(
				'type' => 'input',
				'size' => '8',
				'max' => '20',
				'eval' => 'number',
				'default' => '0',
				'range' => array(
					'upper' => 2000,
					'lower' => 0 )
			)
		),
		'link_pid' => array(
			'exclude' => 0,	
			'label' => $extensionResourcesLanguagePath . 'tx_srsendcard_card.link_pid',
			'config' => array(
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
	'types' => array(
		'0' => Array( 'showitem' => 'sys_language_uid;;;;1-1-1, l18n_parent, l18n_diffsource,hidden;;1;;2-2-2, card, image, cardaltText, img_width, img_height, selection_image, selection_imagealtText, selection_image_width, selection_image_height, link_pid')
	),
	'palettes' => array(
		'1' => Array('showitem' => 'starttime, endtime')
	)
);