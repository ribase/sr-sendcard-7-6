<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2003-2012 Stanislas Rolland <typo3(arobas)sjbr.ca>
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
$GLOBALS['LANG']->includeLLFile(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('sr_sendcard') . 'Resources/Private/Language/locallang_mod.xlf');
// This checks permissions and exits if the users has no permission for entry.
$GLOBALS['BE_USER']->modAccess($MCONF, 1);

	// Make instance:
$GLOBALS['SOBE'] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('tx_srsendcard_statistics');
$GLOBALS['SOBE']->init();

	// Include files?
reset($GLOBALS['SOBE']->include_once);
while (list(, $INC_FILE) = each($GLOBALS['SOBE']->include_once)) {
	include_once($INC_FILE);
}

$GLOBALS['SOBE']->main();
$GLOBALS['SOBE']->printContent();
?>
