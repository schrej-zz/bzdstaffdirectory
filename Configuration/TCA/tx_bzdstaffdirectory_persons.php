<?php
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages("tx_bzdstaffdirectory_persons");

$TCA["tx_bzdstaffdirectory_persons"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons",
		'label' => 'last_name',
		'label_alt' => 'first_name',
		'label_alt_force' => 1,
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"default_sortby" => "ORDER BY last_name",
		"delete" => "deleted",
		'prependAtCopy' => 'LLL:EXT:lang/locallang_general.php:LGL.prependAtCopy',
		'copyAfterDuplFields' => 'sys_language_uid',
		'useColumnsForDefaultValues' => 'sys_language_uid',
   		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
		'languageField' => 'sys_language_uid',
		"enablecolumns" => Array (
			"disabled" => "hidden",
		),
		'iconfile' => 'EXT:bzdstaffdirectory/icon_tx_bzdstaffdirectory_persons.gif',
	),
);
?>
