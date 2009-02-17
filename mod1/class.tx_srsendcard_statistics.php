<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2003-2006 Stanislas Rolland <typo3(arobas)sjbr.ca>
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
* Module 'Sent Cards Statistics' for the 'sr_sendcard' extension.
*
* @author Stanislas Rolland <typo3(arobas)sjbr.ca>
*/

require_once(PATH_t3lib.'class.t3lib_scbase.php');

class tx_srsendcard_statistics extends t3lib_SCbase {
	var $pageinfo;

	/**
	 * Initialize module
	 *
	 * @return void
	 */
	function init() {
		global $AB, $BE_USER, $LANG, $BACK_PATH, $TCA_DESCR, $TCA, $HTTP_GET_VARS, $HTTP_POST_VARS, $CLIENT, $TYPO3_CONF_VARS;
		parent::init();
	}

	/**
	 * Adds items to the->MOD_MENU array. Used for the function menu selector.
	 *
	 * @return void
	 */
	function menuConfig() {
		global $LANG;
		$this->MOD_MENU = Array (
			'function' => Array (
				'1' => $LANG->getLL('function1'),
				'2' => $LANG->getLL('function2'),
				'3' => $LANG->getLL('function3'),
				)
			);
		parent::menuConfig();
	}

		// If you chose "web" as main module, you will need to consider the $this->id parameter which will contain the uid-number of the page clicked in the page tree
	/**
	 * Main function of the module. Write the content to $this->content
	 *
	 * @return void
	 */
	function main() {
		global $AB, $BE_USER, $LANG, $BACK_PATH, $TCA_DESCR, $TCA, $HTTP_GET_VARS, $HTTP_POST_VARS, $CLIENT, $TYPO3_CONF_VARS;
		
			// Access check!
			// The page will show only if there is a valid page and if this page may be viewed by the user
		$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id, $this->perms_clause);
		$access = is_array($this->pageinfo) ? 1 : 0;
		
		if (($this->id && $access) || ($BE_USER->user['admin'] && !$this->id)) {
			
				// Draw the header.
			$this->doc = t3lib_div::makeInstance('mediumDoc');
			$this->doc->backPath = $BACK_PATH;
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
					if (top.theMenu) top.theMenu.recentuid = '.intval($this->id).';
					// -->
					/*]]>*/
					</script>
				';
			
			$headerSection = $this->doc->getHeader('pages', $this->pageinfo, $this->pageinfo['_thePath']).'<br>'.$LANG->php3Lang['labels']['path'].': '.t3lib_div::fixed_lgd_pre($this->pageinfo['_thePath'], 50);
			
			$this->content .= $this->doc->startPage($LANG->getLL('title'));
			$this->content .= $this->doc->header($LANG->getLL('title'));
			$this->content .= $this->doc->spacer(5);
			$this->content .= $this->doc->section('', $this->doc->funcMenu($headerSection, t3lib_BEfunc::getFuncMenu($this->id, 'SET[function]', $this->MOD_SETTINGS['function'], $this->MOD_MENU['function'])));
			$this->content .= $this->doc->divider(5);
			
				// Render content:
			$this->moduleContent();
			
				// ShortCut
			if ($BE_USER->mayMakeShortcut()) {
				$this->content .= $this->doc->spacer(20).$this->doc->section('', $this->doc->makeShortcutIcon('id', implode(',', array_keys($this->MOD_MENU)), $this->MCONF['name']));
			}
			
			$this->content .= $this->doc->spacer(10);
		} else {
				// If no access or if ID == zero
			$this->doc = t3lib_div::makeInstance('mediumDoc');
			$this->doc->backPath = $BACK_PATH;
				 
			$this->content .= $this->doc->startPage($LANG->getLL('title'));
			$this->content .= $this->doc->header($LANG->getLL('title'));
			$this->content .= $this->doc->spacer(5);
			$this->content .= $this->doc->spacer(10);
		}
	}
	
	/**
	 * Prints out the module HTML
	 *
	 * @return void
	 */
	function printContent() {
		global $SOBE;
		
		$this->content .= $this->doc->middle();
		$this->content .= $this->doc->endPage();
		echo $this->content;
	}
	
	/**
	 * Generates the module content
	 *
	 * @return void
	 */
	function moduleContent() {
		global $LANG, $TYPO3_DB;
		
			// Get the sent cards
		$res = $TYPO3_DB->exec_SELECTquery(
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
		while ($row = $TYPO3_DB->sql_fetch_assoc($res)) {
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
		switch((string)$this->MOD_SETTINGS['function']) {
			case 1:
				/* Sorted in alphabetial order*/
				/*Ouput */
				$index = 0;
				$content = '<table style="border-style: solid; border-width: 1px;"><tr><td style="font-weight: bold; color: blue;">' . $LANG->getLL('cardTitle') . '</td><td style="font-weight: bold;">' . $LANG->getLL('cardTimes') . '</td><td style="font-weight: bold;">' . $LANG->getLL('cardLastTime') . '</td></tr>';
				break;
				
			case 2:
				/* Sorted by most recently sent*/
				array_multisort($cardsDate, SORT_DESC, SORT_NUMERIC, $cardsCaption, SORT_STRING, $cardsCount, SORT_DESC, SORT_NUMERIC);
				
				/*Output */
				$index = 0;
				$content = '<table style="border-style: solid; border-width: 1px;"><tr><td style="font-weight: bold;">' .$LANG->getLL('cardTitle') . '</td><td style="font-weight: bold;">' . $LANG->getLL('cardTimes') . '</td><td style="font-weight: bold; color: blue;">' . $LANG->getLL('cardLastTime') . '</td></tr>';
				break;
				
			case 3:
				/* Sorted by frequency*/
				array_multisort($cardsCount, SORT_DESC, SORT_NUMERIC, $cardsDate, SORT_DESC, SORT_NUMERIC, $cardsCaption, SORT_STRING);
				
				/*Output */
				$index = 0;
				$content = '<table style="border-style: solid; border-width: 1px;"><tr><td style="font-weight: bold;">' . $LANG->getLL('cardTitle') . '</td><td style="font-weight: bold; color: blue;">' . $LANG->getLL('cardTimes') . '</td><td style="font-weight: bold;">' . $LANG->getLL('cardLastTime') . '</td></tr>';
				break;
		}
			 
			// Display the sorted table rows
		while ($cardsCaption[$index]) {
			$date = getdate($cardsDate[$index]);
			$date_output = substr('0'.$date[mday], -2).'.'.substr('0'.$date[mon], -2).'.'.$date[year];
			$content .= '<tr><td>'.$cardsCaption[$index].'</td><td style="text-align: right;">'.$cardsCount[$index].'</td><td style="text-align: right;">'.$date_output.'</td></tr>';
			$index++;
		}
		$content .= '</table>';
		$this->content .= $this->doc->section($LANG->getLL('title'), $content, 0, 1);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sr_sendcard/mod1/class.tx_srsendcard_statistics.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sr_sendcard/mod1/class.tx_srsendcard_statistics.php']);
}

?>
