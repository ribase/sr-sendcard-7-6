<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Stanislas Rolland <typo3(arobas)sjbr.ca>
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Email functions of the Send-A-Card (sr_sendcard) extension.
 *
 * $Id: $
 *
 * @author	Stanislas Rolland <typo3(arobas)sjbr.ca>
 *
 */
class tx_srsendcard_email {
	protected $templateCode;
	protected $cObj;
	protected $conf;
	protected $siteUrl;
	protected $pibase;

	/**
	 * Constructor
	 *
	 * @param tslib_pibase $pibase
	 */
	public function __construct ($pibase) {
		$this->templateCode = $pibase->templateCode;
		$this->cObj = $pibase->cObj;
		$this->conf = $pibase->conf;
		$this->siteUrl = $pibase->siteUrl;
		$this->pibase = $pibase;
	}

	/**
	 * Send an email message
	 *
	 * @param array $emailData: email data variables
	 * @param string $emailTemplateKey: template key
	 * @return void
	 */
	function sendEmail ($emailData, $emailTemplateKey) {
		$content = '';
		$htmlContent = '';
			// Get templates
		$subpart = $this->cObj->getSubpart($this->templateCode, '###' . $emailTemplateKey . '###');
		if ($this->conf['enableHTMLMail']) {
			$htmlSubpart = $this->cObj->getSubpart($this->templateCode, '###' . $emailTemplateKey . '_HTML' . '###');
		}
			// Set markers
		$markerArray = array();
			// Localize labels
		$labels = array(
			'email_cardSent_subject1', 'email_cardSent_subject2',
			'email_cardSent_title1', 'email_cardSent_title2',
			'email_cardSent_text1', 'email_cardSent_text2', 'email_cardSent_text3', 'email_cardSent_text4',
			'email_cardViewed_subject1', 'email_cardViewed_subject2',
			'email_cardViewed_title1', 'email_cardViewed_title2',
			'email_cardViewed_text1', 'email_cardViewed_text2', 'email_cardViewed_text3',
			'email_signature'
		);
		foreach ($labels as $label) {
			$markerArray['###' . strtoupper($label) . '###'] = $this->pibase->pi_getLL($label);
		}
			// Set data markers
		$dataMarkers = array(
			'to_name', 'to_email',
			'from_email', 'from_name',
			'card_url',
			'date'
		);
		foreach ($dataMarkers as $dataMarker) {
			$markerArray['###' . strtoupper($dataMarker) . '###'] = $emailData[$dataMarker];
		}
		$markerArray['###SITE_NAME###'] = $this->conf['siteName'];
		$markerArray['###SITE_WWW###'] = t3lib_div::getIndpEnv('TYPO3_HOST_ONLY');
		$markerArray['###SITE_URL###'] = $this->siteUrl;
		$markerArray['###SITE_EMAIL###'] = $this->conf['siteEmail'];
		$markerArray['###CHARSET###'] = $GLOBALS['TSFE']->renderCharset ?: 'utf-8';
			// Substitute markers in templates
		$content = $this->cObj->substituteMarkerArrayCached($subpart, $markerArray, array(), array());
		if ($this->conf['enableHTMLMail']) {
			$htmlContent = $this->cObj->substituteMarkerArrayCached($htmlSubpart, $markerArray, $subpartArray, $wrappedSubpartArray);
		}
			// Create mail
		$mail = t3lib_div::makeInstance('t3lib_mail_Message');
			// Set subject
		$defaultSubject = 'Send-A-Card message';
		if ($htmlContent) {
			$parts = preg_split('/<title>|<\\/title>/i', $htmlContent, 3);
			$subject = trim($parts[1]) ? strip_tags(trim($parts[1])) : $defaultSubject;
		} else {
			// First line is subject
			$parts = explode(LF, $content, 3);
			$subject = trim($parts[1]) ? trim($parts[1]) : $defaultSubject;
			$content = trim($parts[2]);
		}
		$mail->setSubject($subject);
			// Set 'from' addresses
		$fromName = str_replace('"', '\'', $this->conf['siteName']);
		if (preg_match('#[/\(\)\\<>,;:@\.\]\[\s]#', $fromName)) {
			$fromName = '"' . $fromName . '"';
		}
		$fromEmail = $this->conf['siteEmail'];
		$mail->setFrom(array($fromEmail => $fromName));
		$mail->setSender($fromEmail);
		$mail->setReturnPath($fromEmail);
		$mail->setReplyTo(array($fromEmail => $fromName));
		$mail->setPriority(3);
			// Set 'to' address
		$mail->setTo(array($emailData['to_email'] => $emailData['to_name']));
		if ($htmlContent) {
			// HTML
			$htmlContent = $this->embedMedia($mail, $htmlContent);
			$mail->setBody($htmlContent, 'text/html');
			$mail->addPart($content, 'text/plain');
		} else {
			// Plain text
			$mail->setBody($content, 'text/plain');
		}
		$mail->send();
	}
	
	/**
	 * Embeds media into the mail message
	 *
	 * @param t3lib_mail_Message $mail: mail message
	 * @param string $htmlContent: the HTML content of the message
	 * @return string the subtituted HTML content
	 */
	protected function embedMedia(t3lib_mail_Message $mail, $htmlContent) {
		$substitutedHtmlContent = $htmlContent;
		$media = array();
		$attribRegex = $this->makeTagRegex(array('img', 'embed', 'audio', 'video'));
			// Split the document by the beginning of the above tags
		$codepieces = preg_split($attribRegex, $htmlContent);
		$len = strlen($codepieces[0]);
		$pieces = count($codepieces);
		$reg = array();
		for ($i = 1; $i < $pieces; $i++) {
			$tag = strtolower(strtok(substr($htmlContent, $len + 1, 10), ' '));
			$len += strlen($tag) + strlen($codepieces[$i]) + 2;
			$dummy = preg_match('/[^>]*/', $codepieces[$i], $reg);
				// Fetches the attributes for the tag
			$attributes = $this->getTagAttributes($reg[0]);
			if ($attributes['src']) {
				$media[] = $attributes['src'];
			}
		}
		foreach ($media as $key => $source) {
			$substitutedHtmlContent = str_replace(
				'"' . $source . '"',
				'"' . $mail->embed(Swift_Image::fromPath($source)) . '"',
				$substitutedHtmlContent);
		}
		return $substitutedHtmlContent;
	}

	/**
	 * Creates a regular expression out of an array of tags
	 *
	 * @param	array		$tags: the array of tags
	 * @return	string		the regular expression
	 */
	protected function makeTagRegex(array $tags) {
		$regexpArray = array();
		foreach ($tags as $tag) {
			$regexpArray[] = '<' . $tag . '[[:space:]]';
		}
		return '/' . implode('|', $regexpArray) . '/i';
	}

	/**
	 * This function analyzes a HTML tag
	 * If an attribute is empty (like OPTION) the value of that key is just empty. Check it with is_set();
	 *
	 * @param string $tag: is either like this "<TAG OPTION ATTRIB=VALUE>" or this " OPTION ATTRIB=VALUE>" which means you can omit the tag-name
	 * @return array array with attributes as keys in lower-case
	 */
	protected function getTagAttributes($tag) {
		$attributes = array();
		$tag = ltrim(preg_replace('/^<[^ ]*/', '', trim($tag)));
		$tagLen = strlen($tag);
		$safetyCounter = 100;
			// Find attribute
		while ($tag) {
			$value = '';
			$reg = preg_split('/[[:space:]=>]/', $tag, 2);
			$attrib = $reg[0];

			$tag = ltrim(substr($tag, strlen($attrib), $tagLen));
			if (substr($tag, 0, 1) == '=') {
				$tag = ltrim(substr($tag, 1, $tagLen));
				if (substr($tag, 0, 1) == '"') {
						// Quotes around the value
					$reg = explode('"', substr($tag, 1, $tagLen), 2);
					$tag = ltrim($reg[1]);
					$value = $reg[0];
				} else {
						// No quotes around value
					preg_match('/^([^[:space:]>]*)(.*)/', $tag, $reg);
					$value = trim($reg[1]);
					$tag = ltrim($reg[2]);
					if (substr($tag, 0, 1) == '>') {
						$tag = '';
					}
				}
			}
			$attributes[strtolower($attrib)] = $value;
			$safetyCounter--;
			if ($safetyCounter < 0) {
				break;
			}
		}
		return $attributes;
	}
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sr_sendcard/lib/class.tx_srsendcard_email.php']) {
  include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sr_sendcard/lib/class.tx_srsendcard_email.php']);
}
?>