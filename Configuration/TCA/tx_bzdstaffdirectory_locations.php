<?php
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages("tx_bzdstaffdirectory_locations");

$TCA["tx_bzdstaffdirectory_locations"] = Array (
	'ctrl' => Array (
		'title' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_locations',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'sortby' => 'sorting',
		'delete' => 'deleted',
		'prependAtCopy' => 'LLL:EXT:lang/locallang_general.php:LGL.prependAtCopy',
		'copyAfterDuplFields' => 'sys_language_uid',
		'useColumnsForDefaultValues' => 'sys_language_uid',
   		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
		'languageField' => 'sys_language_uid',
		'enablecolumns' => array (
			'disabled' => 'hidden',
		),
		'iconfile' => 'EXT:bzdstaffdirectory/icon_tx_bzdstaffdirectory_locations.gif',
	),
);
?>
