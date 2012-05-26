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
		// The backReference to the parent cObj
	var $cObj;
		// Same as class name
	var $prefixId = 'tx_srsendcard_pi1';
		// Path to this script relative to the extension directory
	var $scriptRelPath = 'pi1/class.tx_srsendcard_pi1_deferred.php';
		// The extension key
	var $extKey = 'sr_sendcard';
		// Configuration array
	var $conf = array();
		 // Default charset to be used in html emails
	var $charset = 'utf-8';
	/**
	 * Main function: send all the cards
	 *
	 * @param array $conf: the TS configuration array
	 * @return void
	 */
	function main ($conf) {
			// Invoke parent constructor
		if (method_exists($this, '__construct')) {
			parent::__construct();
		} else {
				// Before TYPO3 4.6+ and PHP 5.3+
			parent::tslib_pibase();
		}
		$this->conf = $conf;
		$this->pi_loadLL();
		$tableName = 'tx_srsendcard_sendcard';
			// Disable caching
		$this->pi_USER_INT_obj = FALSE;
		$GLOBALS['TSFE']->set_no_cache();
			// Load template
		$this->templateCode = $this->fileResource($this->conf['templateFile']);
			// Setting CSS style markers if required
		if ($this->conf['enableHTMLMail']) {
			$globalMarkerArray['###CSS_STYLES###'] = $this->fileResource($this->conf['HTMLMailCSS']);
		}
		$this->templateCode = $this->cObj->substituteMarkerArray($this->templateCode, $globalMarkerArray);
			// Initialize markers arrays
		$wrappedSubpartArray = array();
		$subpartArray = array();
		$markerArray = array();
			// Get the cards it is time to send
		$time = time();
		$whereClause = 'emailsent = 0';
		$whereClause .= ' AND send_time < ' . intval($time);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			$tableName,
			$whereClause
			);
			// Create mail object
		$mail = t3lib_div::makeInstance('tx_srsendcard_email', $this);
			// Send the cards
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
			$mail->sendEmail($emailData, 'TEMPLATE_EMAIL_CARD_SENT');
		}
			// Mark cards sent
		$whereClause = 'send_time < ' . intval($time) . ' AND emailsent = 0';
		$fields_values = array();
		$fields_values['emailsent'] = '1';
		$res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
			$tableName,
			$whereClause,
			$fields_values
			);
			// Cards were sent
		return TRUE;
	}

	/**
	 * From the 'salutationswitcher' extension.
	 *
	 * @author	Oliver Klee <typo-coding@oliverklee.de>
	 */
	 	// List of allowed suffixes
	public $allowedSuffixes = array('formal', 'informal');
	
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
	public function pi_getLL($key, $alt = '', $hsc = FALSE) {
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
	protected function fileResource($fName) {
		$content = '';
		$incFile = PATH_site . $GLOBALS['TSFE']->tmpl->getFileName($fName);
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