<?php
if (!defined('TYPO3_MODE')) {
  die ('Access denied.');
}

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['bzdstaffdirectory_pi1']='layout, select_key, pages, recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['bzdstaffdirectory_pi1']='pi_flexform';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(Array('LLL:EXT:bzdstaffdirectory/locallang_db.php:tt_content.list_type_pi1','bzdstaffdirectory_pi1'),'list_type','bzdstaffdirectory');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('bzdstaffdirectory_pi1','FILE:EXT:bzdstaffdirectory/flexform_ds.xml');





?>
