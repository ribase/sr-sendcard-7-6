<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2003-2014 Stanislas Rolland <typo3(arobas)sjbr.ca>
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
* Class that adds the wizard icon for extension sr_sendcard
*
* @author Stanislas Rolland <typo3(arobas)sjbr.ca>
*/
class tx_srsendcard_pi1_wizicon {
	/**
	 * Main function: get the wizard items for the extension
	 *
	 * @param array  Wizard items
	 * @return array  Wizard items
	 */
	public function proc($wizardItems) {
		$GLOBALS['LANG']->includeLLFile(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('sr_sendcard') . 'Resources/Private/Language/locallang.xlf');

		$wizardItems['plugins_tx_srsendcard_pi1'] = array(
			'icon' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('sr_sendcard') . 'Resources/Public/Images/moduleicon.gif',
			'title' => $GLOBALS['LANG']->getLL('pi1_title'),
			'description' => $GLOBALS['LANG']->getLL('pi1_plus_wiz_description'),
			'params' => '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=sr_sendcard_pi1'
			);
		return $wizardItems;
	}
}
