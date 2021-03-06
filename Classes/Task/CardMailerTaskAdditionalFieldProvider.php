<?php
namespace SJBR\SrSendcard\Task;

/*
 *  Copyright notice
 *
 *  (c) 2012-2015 Stanislas Rolland <typo3(arobas)sjbr.ca>
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

use TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * This is the card mailer task additional field provider of extension Send-A-Card (sr_sendcard)
 *
 */
class CardMailerTaskAdditionalFieldProvider implements AdditionalFieldProviderInterface
{
	/**
	 * This method is used to define new fields for adding or editing a task
	 *
	 * @param array $taskInfo Reference to the array containing the info used in the add/edit form
	 * @param object $task When editing, reference to the current task object. Null when adding.
	 * @param SchedulerModuleController $parentObject Reference to the calling object (Scheduler's BE module)
	 * @return array	Array containing all the information pertaining to the additional fields
	 *					The array is multidimensional, keyed to the task class name and each field's id
	 *					For each field it provides an associative sub-array with the following:
	 *						['code']		=> The HTML code for the field
	 *						['label']		=> The label of the field (possibly localized)
	 *						['cshKey']		=> The CSH key for the field
	 *						['cshLabel']	=> The code of the CSH label
	 */
	public function getAdditionalFields(array &$taskInfo, $task, SchedulerModuleController $parentObject)
	{
		// Initialize extra field value
		if (empty($taskInfo['tx_srsendcard_viewCardPid'])) {
			if ($parentObject->CMD == 'add') {
				// In case of new task and if field is empty, set default value
				$taskInfo['tx_srsendcard_viewCardPid'] = '';
			} elseif ($parentObject->CMD == 'edit') {
				// In case of edit, set to internal value if no data was submitted already
				$taskInfo['tx_srsendcard_viewCardPid'] = $task->viewCardPid;
			} else {
				// Otherwise set an empty value, as it will not be used anyway
				$taskInfo['tx_srsendcard_viewCardPid'] = '';
			}
		}

		// Write the code for the field
		$fieldID = 'tx_srsendcard_viewCardPid';
		$fieldCode = '<input type="text" name="tx_scheduler[tx_srsendcard_viewCardPid]" id="' . $fieldID . '" value="' . $taskInfo['tx_srsendcard_viewCardPid'] . '" size="10" />';
		$additionalFields = array();
		$additionalFields[$fieldID] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:sr_sendcard/Resources/Private/Language/locallang.xlf:label.tx_srsendcard_viewCardPid',
			'cshKey'   => '_MOD_tools_txschedulerM1',
			'cshLabel' => $fieldID
		);
		return $additionalFields;
	}

	/**
	 * This method checks any additional data that is relevant to the specific task
	 * If the task class is not relevant, the method is expected to return TRUE
	 *
	 * @param array $submittedData Reference to the array containing the data submitted by the user
	 * @param SchedulerModuleController $parentObject Reference to the calling object (Scheduler's BE module)
	 * @return boolean TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
	 */
	public function validateAdditionalFields(array &$submittedData, SchedulerModuleController $parentObject)
	{
		$submittedData['tx_srsendcard_viewCardPid'] = intval($submittedData['tx_srsendcard_viewCardPid']);
		if ($submittedData['tx_srsendcard_viewCardPid'] <= 0) {
			$parentObject->addMessage($GLOBALS['LANG']->sL('LLL:EXT:sr_sendcard/Resources/Private/Language/locallang.xlf:msg.tx_srsendcard_viewCardPid'), \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
			$result = false;
		} else {
			$result = true;
		}
		return $result;
	}

	/**
	 * This method is used to save any additional input into the current task object
	 * if the task class matches
	 *
	 * @param array $submittedData Array containing the data submitted by the user
	 * @param AbstractTask $task Reference to the current task object
	 * @return void
	 */
	public function saveAdditionalFields(array $submittedData, AbstractTask $task)
	{
		$task->viewCardPid = $submittedData['tx_srsendcard_viewCardPid'];
	}
}