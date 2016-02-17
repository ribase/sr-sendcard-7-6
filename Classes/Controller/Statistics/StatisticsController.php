<?php
namespace SJBR\SrSendcard\Controller\Statistics;

/*
 *  Copyright notice
 *
 *  (c) 2003-2016 Stanislas Rolland <typo3(arobas)sjbr.ca>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 */

use TYPO3\CMS\Backend\Module\BaseScriptClass;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
* Module 'Sent Cards Statistics' for the 'sr_sendcard' extension.
*
*/
class StatisticsController extends BaseScriptClass
{

    /**
     * @var array
     */
    public $pageinfo;

    /**
     * Document Template Object
     *
     * @var \TYPO3\CMS\Backend\Template\DocumentTemplate
     * @deprecated
     */
    public $doc;

    /**
     * @var \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
     */
    protected $backendUser;

    /**
     * @var \TYPO3\CMS\Lang\LanguageService
     */
    protected $languageService;

    /**
     * The name of the module
     *
     * @var string
     */
    protected $moduleName = 'web_txsrsendcardM1';

	/**
	 * @var string
	 */
	protected $moduleTemplate = 'EXT:sr_sendcard/Resources/Private/Templates/Statistics.html';

	/**
	 * @var string
	 */
	protected $styleSheetFile2 = '';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->languageService = $GLOBALS['LANG'];
        $this->languageService->includeLLFile('EXT:sr_sendcard/Resources/Private/Language/locallang_mod.xlf');

        $this->backendUser = $GLOBALS['BE_USER'];
        $this->getBackendUser()->modAccess($GLOBALS['MCONF'], true);

        $this->MCONF = array(
            'name' => $this->moduleName,
        );
        $this->styleSheetFile2 = ExtensionManagementUtility::extRelPath('sr_sendcard') . 'Resources/Public/StyleSheets/Statistics.css';
    }

	/**
	 * Adds items to the->MOD_MENU array. Used for the function menu selector.
	 *
	 * @return void
	 */
	public function menuConfig()
	{
		$this->MOD_MENU = array(
			'function' => array(
				'1' => $this->languageService->getLL('function1'),
				'2' => $this->languageService->getLL('function2'),
				'3' => $this->languageService->getLL('function3'),
				)
			);
		parent::menuConfig();
	}

	/**
	 * Main function of the module. Write the content to $this->content
	 * If you chose "web" as main module, you will need to consider the $this->id parameter which will contain the uid-number of the page clicked in the page tree
	 *
	 * @return void
	 */
	public function main()
	{
		// Access check!
		// The page will show only if there is a valid page and if this page may be viewed by the user
		$this->pageinfo = BackendUtility::readPageAccess($this->id, $this->perms_clause);
		$access = is_array($this->pageinfo) ? 1 : 0;

		$this->doc = GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\Template\\DocumentTemplate');
		$this->doc->backPath = $GLOBALS['BACK_PATH'];
		$this->doc->setModuleTemplate($this->moduleTemplate);
		$this->doc->styleSheetFile2 = $this->styleSheetFile2;

		if (($this->id && $access) || ($GLOBALS['BE_USER']->user['admin'] && !$this->id)) {
			// Draw the header.
			$this->doc->form = $this->getFormTag();
			// JavaScript
			$this->doc->postCode = $this->doc->wrapScriptTags('if (top.fsMod) top.fsMod.recentIds["web"] = ' . (int)$this->id . ';');
			$this->doc->getContextMenuCode();
			$this->extObjContent();
			// Markers
			$this->markers = array(
				'FUNC_MENU' => BackendUtility::getFuncMenu(
					$this->id,
					'SET[function]',
					$this->MOD_SETTINGS['function'],
					$this->MOD_MENU['function']
				),
				'CONTENT' => $this->doc->section(
					$this->languageService->getLL('title'),
					$this->moduleContent(),
					0,
					1
				),
				'VIEW' => ''
			);
			// ShortCut
			if ($this->backendUser->mayMakeShortcut()) {
				$this->markers['SHORTCUT'] = $this->doc->makeShortcutIcon('id', implode(',', array_keys($this->MOD_MENU)), $this->MCONF['name']);
			} else {
				$this->markers['SHORTCUT'] = '';
			}
			$docHeaderButtons = array(
				'VIEW' => $this->markers['VIEW'],
				'SHORTCUT' => $this->markers['SHORTCUT']
			);

			// Build the <body> for the module
			$this->content = $this->doc->moduleBody($this->pageinfo, $docHeaderButtons, $this->markers);
		} else {
			// If no access or if ID == zero
			$this->content = $this->doc->header($this->languageService->getLL('title'));
		}
		// Renders the module page
		$this->content = $this->doc->render($this->languageService->getLL('title'), $this->content);
	}

	/**
	 * Prints out the module HTML
	 *
	 * @return void
	 */
	public function printContent()
	{
		$this->content = $this->doc->insertStylesAndJS($this->content);
		echo $this->content;
	}
	
	/**
	 * Generates the module content
	 *
	 * @return void
	 */
	public function moduleContent()
	{
		$content = '';
		// Get the sent cards
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'caption,time_created',
			'tx_srsendcard_domain_model_sendcard',
			'1=1',
			'',
			'caption'
			);
		$cardsCaption = array();
		$cardsCount = array();
		$cardsDate = array();
		$index = -1;
		$lastCaption = '';
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			if ($lastCaption != $row['caption']) {
				$index++;
				$cardsCaption[$index] = $row['caption'];
				$cardsDate[$index] = $row['time_created'];
				$cardsCount[$index] = 1;
			} else {
				$cardsCount[$index] = $cardsCount[$index]+1;
				if ($cardsDate[$index] < $row['time_created']) {
					$cardsDate[$index] = $row['time_created'];
				};
			}
			$lastCaption = $row['caption'];
		}
		
		// Sort and adjust table titles according to selected function
		switch ((string)$this->MOD_SETTINGS['function']) {
			case 1:
				/* Sorted in alphabetial order*/
				/*Ouput */
				$index = 0;
				$content = '<table class="statistics"><tr><th class="selected">' . $this->languageService->getLL('cardTitle') . '</th><th>' . $this->languageService->getLL('cardTimes') . '</th><th>' . $this->languageService->getLL('cardLastTime') . '</th></tr>';
				break;
				
			case 2:
				/* Sorted by most recently sent*/
				array_multisort($cardsDate, SORT_DESC, SORT_NUMERIC, $cardsCaption, SORT_STRING, $cardsCount, SORT_DESC, SORT_NUMERIC);
				
				/*Output */
				$index = 0;
				$content = '<table class="statistics"><tr><th>' . $this->languageService->getLL('cardTitle') . '</th><th>' . $this->languageService->getLL('cardTimes') . '</th><th class="selected">' . $this->languageService->getLL('cardLastTime') . '</th></tr>';
				break;
				
			case 3:
				/* Sorted by frequency*/
				array_multisort($cardsCount, SORT_DESC, SORT_NUMERIC, $cardsDate, SORT_DESC, SORT_NUMERIC, $cardsCaption, SORT_STRING);
				
				/*Output */
				$index = 0;
				$content = '<table class="statistics"><tr><th>' . $this->languageService->getLL('cardTitle') . '</th><th class="selected">' . $this->languageService->getLL('cardTimes') . '</th><th>' . $this->languageService->getLL('cardLastTime') . '</th></tr>';
				break;
		}
			 
			// Display the sorted table rows
		while ($cardsCaption[$index]) {
			$date = getdate($cardsDate[$index]);
			$date_output = substr('0'.$date[mday], -2).'.'.substr('0'.$date[mon], -2).'.'.$date[year];
			$content .= '<tr><td>'.$cardsCaption[$index].'</td><td>'.$cardsCount[$index].'</td><td>'.$date_output.'</td></tr>';
			$index++;
		}
		$content .= '</table>';
		return $content;
	}

	/**
	 * Returns a form tag with the current configured params
	 *
	 * @param string $name Name of the form tag
	 * @return string HTML form tag
	 */
	protected function getFormTag($name='editform')
	{
		$formAction = GeneralUtility::linkThisScript();
		return '<form action="' . htmlspecialchars($formAction) . '" method="post" name="' . $name . '" id="' . $name . '" autocomplete="off" enctype="' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['form_enctype'] . '">';
	}
}