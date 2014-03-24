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
 * This is the card mailer task additional field provider of extension Send-A-Card (sr_sendcard)
 *
 */
class tx_srsendcard_cardMailer_AdditionalFieldProvider implements tx_scheduler_AdditionalFieldProvider {
	/**
	 * This method is used to define new fields for adding or editing a task
	 * In this case, it adds an sleep time field
	 *
	 * @param array $taskInfo Reference to the array containing the info used in the add/edit form
	 * @param object $task When editing, reference to the current task object. Null when adding.
	 * @param tx_scheduler_Module $parentObject Reference to the calling object (Scheduler's BE module)
	 * @return array	Array containing all the information pertaining to the additional fields
	 *					The array is multidimensional, keyed to the task class name and each field's id
	 *					For each field it provides an associative sub-array with the following:
	 *						['code']		=> The HTML code for the field
	 *						['label']		=> The label of the field (possibly localized)
	 *						['cshKey']		=> The CSH key for the field
	 *						['cshLabel']	=> The code of the CSH label
	 */
	public function getAdditionalFields(array &$taskInfo, $task, tx_scheduler_Module $parentObject) {
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
	 * @param tx_scheduler_Module $parentObject Reference to the calling object (Scheduler's BE module)
	 * @return boolean TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
	 */
	public function validateAdditionalFields(array &$submittedData, tx_scheduler_Module $parentObject) {
		$submittedData['tx_srsendcard_viewCardPid'] = intval($submittedData['tx_srsendcard_viewCardPid']);
		if ($submittedData['tx_srsendcard_viewCardPid'] <= 0) {
			$parentObject->addMessage($GLOBALS['LANG']->sL('LLL:EXT:sr_sendcard/Resources/Private/Language/locallang.xlf:msg.tx_srsendcard_viewCardPid'), t3lib_FlashMessage::ERROR);
			$result = FALSE;
		} else {
			$result = TRUE;
		}
		return $result;
	}

	/**
	 * This method is used to save any additional input into the current task object
	 * if the task class matches
	 *
	 * @param array $submittedData Array containing the data submitted by the user
	 * @param tx_scheduler_Task $task Reference to the current task object
	 * @return void
	 */
	public function saveAdditionalFields(array $submittedData, tx_scheduler_Task $task) {
		$task->viewCardPid = $submittedData['tx_srsendcard_viewCardPid'];
	}
}
if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sr_sendcard/tasks/class.tx_srsendcard_cardmailer_additionalfieldprovider.php']) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sr_sendcard/tasks/class.tx_srsendcard_cardmailer_additionalfieldprovider.php']);
}
?>