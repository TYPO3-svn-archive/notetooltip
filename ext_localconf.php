<?php
if (!defined ("TYPO3_MODE")) 	die ("Access denied.");
$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['typo3/class.webpagetree.php'] = t3lib_extMgm::extPath($_EXTKEY).'class.ux_webpagetree.php';
?>