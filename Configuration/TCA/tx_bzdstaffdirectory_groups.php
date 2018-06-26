<?php
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages("tx_bzdstaffdirectory_groups");

$TCA["tx_bzdstaffdirectory_groups"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_groups",
		"label" => "group_name",
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"sortby" => "sorting",
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
		"dynamicConfigFile" => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY)."tca.php",
        'iconfile' => 'EXT:bzdstaffdirectory/icon_tx_bzdstaffdirectory_groups.gif',
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, group_name",
	)
);
?>
