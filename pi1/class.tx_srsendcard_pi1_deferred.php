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
 *  Deferred Card Delivery class for the 'sr_sendcard' extension.
 *  Invoked from the cardmailer	 *
 *  @author Stanislas Rolland <typo3(arobas)sjbr.ca>
 *
 *  Credits:
 *  The general idea of this plugin is based on the sendcard php script authored by Peter Bowyer.
 *  The plugin is a complete remake within the Typo3 framework,
 *  leaving little resemblance with the code of the original sendcard script that inspired it.
 *  Yet, this plugin is made available in the Typo3 public online extension repository with the agreement of Peter Bowyer.
 *
 *  See also sendcard:
 *  Copyright Peter Bowyer <peter@sendcard.org> 2000, 2001, 2002
 *  This script is released under the Artistic License
 */
class tx_srsendcard_pi1_deferred extends tslib_pibase {
	var $cObj; // The backReference to the mother cObj object set at call time
	var $prefixId = 'tx_srsendcard_pi1'; // Same as class name
	var $scriptRelPath = 'pi1/class.tx_srsendcard_pi1_deferred.php'; // Path to this script relative to the extension dir.
	var $extKey = 'sr_sendcard'; // The extension key.
	var $conf = array();
		 // Default charset to be used in html emails
	var $charset = 'utf-8';
	/**
	 * Main function: send all the cards
	 *
	 * @param array  $conf: the TS configuration array
	 * @return void
	 */
	 
	function main($conf) {
		$this->conf = $conf;
		$this->tslib_pibase();
		$this->pi_loadLL();
		$tbl_name = 'tx_srsendcard_sendcard';
		$this->pi_USER_INT_obj = 1; // Disable caching
		$GLOBALS['TSFE']->set_no_cache();
		
			// Load template
		$this->templateCode = $this->fileResource($this->conf['templateFile']);
		
			// Setting CSS style markers if required
		if ($this->conf['enableHTMLMail']) {
			$globalMarkerArray['###CSS_STYLES###'] = $this->fileResource($this->conf['HTMLMailCSS']);
		}
		
		$this->templateCode = $this->cObj->substituteMarkerArray($this->templateCode, $globalMarkerArray);
		
		$wrappedSubpartArray = array();
		$subpartArray = array();
		$markerArray = array();
		
		$time = time();
		
		/*
		 * Send the cards
		 */
		$whereClause = 'emailsent = 0';
		$whereClause .= ' AND send_time < ' . intval($time); //can't wait to test!!
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			$tbl_name,
			$whereClause
			);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$emailData['from_name'] = $row['fromwho'];
			$emailData['from_email'] = $row['from_email'];
			$emailData['to_name'] = $row['towho'];
			$emailData['to_email'] = $row['to_email'];
			$emailData['card_url'] = $row['card_url'];
			
				// Setting language and charsets
			$GLOBALS['TSFE']->config['config']['language'] = $row['language'];
			$GLOBALS['TSFE']->initLLvars();
			$this->LLkey = $row['language'];
			$this->charset = $row['charset'];
			
			$this->sendEmail($emailData, 'TEMPLATE_EMAIL_CARD_SENT');
		}
			
			// Mark cards sent
		$whereClause = 'send_time < ' . intval($time) . ' AND emailsent = 0';
		$fields_values = array();
		$fields_values['emailsent'] = '1';
		$res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
			$tbl_name,
			$whereClause,
			$fields_values
			);
		
	}
	
	/**
	 * Get the content of a file  resource using the full path to the file resource because we are a cron job
	 *
	 * @param	string	$emailData: content of the mail
	 * @param	string	$emailTemplateKey: key of the email html template
	 * @return	void
	 */
	 
	function sendEmail($emailData, $emailTemplateKey) {		
			// Get templates
		$subpart = $this->cObj->getSubpart($this->templateCode, '###'.$emailTemplateKey.'###');
		if ($this->conf['enableHTMLMail']) {
			$HTMLSubpart = $this->cObj->getSubpart($this->templateCode, '###'.$emailTemplateKey.'_HTML'.'###');
		}
		
			// Set markers
		$markerArray['###EMAIL_CARDSENT_SUBJECT1###'] = $this->pi_getLL('email_cardSent_subject1');
		$markerArray['###EMAIL_CARDSENT_SUBJECT2###'] = $this->pi_getLL('email_cardSent_subject2');
		$markerArray['###EMAIL_CARDSENT_TITLE1###'] = $this->pi_getLL('email_cardSent_title1');
		$markerArray['###EMAIL_CARDSENT_TITLE2###'] = $this->pi_getLL('email_cardSent_title2');
		$markerArray['###EMAIL_CARDSENT_TEXT1###'] = $this->pi_getLL('email_cardSent_text1');
		$markerArray['###EMAIL_CARDSENT_TEXT2###'] = $this->pi_getLL('email_cardSent_text2');
		$markerArray['###EMAIL_CARDSENT_TEXT3###'] = $this->pi_getLL('email_cardSent_text3');
		$markerArray['###EMAIL_CARDSENT_TEXT4###'] = $this->pi_getLL('email_cardSent_text4');
		$markerArray['###EMAIL_CARDVIEWED_SUBJECT1###'] = $this->pi_getLL('email_cardViewed_subject1');
		$markerArray['###EMAIL_CARDVIEWED_SUBJECT2###'] = $this->pi_getLL('email_cardViewed_subject2');
		$markerArray['###EMAIL_CARDVIEWED_TITLE1###'] = $this->pi_getLL('email_cardViewed_title1');
		$markerArray['###EMAIL_CARDVIEWED_TITLE2###'] = $this->pi_getLL('email_cardViewed_title2');
		$markerArray['###EMAIL_CARDVIEWED_TEXT1###'] = $this->pi_getLL('email_cardViewed_text1');
		$markerArray['###EMAIL_CARDVIEWED_TEXT2###'] = $this->pi_getLL('email_cardViewed_text2');
		$markerArray['###EMAIL_CARDVIEWED_TEXT3###'] = $this->pi_getLL('email_cardViewed_text3');
		$markerArray['###EMAIL_SIGNATURE###'] = $this->pi_getLL('email_signature');
		$markerArray['###TO_NAME###'] = $emailData['to_name'];
		$markerArray['###TO_EMAIL###'] = $emailData['to_email'];
		$markerArray['###FROM_EMAIL###'] = $emailData['from_email'];
		$markerArray['###FROM_NAME###'] = $emailData['from_name'];
		$markerArray['###CARD_URL###'] = $emailData['card_url'];
		$markerArray['###DATE###'] = $emailData['date'];
		$markerArray['###SITE_NAME###'] = $this->conf['siteName'];
		$markerArray['###SITE_WWW###'] = t3lib_div::getIndpEnv('TYPO3_HOST_ONLY');
		$markerArray['###SITE_URL###'] = t3lib_div::getIndpEnv('TYPO3_SITE_URL');
		$markerArray['###SITE_EMAIL###'] = $this->conf['siteEmail'];
		$markerArray['###CHARSET###'] = $GLOBALS['TSFE']->metaCharset;

			// Substitute in template
		$content = $this->cObj->substituteMarkerArrayCached($subpart, $markerArray, $subpartArray, $wrappedSubpartArray);
		if ($this->conf['enableHTMLMail']) {
			$content = $GLOBALS['TSFE']->csConvObj->conv($content, $GLOBALS['TSFE']->renderCharset, $GLOBALS['TSFE']->metaCharset);
			$HTMLContent = $this->cObj->substituteMarkerArrayCached($HTMLSubpart, $markerArray, $subpartArray, $wrappedSubpartArray);
			$HTMLContent = $GLOBALS['TSFE']->csConvObj->conv($HTMLContent, $GLOBALS['TSFE']->renderCharset, $GLOBALS['TSFE']->metaCharset,1);
		} else {
			$content = $GLOBALS['TSFE']->csConvObj->conv($content, $GLOBALS['TSFE']->renderCharset, ($GLOBALS['TSFE']->config['config']['notification_email_charset'] ? $GLOBALS['TSFE']->config['config']['notification_email_charset'] : 'utf-8'));
		}

			// Set subject, content and headers
		$headers = array();
		$headers[] = 'FROM: '.$this->conf['siteName'].' <'.$this->conf['siteEmail'].'>';
		list($subject, $plain_message) = explode(chr(10), trim($content), 2);
		if ($this->conf['enableHTMLMail']) {
			$parts = spliti('<title>|</title>', $HTMLContent, 3);
			$subject = trim($parts[1]) ? strip_tags(trim($parts[1])) : 'Send-A-Card message';
		}

		if ($this->conf['enableHTMLMail']) {
			$Typo3_htmlmail = t3lib_div::makeInstance('t3lib_htmlmail');
			$Typo3_htmlmail->start();
			$Typo3_htmlmail->charset = $GLOBALS['TSFE']->metaCharset;
			$Typo3_htmlmail->useQuotedPrintable();
			if($this->conf['forceBase64Encoding']) {
				$Typo3_htmlmail->useBase64();
			}
			$Typo3_htmlmail->mailer = 'Typo3 HTMLMail';
			$Typo3_htmlmail->subject = $subject;
			$Typo3_htmlmail->from_email = $this->conf['siteEmail'];
			$Typo3_htmlmail->from_name = $this->conf['siteName'];
			$Typo3_htmlmail->replyto_email = $this->conf['siteEmail'];
			$Typo3_htmlmail->replyto_name = $this->conf['siteName'];
			$Typo3_htmlmail->organisation = '';
			$Typo3_htmlmail->priority = 3;

				// HTML
			if ($this->conf['enableHTMLMail'] && trim($HTMLContent)) {
				$Typo3_htmlmail->theParts['html']['content'] = $HTMLContent;
				$Typo3_htmlmail->theParts['html']['path'] = '';
				$Typo3_htmlmail->extractMediaLinks();
				$Typo3_htmlmail->extractHyperLinks();
				$Typo3_htmlmail->fetchHTMLMedia();
				$Typo3_htmlmail->substMediaNamesInHTML(0); // 0 = relative
				$Typo3_htmlmail->substHREFsInHTML();
				$Typo3_htmlmail->setHTML($Typo3_htmlmail->encodeMsg($Typo3_htmlmail->theParts['html']['content']));
			}

				// PLAIN
			$Typo3_htmlmail->addPlain($plain_message);
			$Typo3_htmlmail->setHeaders();
			$Typo3_htmlmail->setContent();
			$Typo3_htmlmail->setRecipient($emailData['to_email']);
			$Typo3_htmlmail->sendtheMail();
		} else {
			$this->cObj->sendNotifyEmail($content, $emailData['to_email'], '', $this->conf['siteEmail'], $this->conf['siteName'], '');
		}
	}
	
	/**
	 * From the 'salutationswitcher' extension.
	 *
	 * @author	Oliver Klee <typo-coding@oliverklee.de>
	 */
	    // list of allowed suffixes
	var $allowedSuffixes = array('formal', 'informal');
	
	/**
	 * Returns the localized label of the LOCAL_LANG key, $key
	 * In $this->conf['salutation'], a suffix to the key may be set (which may be either 'formal' or 'informal').
	 * If a corresponding key exists, the formal/informal localized string is used instead.
	 * If the key doesn't exist, we just use the normal string.
	 *
	 * Example: key = 'greeting', suffix = 'informal'. If the key 'greeting_informal' exists, that string is used.
	 * If it doesn't exist, we'll try to use the string with the key 'greeting'.
	 *
	 * Notice that for debugging purposes prefixes for the output values can be set with the internal vars ->LLtestPrefixAlt and ->LLtestPrefix
	 *
	 * @param    string        The key from the LOCAL_LANG array for which to return the value.
	 * @param    string        Alternative string to return IF no value is found set for the key, neither for the local language nor the default.
	 * @param    boolean        If true, the output label is passed through htmlspecialchars()
	 * @return    string        The value from LOCAL_LANG.
	 */
	function pi_getLL($key, $alt = '', $hsc = FALSE) {
			// If the suffix is allowed and we have a localized string for the desired salutation, we'll take that.
		if (isset($this->conf['salutation']) && in_array($this->conf['salutation'], $this->allowedSuffixes, 1)) {
			$expandedKey = $key.'_'.$this->conf['salutation'];
			if (isset($this->LOCAL_LANG[$this->LLkey][$expandedKey])) {
				$key = $expandedKey;
			}
		}
		return parent::pi_getLL($key, $alt, $hsc);
	}
	
	/**
	 * Get the content of a file resource using the full path to the file resource because we are a cron job
	 *
	 * @param	string	$fName: the name of the file
	 * @return	string	the content of the file
	 */
	function fileResource($fName) {
		$content = '';
		$incFile = PATH_site.$GLOBALS['TSFE']->tmpl->getFileName($fName);
		if ($incFile) {
			$content = t3lib_div::getURL($incFile);
		}
		return $content;
	}
	
}
if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sr_sendcard/pi1/class.tx_srsendcard_pi1_deferred.php']) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sr_sendcard/pi1/class.tx_srsendcard_pi1_deferred.php']);
}
?>