<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

t3lib_extMgm::addUserTSConfig('options.saveDocNew.tx_srsendcard_card=1');
t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_srsendcard_pi1.php', '_pi1', 'list_type', 0);

	// unserializing the configuration so we can use it here:
$_EXTCONF = unserialize($_EXTCONF);

/**
 * Keep card language overlay table for backward compatibility of card translation mechanisms.
 */
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['keepCardLanguageOverlay'] = $_EXTCONF['keepCardLanguageOverlay'] ? $_EXTCONF['keepCardLanguageOverlay'] : 0;

?>
