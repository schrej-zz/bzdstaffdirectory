<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(Array('LLL:EXT:bzdstaffdirectory/locallang_db.php:tt_content.list_type_pi1', $_EXTKEY.'_pi1'),'list_type');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($_EXTKEY.'_pi1','FILE:EXT:bzdstaffdirectory/flexform_ds.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY,"pi1/static/","BZD Staff Directory");


if (TYPO3_MODE=="BE")	$TBE_MODULES_EXT["xMOD_db_new_content_el"]["addElClasses"]["tx_bzdstaffdirectory_pi1_wizicon"] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY).'pi1/class.tx_bzdstaffdirectory_pi1_wizicon.php';

$tempColumns = Array (
	'tx_bzdstaffdirectory_bzd_contact_person' => Array (
		'l10n_mode' => $l10n_mode_merge,
		'exclude' => 0,
		'label' => 'LLL:EXT:bzdstaffdirectory/locallang_db.php:pages.tx_bzdstaffdirectory_bzd_contact_person',
		'config' => Array (
			'type' => 'select',
			'foreign_table' => 'tx_bzdstaffdirectory_persons',
			'foreign_table_where' => 'ORDER BY tx_bzdstaffdirectory_persons.last_name ASC',
			'size' => 4,
			'minitems' => 0,
			'maxitems' => 5,
			'MM' => 'tx_bzdstaffdirectory_pages_persons_mm',
			'wizards' => Array(
				'_PADDING' => 2,
				'_VERTICAL' => 1,
				'list' => Array(
					'type' => 'script',
					'title' => 'List',
					'icon' => 'list.gif',
					'params' => Array(
						'table'=>'tx_bzdstaffdirectory_persons',
						'pid' => '###CURRENT_PID###',
					),
					'module' => array('name' => 'wizard_list'),
				),
			),
		)
	),
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns("pages",$tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes("pages","tx_bzdstaffdirectory_bzd_contact_person;;;;1-1-1");
?>
