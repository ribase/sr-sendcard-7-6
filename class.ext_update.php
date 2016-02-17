<?php
namespace SJBR\SrSendcard;

/*
 *  Copyright notice
 *
 *  (c) 2016 Stanislas Rolland <typo3(arobas)sjbr.ca>
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
 */

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Class for updating the db
 */
class ext_update
{
	/**
	 * @var string Name of the extension this controller belongs to
	 */
	protected $extensionName = 'SrSendcard';

	/**
	 * Main function, returning the HTML content
	 *
	 * @return string HTML
	 */
	public function main()
	{
		$content = '';
		$content .= '<p>' . nl2br(LocalizationUtility::translate('update.migrateTables', $this->extensionName)) . '</p>';

		$tables = array(
			array(
				'source' => 'tx_srsendcard_card',
				'target' => 'tx_srsendcard_domain_model_card'
			),
			array(
				'source' => 'tx_srsendcard_sendcard',
				'target' => 'tx_srsendcard_domain_model_sendcard'
			)
		);

		foreach ($tables as $table) {
			$convertedCards = array();
			$convertedCards = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', $table['target'], '1=1');
			if (is_array($convertedCards) && empty($convertedCards)) {
				$cards = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', $table['source'], '1=1');
				if (is_array($cards) && !empty($cards)) {
					foreach ($cards as $card) {
						$newcard = $card;
						if ($table['target'] === 'tx_srsendcard_domain_model_sendcard') {
							$newcard['id'] = $card['uid'];
							unset($newcard['uid']);
						}
						$res = $GLOBALS['TYPO3_DB']->exec_INSERTquery($table['target'], $newcard);
					}
					$content .= '<p>' . nl2br(sprintf(LocalizationUtility::translate('update.tableMigrated', $this->extensionName), count($cards), $table['source'])) . '</p>';
				}
			} else {
				$content .= '<p>' . nl2br(sprintf(LocalizationUtility::translate('update.tableNotMigrated', $this->extensionName), $table['source'], $table['target'])) . '</p>';
			}
		}
		return $content;
	}

	public function access()
	{
		return true;
	}
}