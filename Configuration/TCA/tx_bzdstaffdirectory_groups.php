<?php
/*
 * This is the default Table Configuration Array for the teams table.
 */

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages("tx_bzdstaffdirectory_groups");


return array(
	'ctrl' => Array (
		'title' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:tx_bzdstaffdirectory_groups',
		'label' => 'group_name',
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
		'enablecolumns' => Array (
			'disabled' => 'hidden',
		),
		'iconfile' => 'EXT:bzdstaffdirectory/icon_tx_bzdstaffdirectory_groups.gif',
	),

	'interface' => array(
		'showRecordFieldList' => 'hidden,group_name'
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
				'fieldControl' => array(
					'listModule' => array(
						'disabled' => '',
						'options' => array(
							'pid' => '###CURRENT_PID###',
							'table'=>'tx_bzdstaffdirectory_persons',
							'title' => 'List'
						)
					)
				),
				'wizards' => array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
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
				'fieldControl' => array(
					'listModule' => array(
						'disabled' => '',
						'options' => array(
							'pid' => '###CURRENT_PID###',
							'table'=>'tx_bzdstaffdirectory_persons',
							'title' => 'List'
						)
					)
				),

				'wizards' => array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
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
			)
		),
		'l18n_diffsource' => array(
			'config'=>array(
				'type'=>'passthrough')
		)
	),
	'types' => array(
		'0' => array('showitem' => 'hidden,--palette--;;1,group_name,group_leaders,team_members,infopage')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

?>
