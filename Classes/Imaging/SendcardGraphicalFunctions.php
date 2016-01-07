<?php
namespace SJBR\SrSendcard\Imaging;

/*
 *  Copyright notice
 *
 *  (c) 2003-2015 Stanislas Rolland <typo3(arobas)sjbr.ca>
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

use TYPO3\CMS\Core\Imaging\GraphicalFunctions;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Adding geometry parameter to position a logo on the image of the card
 */
class SendcardGraphicalFunctions extends GraphicalFunctions
{
	public function combineExec($input, $overlay, $mask, $output, $handleNegation = false, $geometry = '')
	{
		if (!$this->NO_IMAGE_MAGICK) {
			if ($geometry) {
				$geometry = '-geometry ' . $geometry . ' ';
			}
			$params = '-colorspace GRAY +matte';
			$theMask = $this->randomName() . '.' . $this->gifExtension;
			$this->imageMagickExec($mask, $theMask, $params);
			$cmd = GeneralUtility::imageMagickCommand('combine', '-compose over +matte ' . $geometry . $this->wrapFileName($input) . ' ' . $this->wrapFileName($overlay) . ' ' . $this->wrapFileName($theMask) . ' ' . $this->wrapFileName($output));
			// +matte = no alpha layer in output
			$this->IM_commands[] = array($output, $cmd);
			$ret = CommandUtility::exec($cmd);
			// Change the permissions of the file
			GeneralUtility::fixPermissions($output);
			if (is_file($theMask)) {
				@unlink($theMask);
			}
			return $ret;
		}
	}
}