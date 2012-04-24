<?php
/*
 * Register necessary class names with autoloader
 *
 * $Id: ext_autoload.php $
 */
$extensionPath = t3lib_extMgm::extPath('sr_sendcard');
return array(
	'tx_srsendcard_pi1' => $extensionPath . 'pi1/class.tx_srsendcard_pi1.php',
);
unset($extensionPath);
?>