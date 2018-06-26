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

/*
 * This is the default Table Configuration Array for the persons table.
 */
$TCA['tx_bzdstaffdirectory_persons'] = Array (
	'ctrl' => $TCA['tx_bzdstaffdirectory_persons']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,last_name,first_name,image,usergroups,tasks'
	),
	'columns' => array(
		'hidden' => array(
			'l10n_mode' => $hideNewLocalizations,
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'last_name' => array(
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.last_name',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'first_name' => array(
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.first_name',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'title' => array(
			'l10n_mode' => $l10n_mode,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.title',
			'config' => array(
				'type' => 'input',
				'size' => '30'
			)
		),
		'image' => array(
			'l10n_mode' => $l10n_mode_image,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.image',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
				'max_size' => 2000,
				'uploadfolder' => 'uploads/tx_bzdstaffdirectory',
				'show_thumbs' => 1,
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			)
		),
		'usergroups' => array(
			'l10n_mode' => 'exclude',
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.usergroups',
			'config' => array(
				'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
				'foreign_table' => 'tx_bzdstaffdirectory_groups',
				'foreign_table_where' => 'AND tx_bzdstaffdirectory_groups.l18n_parent = 0 ORDER BY tx_bzdstaffdirectory_groups.uid',
				'size' => 4,
				'minitems' => 0,
				'maxitems' => 99,
				'MM' => 'tx_bzdstaffdirectory_persons_usergroups_mm',
			)
		),
		'gender' => array(
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.gender',
			'config' => array(
				'type' => 'select',
                'renderType' => 'selectSingle',
				'items' => array(
					array('LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.gender.notSet', 0),
					array('LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.gender.male', 1),
					array('LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.gender.female', 2)
				)
			)
		),
		'date_incompany' => array(
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.date_incompany',
			'config' => array(
				'type' => 'input',
				'size' => '12',
				'max' => '20',
				'eval' => 'date',
				'default' => '0'
			)
		),
		'date_birthdate' => array(
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.date_birthdate',
			'config' => array(
				'type' => 'input',
				'size' => '12',
				'max' => '20',
				'eval' => 'date',
				'default' => '0'
			)
		),
		'function' => array(
			'l10n_mode' => $l10n_mode,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.function',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'functions' => array(
			'l10n_mode' => 'exclude',
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.functions',
			'config' => array(
				'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
				'foreign_table' => 'tx_bzdstaffdirectory_functions',
				'foreign_table_where' => 'AND tx_bzdstaffdirectory_functions.l18n_parent = 0 ORDER BY tx_bzdstaffdirectory_functions.uid',
				'size' => 4,
				'minitems' => 0,
				'maxitems' => 99,
				'MM' => 'tx_bzdstaffdirectory_persons_functions_mm',
			)
		),
		'email' => array(
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.email',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'tasks' => array(
			'l10n_mode' => $l10n_mode,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.tasks',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'opinion' => array(
			'l10n_mode' => $l10n_mode,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.opinion',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'location' => array(
			'l10n_mode' => 'exclude',
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.location',
			'config' => array(
				'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
				'foreign_table' => 'tx_bzdstaffdirectory_locations',
				'foreign_table_where' => 'AND tx_bzdstaffdirectory_locations.l18n_parent = 0 ORDER BY tx_bzdstaffdirectory_locations.uid',
				'size' => 4,
				'minitems' => 0,
				'maxitems' => 99,
				'MM' => 'tx_bzdstaffdirectory_persons_locations_mm',
			)
		),
		'room' => array(
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.room',
			'config' => array(
				'type' => 'input',
				'size' => '30'
			)
		),
		'nickname' => array(
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.nickname',
			'config' => array(
				'type' => 'input',
				'size' => '30'
			)
		),
		'phone' => array(
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.phone',
			'config' => array(
				'type' => 'input',
				'size' => '30'
			)
		),
		'mobile_phone' => array(
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.mobile_phone',
			'config' => array(
				'type' => 'input',
				'size' => '30'
			)
		),
		'officehours' => array(
			'l10n_mode' => $l10n_mode,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.officehours',
			'config' => array(
				'type' => 'input',
				'size' => '30'
			)
		),
		'xing_profile_url' => array(
			'l10n_mode' => $l10n_mode,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.xing_profile_url',
			'config' => array(
				'type' => 'input',
				'size' => '30'
			)
		),
		'files' => array(
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_persons.files',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
				'max_size' => 2000,
				'uploadfolder' => 'uploads/tx_bzdstaffdirectory',
				'show_thumbs' => 1,
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 5
			)
		),
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ],
                ],
                'default' => 0,
            ]
        ],
		'l18n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
				'foreign_table' => 'tx_bzdstaffdirectory_persons',
				'foreign_table_where' => 'AND tx_bzdstaffdirectory_persons.uid=###CURRENT_PID### AND tx_bzdstaffdirectory_persons.sys_language_uid IN (-1,0)',
                'showIconTable' => false
			)
		),
		'l18n_diffsource' => array(
			'config'=>array(
				'type'=>'passthrough')
		)
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, last_name, first_name, title, email, phone, mobile_phone, function, functions, nickname, gender, date_birthdate, date_incompany, image, usergroups, location, room, officehours, xing_profile_url, tasks, opinion;;;richtext[paste|bold|italic|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts_css], files')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

// add the universal fields to the TCA Array if they are configured
if ($confArr['useUniversalField_1'] && !empty($confArr['fieldNameUniversalField_1'])) {
	$TCA['tx_bzdstaffdirectory_persons']['types']['0']['showitem'] .= ', universal_field_1';
	$TCA['tx_bzdstaffdirectory_persons']['columns']['universal_field_1'] = array(
		'l10n_mode' => $l10n_mode,
		'exclude' => 1,
		'label' => $confArr['fieldNameUniversalField_1'],
		'config' => array(
			'type' => 'input',
			'size' => '30'
		)
	);
}

if ($confArr['useUniversalField_2'] && !empty($confArr['fieldNameUniversalField_2'])) {
	$TCA['tx_bzdstaffdirectory_persons']['types']['0']['showitem'] .= ', universal_field_2';
	$TCA['tx_bzdstaffdirectory_persons']['columns']['universal_field_2'] = array(
		'l10n_mode' => $l10n_mode,
		'exclude' => 1,
		'label' => $confArr['fieldNameUniversalField_2'],
		'config' => array(
			'type' => 'input',
			'size' => '30'
		)
	);
}

if ($confArr['useUniversalField_3'] && !empty($confArr['fieldNameUniversalField_3'])) {
	$TCA['tx_bzdstaffdirectory_persons']['types']['0']['showitem'] .= ', universal_field_3';
	$TCA['tx_bzdstaffdirectory_persons']['columns']['universal_field_3'] = array(
		'l10n_mode' => $l10n_mode,
		'exclude' => 1,
		'label' => $confArr['fieldNameUniversalField_3'],
		'config' => array(
			'type' => 'input',
			'size' => '30'
		)
	);
}

if ($confArr['useUniversalField_4'] && !empty($confArr['fieldNameUniversalField_4'])) {
	$TCA['tx_bzdstaffdirectory_persons']['types']['0']['showitem'] .= ', universal_field_4';
	$TCA['tx_bzdstaffdirectory_persons']['columns']['universal_field_4'] = array(
		'l10n_mode' => $l10n_mode,
		'exclude' => 1,
		'label' => $confArr['fieldNameUniversalField_4'],
		'config' => array(
			'type' => 'input',
			'size' => '30'
		)
	);
}

if ($confArr['useUniversalField_5'] && !empty($confArr['fieldNameUniversalField_5'])) {
	$TCA['tx_bzdstaffdirectory_persons']['types']['0']['showitem'] .= ', universal_field_5';
	$TCA['tx_bzdstaffdirectory_persons']['columns']['universal_field_5'] = array(
		'l10n_mode' => $l10n_mode,
		'exclude' => 1,
		'label' => $confArr['fieldNameUniversalField_5'],
		'config' => array(
			'type' => 'input',
			'size' => '30'
		)
	);
}

// check and mark required input fields for a persons record
// Explodes required fields from extension configuration to array.
$requiredFields = explode(',', $confArr['requiredFields']);
// Delete duplicate entries.
$requiredFields = array_unique($requiredFields);
// For all required fields do ...
foreach ($requiredFields as $requiredField) {
	// Trim spaces and co.
	$requiredField = trim($requiredField);
	// Is the field name already existing? Only then it's a valid entry!
	if (array_key_exists($requiredField, $TCA['tx_bzdstaffdirectory_persons']['columns'])) {
		// Is 'eval' already set?
		if (array_key_exists('eval', $TCA['tx_bzdstaffdirectory_persons']['columns'][$requiredField]['config'])) {
			// 'eval' is already set, so append 'required'.
			$TCA['tx_bzdstaffdirectory_persons']['columns'][$requiredField]['config']['eval'] .= ',required';
		}
		else {
			// 'eval' isn't already set, so set it 'required'
			$TCA['tx_bzdstaffdirectory_persons']['columns'][$requiredField]['config']['eval'] = 'required';
		}
	}
}
?>
