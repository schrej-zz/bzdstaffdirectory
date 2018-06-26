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
		'iconfile' => 'EXT:bzdstaffdirectory/icon_tx_bzdstaffdirectory_groups.gif',
	),
);

*
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

?>
