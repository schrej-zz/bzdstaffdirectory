<?php
if (!defined('TYPO3_MODE')) {
  die ('Access denied.');
}
$tempColumns = Array (
	'tx_bzdstaffdirectory_bzd_contact_person' => Array (
		'l10n_mode' => $l10n_mode_merge,
		'exclude' => 0,
		'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:pages.tx_bzdstaffdirectory_bzd_contact_person',
		'config' => Array (
			'type' => 'select',
               	'renderType' => 'selectSingle',
			'foreign_table' => 'tx_bzdstaffdirectory_persons',
			'foreign_table_where' => 'ORDER BY tx_bzdstaffdirectory_persons.last_name ASC',
			'size' => 4,
			'minitems' => 0,
			'maxitems' => 5,
			'MM' => 'tx_bzdstaffdirectory_pages_persons_mm',
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

			'wizards' => Array(
				'_PADDING' => 2,
				'_VERTICAL' => 1,
			),
		)
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages',$tempColumns);
// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages','tx_bzdstaffdirectory_bzd_contact_person;;;;1-1-1');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages','tx_bzdstaffdirectory_bzd_contact_person;hidden,--palette--;;1');

?>