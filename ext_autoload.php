<?php
/*
 * Register necessary class names with autoloader
 *
 * $Id: ext_autoload.php $
 */
$extensionPath = t3lib_extMgm::extPath('sr_sendcard');
return array(
	'tx_srsendcard_pi1' => $extensionPath . 'pi1/class.tx_srsendcard_pi1.php',
	'tx_srsendcard_pi1_deferred' => $extensionPath . 'pi1/class.tx_srsendcard_pi1_deferred.php',
	'tx_srsendcard_email' => $extensionPath . 'lib/class.tx_srsendcard_email.php',
	'tx_srsendcard_cardmailer' => $extensionPath . 'tasks/class.tx_srsendcard_cardmailer.php',
	'tx_srsendcard_cardmailer_additionalfieldprovider' => $extensionPath . 'tasks/class.tx_srsendcard_cardmailer_additionalfieldprovider.php',
);
unset($extensionPath);
?>