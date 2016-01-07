<?php
namespace SJBR\SrSendcard\Service;

/*
 *  Copyright notice
 *
 *  (c) 2016 Stanislas Rolland <typo3(arobas)sjbr.ca>
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

/**
 * Helper functionality for subparts and marker substitution
 * ###MYMARKER###
 */
class MarkerBasedTemplateService
{
	/**
	 * Marker-based template service (TYPO3 7+)
	 *
	 * @var \TYPO3\CMS\Core\Service\MarkerBasedTemplateService
	 */
	protected $markerBasedTemplateService = null;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Marker-based service
		if (class_exists('TYPO3\\CMS\\Core\\Service\\MarkerBasedTemplateService')) {
			$this->markerBasedTemplateService = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Service\\MarkerBasedTemplateService');
		}	
	}

	public function getSubpart($content, $marker)
	{
		if (is_object($this->markerBasedTemplateService)) {
			return $this->markerBasedTemplateService->getSubpart($content, $marker);
		} else {
			// In TYPO3 6.2
			return \TYPO3\CMS\Core\Html\HtmlParser::getSubpart($content, $marker);
		}
	}

	public function substituteSubpart($content, $marker, $subpartContent, $recursive = true, $keepMarker = false)
	{
		if (is_object($this->markerBasedTemplateService)) {
			return $this->markerBasedTemplateService->substituteSubpart($content, $marker, $subpartContent, $recursive, $keepMarker);
		} else {
			// In TYPO3 6.2
			return \TYPO3\CMS\Core\Html\HtmlParser::substituteSubpart($content, $marker, $subpartContent, $recursive, $keepMarker);
		}
	}

	public function substituteMarkerArray($content, $markContentArray, $wrap = '', $uppercase = false, $deleteUnused = false)
	{
		if (is_object($this->markerBasedTemplateService)) {
			return $this->markerBasedTemplateService->substituteMarkerArray($content, $markContentArray, $wrap, $uppercase, $deleteUnused);
		} else {
			// In TYPO3 6.2
			return \TYPO3\CMS\Core\Html\HtmlParser::substituteMarkerArray($content, $markContentArray, $wrap, $uppercase, $deleteUnused);
		}
	}
}