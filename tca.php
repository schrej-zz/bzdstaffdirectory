<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

// get extension confArr
$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['bzdstaffdirectory']);

// l10n_mode for text fields
$l10n_mode = ($confArr['l10n_mode_prefixLangTitle']?'prefixLangTitle':'');

// l10n_mode for text fields that probably won't be translated (like the name, phone number and so on)
$l10n_mode_merge = '';//($confArr['l10n_mode_prefixLangTitle']?'mergeIfNotBlank':'');

// l10n_mode for the image field
$l10n_mode_image = ($confArr['l10n_mode_imageExclude']?'exclude':'mergeIfNotBlank');

// hide new localizations
$hideNewLocalizations = ($confArr['hideNewLocalizations']?'mergeIfNotBlank':'');



/*
 * This is the default Table Configuration Array for the persons table.
 */
$TCA['tx_bzdstaffdirectory_persons'] = Array (
	'ctrl' => $TCA['tx_bzdstaffdirectory_persons']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,last_name,first_name,image,usergroups,tasks'
	),
	'feInterface' => $TCA['tx_bzdstaffdirectory_persons']['feInterface'],
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

/*
 * This is the default Table Configuration Array for the teams table.
 */
$TCA['tx_bzdstaffdirectory_groups'] = array(
	'ctrl' => $TCA['tx_bzdstaffdirectory_groups']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,group_name'
	),
	'feInterface' => $TCA['tx_bzdstaffdirectory_groups']['feInterface'],
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
		'group_name' => array(
			'l10n_mode' => $l10n_mode,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_groups.group_name',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'group_leaders' => array(
			'l10n_mode' => 'exclude',
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_groups.group_leaders',
			'config' => array(
				'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
				'foreign_table' => 'tx_bzdstaffdirectory_persons',
				'foreign_table_where' => 'AND tx_bzdstaffdirectory_persons.l18n_parent = 0 ORDER BY tx_bzdstaffdirectory_persons.uid',
				'size' => 4,
				'minitems' => 0,
				'maxitems' => 99,
				'MM' => 'tx_bzdstaffdirectory_groups_teamleaders_mm',
				'wizards' => array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
					'list' => array(
						'type' => 'script',
						'title' => 'List',
						'icon' => 'list.gif',
						'params' => array(
							'table'=>'tx_bzdstaffdirectory_persons',
							'pid' => '###CURRENT_PID###',
						),
						'module' => array('name' => 'wizard_list'),
					)
				)
			)
		),
		'team_members' => array(
			'l10n_mode' => 'exclude',
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_groups.group_members',
			'config' => array(
				'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
				'foreign_table' => 'tx_bzdstaffdirectory_persons',
				'foreign_table_where' => 'AND tx_bzdstaffdirectory_persons.l18n_parent = 0 ORDER BY tx_bzdstaffdirectory_persons.last_name',
				'size' => 4,
				'minitems' => 0,
				'maxitems' => 9999,
				'MM' => 'tx_bzdstaffdirectory_persons_usergroups_mm',
				'MM_foreign_select' => 1,
				'MM_opposite_field' => 'uid_local',
				'wizards' => array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
					'list' => array(
						'type' => 'script',
						'title' => 'List',
						'icon' => 'list.gif',
						'params' => array(
							'table' => 'tx_bzdstaffdirectory_persons',
							'pid' => '###CURRENT_PID###',
						),
						'module' => array('name' => 'wizard_list'),
					)
				)
			)
		),

		'infopage' => array(
			'l10n_mode' => 'exclude',
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_groups.infopage',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'pages',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
				'show_thumbs' => 1
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
				'foreign_table' => 'tx_bzdstaffdirectory_groups',
				'foreign_table_where' => 'AND tx_bzdstaffdirectory_groups.uid=###CURRENT_PID### AND tx_bzdstaffdirectory_groups.sys_language_uid IN (-1,0)',
                'showIconTable' => false
			)
		),
		'l18n_diffsource' => array(
			'config'=>array(
				'type'=>'passthrough')
		)
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, group_name, group_leaders, team_members, infopage')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

/*
 * This is the default Table Configuration Array for the locations table.
 */
$TCA['tx_bzdstaffdirectory_locations'] = array(
	'ctrl' => $TCA['tx_bzdstaffdirectory_locations']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,title'
	),
	'feInterface' => $TCA['tx_bzdstaffdirectory_locations']['feInterface'],
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
		'title' => array(
			'l10n_mode' => $l10n_mode,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_locations.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'address' => array(
			'l10n_mode' => $l10n_mode,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_locations.address',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'zip' => array(
			'l10n_mode' => $l10n_mode,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_locations.zip',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'city' => array(
			'l10n_mode' => $l10n_mode,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_locations.city',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'country' => array(
			'l10n_mode' => $l10n_mode,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_locations.country',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'infopage' => array(
			'l10n_mode' => 'exclude',
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_locations.infopage',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'pages',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
				'show_thumbs' => 1
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
				'foreign_table' => 'tx_bzdstaffdirectory_groups',
				'foreign_table_where' => 'AND tx_bzdstaffdirectory_groups.uid=###CURRENT_PID### AND tx_bzdstaffdirectory_groups.sys_language_uid IN (-1,0)',
                'showIconTable' => false
			)
		),
		'l18n_diffsource' => array(
			'config'=>array(
				'type'=>'passthrough')
		)
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, title, address, zip, city, country, infopage')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);


/*
 * This is the default Table Configuration Array for the functions table.
 */
$TCA['tx_bzdstaffdirectory_functions'] = array(
	'ctrl' => $TCA['tx_bzdstaffdirectory_functions']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,title'
	),
	'feInterface' => $TCA['tx_bzdstaffdirectory_functions']['feInterface'],
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
		'title' => array(
			'l10n_mode' => $l10n_mode,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_functions.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
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
				'foreign_table' => 'tx_bzdstaffdirectory_functions',
				'foreign_table_where' => 'AND tx_bzdstaffdirectory_functions.uid=###CURRENT_PID### AND tx_bzdstaffdirectory_functions.sys_language_uid IN (-1,0)',
                'showIconTable' => false
			)
		),
		'l18n_diffsource' => array(
			'config'=>array(
				'type'=>'passthrough')
		)
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, title')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

?>