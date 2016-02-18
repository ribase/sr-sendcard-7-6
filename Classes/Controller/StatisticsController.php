<?php
namespace SJBR\SrSendcard\Controller;

/*
 *  Copyright notice
 *
 *  (c) 2003-2016 Stanislas Rolland <typo3(arobas)sjbr.ca>
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

use SJBR\SrSendcard\Domain\Repository\SendcardRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Module 'Sent Cards Statistics'
 */
class StatisticsController extends ActionController
{
	/**
	 * @var string Name of the extension this controller belongs to
	 */
	protected $extensionName = 'SrSendcard';

	/**
	 * @var \SJBR\SrSendcard\Domain\Repository\SendcardRepository
	 * @inject
	 */
	protected $sendcardRepository;

	/**
	 * Display the statistics in alphabetial order
	 *
	 * @return void
	 */
	public function indexAction()
	{
		$entries = $this->computeStatistics('index');
		$this->view->assign('entries', $entries);
	}

	/**
	 * Display the statistics in order of last date sent
	 *
	 * @return void
	 */
	public function recentAction()
	{
		$entries = $this->computeStatistics('recent');
		$this->view->assign('entries', $entries);
	}

	/**
	 * Display the statistics in popularity order
	 *
	 * @return void
	 */
	public function popularAction()
	{
		$entries = $this->computeStatistics('popular');
		$this->view->assign('entries', $entries);
	}

	/**
	 * Computes the statistics
	 *
	 * @return array the statistics entries
	 */
	protected function computeStatistics($action)
	{
		// Get the sent cards
		$sentCards = $this->sendcardRepository->findAll();
		$cardsCaption = array();
		$cardsCount = array();
		$cardsDate = array();
		$lastCaption = '';
		$index = -1;
		foreach ($sentCards as $sentCard) {
			if ($lastCaption !== $sentCard->getCaption()) {
				$index++;
				$cardsCaption[$index] = $sentCard->getCaption();
				$cardsDate[$index] = $sentCard->getTimeCreated()->getTimestamp();
				$cardsCount[$index] = 1;
			} else {
				$cardsCount[$index] = $cardsCount[$index]+1;
				if ($cardsDate[$index] < $sentCard->getTimeCreated()->getTimestamp()) {
					$cardsDate[$index] = $sentCard->getTimeCreated()->getTimestamp();
				};
			}
			$lastCaption = $sentCard->getCaption();
		}
		// Sort and adjust table titles according to selected function
		switch ($action) {
			case 'index':
				/* Sorted in alphabetial order*/
				break;
			case 'recent':
				/* Sorted by most recently sent*/
				array_multisort($cardsDate, SORT_DESC, SORT_NUMERIC, $cardsCaption, SORT_STRING, $cardsCount, SORT_DESC, SORT_NUMERIC);
				break;
			case 'popular':
				/* Sorted by frequency*/
				array_multisort($cardsCount, SORT_DESC, SORT_NUMERIC, $cardsDate, SORT_DESC, SORT_NUMERIC, $cardsCaption, SORT_STRING);
				break;
		}
		// Assemble the entries
		$entries = array();
		$index = 0;
		while ($cardsCaption[$index]) {
			$entries[] = array(
				'cardTitle' => $cardsCaption[$index],
				'cardTimes' => $cardsCount[$index],
				'cardLastTime' => $cardsDate[$index]
			);
			$index++;
		}
		return $entries;
	}
}