<?php
/***************************************************************
*  Copyright notice
*	
*  (c) 2003-2011 Stanislas Rolland <typo3(arobas)sjbr.ca>
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
 * This is the cron job of extension Send-A-Card (sr_sendcard) that sends the deferred cards
 *
 */
 
error_reporting (E_ALL ^ E_NOTICE);
if (!defined('PATH_thisScript')) define('PATH_thisScript',str_replace('//','/', str_replace('\\','/', php_sapi_name()=='xcgi'||php_sapi_name()=='isapi'||php_sapi_name()=='cgi-fcgi' ? $HTTP_SERVER_VARS['PATH_TRANSLATED']:$HTTP_SERVER_VARS['SCRIPT_FILENAME'])));
if (!defined('PATH_site')) define('PATH_site', dirname(dirname(dirname(dirname(dirname(PATH_thisScript))))).'/');
if (!defined('PATH_t3lib')) if (!defined('PATH_t3lib')) define('PATH_t3lib', PATH_site.'t3lib/');
define('TYPO3_mainDir', 'typo3/');
if (!defined('PATH_typo3')) define('PATH_typo3', PATH_site.TYPO3_mainDir);
if (!defined('PATH_tslib')) {
	if (@is_dir(PATH_site.'typo3/sysext/cms/tslib/')) {
		define('PATH_tslib', PATH_site.'typo3/sysext/cms/tslib/');
	} elseif (@is_dir(PATH_site.'tslib/')) {
		define('PATH_tslib', PATH_site.'tslib/');
	}
}
define('PATH_typo3conf', PATH_site.'typo3conf/');
define('TYPO3_MODE','BE');

require_once(PATH_t3lib.'class.t3lib_div.php');
require_once(PATH_t3lib.'class.t3lib_extmgm.php');
require_once(PATH_t3lib.'config_default.php');
require_once(PATH_typo3conf.'localconf.php');
require_once(PATH_tslib.'class.tslib_fe.php');
require_once(PATH_t3lib.'class.t3lib_userauth.php');
require_once(PATH_tslib.'class.tslib_feuserauth.php');
require_once(PATH_t3lib.'class.t3lib_tstemplate.php');
require_once(PATH_t3lib.'class.t3lib_page.php');
require_once(PATH_tslib.'class.tslib_content.php');
require_once(t3lib_extMgm::extPath('sr_sendcard').'pi1/class.tx_srsendcard_pi1_deferred.php');
require_once(PATH_t3lib.'class.t3lib_cs.php');

if (!defined ('TYPO3_db'))  die ('The configuration file was not included.');

require_once(PATH_t3lib.'class.t3lib_db.php');
$TYPO3_DB = t3lib_div::makeInstance('t3lib_DB');

require_once(PATH_t3lib.'class.t3lib_timetrack.php');
$GLOBALS['TT'] = new t3lib_timeTrack;

// ***********************************
// Creating a fake $TSFE object
// ***********************************

$TSFEclassName = t3lib_div::makeInstanceClassName('tslib_fe');
$id = isset($HTTP_GET_VARS['id'])?$HTTP_GET_VARS['id']:0;
$GLOBALS['TSFE'] = new $TSFEclassName($TYPO3_CONF_VARS, $id, '0', 1, '', '','','');
$GLOBALS['TSFE']->connectToMySQL();
$GLOBALS['TSFE']->initFEuser();
$GLOBALS['TSFE']->fetch_the_id();
$GLOBALS['TSFE']->getPageAndRootline();
$GLOBALS['TSFE']->initTemplate();
$GLOBALS['TSFE']->tmpl->getFileName_backPath = PATH_site;
$GLOBALS['TSFE']->forceTemplateParsing = 1;
$GLOBALS['TSFE']->getConfigArray();
$sendingCards = t3lib_div::makeInstance('tx_srsendcard_pi1_deferred');
$sendingCards->cObj = t3lib_div::makeInstance('tslib_cObj');
$conf = $GLOBALS['TSFE']->tmpl->setup['plugin.'][$sendingCards->prefixId.'.'];
$sendingCards->main($conf);

?>