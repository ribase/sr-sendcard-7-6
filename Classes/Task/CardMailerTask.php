<?php
namespace SJBR\SrSendcard\Task;

/*
 *  Copyright notice
 *
 *  (c) 2012-2016 Stanislas Rolland <typo3(arobas)sjbr.ca>
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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * This is the card mailer task of extension Send-A-Card (sr_sendcard) that sends the deferred cards
 */
class CardMailerTask extends AbstractTask
{
	/**
	 * Page id on which the card will be viewed
	 *
	 * @var integer $viewCardPid
	 */
	public $viewCardPid = 0;

	/**
	 * Invokes the deferred card mailing class
	 *
	 */
	public function execute()
	{
		$success = false;
		if (!empty($this->viewCardPid)) {
			$GLOBALS['TT'] = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\TimeTracker\\TimeTracker');
			$GLOBALS['TSFE'] = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController', $GLOBALS['TYPO3_CONF_VARS'], $this->viewCardPid, '0', 1, '', '', '', '');
			$GLOBALS['TSFE']->connectToDB();
			$GLOBALS['TSFE']->initFEuser();
			$GLOBALS['TSFE']->fetch_the_id();
			$GLOBALS['TSFE']->getPageAndRootline();
			$GLOBALS['TSFE']->initTemplate();
			$GLOBALS['TSFE']->tmpl->getFileName_backPath = PATH_site;
			$GLOBALS['TSFE']->forceTemplateParsing = 1;
			$GLOBALS['TSFE']->getConfigArray();
			$sendingCards = GeneralUtility::makeInstance('SJBR\\SrSendcard\\Controller\\DeferredSendcardController');
			$sendingCards->cObj = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');
			$conf = $GLOBALS['TSFE']->tmpl->setup['plugin.'][$sendingCards->prefixId . '.'];
			$success = $sendingCards->main($conf);
		}
		return $success;
	}
}