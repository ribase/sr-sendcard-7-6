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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
* Module 'Sent Cards Statistics' for the 'sr_sendcard' extension.
*
*/
class StatisticsController extends BaseScriptClass
{
	protected $pageinfo;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->getLanguageService()->includeLLFile('EXT:sr_sendcard/Resources/Private/Language/locallang_mod.xlf');
		$this->getBackendUser()->modAccess($GLOBALS['MCONF'], true);
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
				'1' => $GLOBALS['LANG']->getLL('function1'),
				'2' => $GLOBALS['LANG']->getLL('function2'),
				'3' => $GLOBALS['LANG']->getLL('function3'),
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

		if (($this->id && $access) || ($GLOBALS['BE_USER']->user['admin'] && !$this->id)) {
			
			// Draw the header.
			$this->doc->form = '<form action="" method="POST">';
			
			// JavaScript
			$this->doc->JScode = '
				<script type="text/javascript">
					/*<![CDATA[*/
					<!--
					script_ended = 0;
					function jumpToUrl(URL) {
					document.location = URL;
					}
					// -->
					/*]]>*/
					</script>
				';
			$this->doc->postCode = '
				<script type="text/javascript">
					/*<![CDATA[*/
					<!--
					script_ended = 1;
					if (top.theMenu) top.theMenu.recentuid = ' . (int) $this->id . ';
					// -->
					/*]]>*/
					</script>
				';
			
			$headerSection = $this->doc->getHeader('pages', $this->pageinfo, $this->pageinfo['_thePath']).'<br>'.$GLOBALS['LANG']->php3Lang['labels']['path'] . ': ' . GeneralUtility::fixed_lgd_cs($this->pageinfo['_thePath'], -50);
			$content .= $this->doc->header($GLOBALS['LANG']->getLL('title'));
			$content .= $this->doc->spacer(5);
			$content .= $this->doc->section('', $this->doc->funcMenu($headerSection, BackendUtility::getFuncMenu($this->id, 'SET[function]', $this->MOD_SETTINGS['function'], $this->MOD_MENU['function'])));
			$content .= $this->doc->divider(5);
			// Render module content
			$content .= $this->doc->section($GLOBALS['LANG']->getLL('title'), $this->moduleContent(), 0, 1);
			// ShortCut
			if ($GLOBALS['BE_USER']->mayMakeShortcut()) {
				$content .= $this->doc->spacer(20).$this->doc->section('', $this->doc->makeShortcutIcon('id', implode(',', array_keys($this->MOD_MENU)), $this->MCONF['name']));
			}
			$content .= $this->doc->spacer(10);
			$this->content = $this->doc->render($GLOBALS['LANG']->getLL('title'), $content);
		} else {
			// If no access or if ID == zero
			$content = $this->doc->header($GLOBALS['LANG']->getLL('title'));
			$this->content = $this->doc->render($GLOBALS['LANG']->getLL('title'), $content);
		}
	}

	/**
	 * Prints out the module HTML
	 *
	 * @return void
	 */
	public function printContent()
	{
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
			'tx_srsendcard_sendcard',
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
				$content = '<table style="border-style: none; margin-left: 5px;"><tr><td style="padding: 1px 3px; font-weight: bold; color: blue;">' . $GLOBALS['LANG']->getLL('cardTitle') . '</td><td style="padding: 1px 3px; font-weight: bold;">' . $GLOBALS['LANG']->getLL('cardTimes') . '</td><td style="padding: 1px 3px; font-weight: bold;">' . $GLOBALS['LANG']->getLL('cardLastTime') . '</td></tr>';
				break;
				
			case 2:
				/* Sorted by most recently sent*/
				array_multisort($cardsDate, SORT_DESC, SORT_NUMERIC, $cardsCaption, SORT_STRING, $cardsCount, SORT_DESC, SORT_NUMERIC);
				
				/*Output */
				$index = 0;
				$content = '<table style="border-style: none; margin-left: 5px;"><tr><td style="padding: 1px 3px; font-weight: bold;">' . $GLOBALS['LANG']->getLL('cardTitle') . '</td><td style="padding: 1px 3px; font-weight: bold;">' . $GLOBALS['LANG']->getLL('cardTimes') . '</td><td style="padding: 1px 3px; font-weight: bold; color: blue;">' . $GLOBALS['LANG']->getLL('cardLastTime') . '</td></tr>';
				break;
				
			case 3:
				/* Sorted by frequency*/
				array_multisort($cardsCount, SORT_DESC, SORT_NUMERIC, $cardsDate, SORT_DESC, SORT_NUMERIC, $cardsCaption, SORT_STRING);
				
				/*Output */
				$index = 0;
				$content = '<table style="border-style: none; margin-left: 5px;"><tr><td style="padding: 1px 3px; font-weight: bold;">' . $GLOBALS['LANG']->getLL('cardTitle') . '</td><td style="padding: 1px 3px; font-weight: bold; color: blue;">' . $GLOBALS['LANG']->getLL('cardTimes') . '</td><td style="padding: 1px 3px; font-weight: bold;">' . $GLOBALS['LANG']->getLL('cardLastTime') . '</td></tr>';
				break;
		}
			 
			// Display the sorted table rows
		while ($cardsCaption[$index]) {
			$date = getdate($cardsDate[$index]);
			$date_output = substr('0'.$date[mday], -2).'.'.substr('0'.$date[mon], -2).'.'.$date[year];
			$content .= '<tr><td style="padding: 1px 3px;">'.$cardsCaption[$index].'</td><td style="padding: 1px 3px; text-align: right;">'.$cardsCount[$index].'</td><td style="padding: 1px 3px; text-align: right;">'.$date_output.'</td></tr>';
			$index++;
		}
		$content .= '</table>';
		return $content;
	}
}