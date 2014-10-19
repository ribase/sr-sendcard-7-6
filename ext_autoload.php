<?php
/*
 * Register necessary class names with autoloader
 */
$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('sr_sendcard');
return array(
	'tx_srsendcard_pi1' => $extensionPath . 'pi1/class.tx_srsendcard_pi1.php',
	'tx_srsendcard_pi1_deferred' => $extensionPath . 'pi1/class.tx_srsendcard_pi1_deferred.php',
	'tx_srsendcard_email' => $extensionPath . 'lib/class.tx_srsendcard_email.php',
	'tx_srsendcard_statistics' => $extensionPath . 'mod1/class.tx_srsendcard_statistics.php',
);