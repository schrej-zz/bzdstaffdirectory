<?php

########################################################################
# Extension Manager/Repository config file for ext "bzdstaffdirectory".
#
# Auto generated 21-05-2012 23:27
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'BZD Staff Directory',
	'description' => 'An extension to show your staff or club-members in different output styles (single persons, lists).',
	'category' => 'plugin',
	'shy' => 0,
	'conflicts' => 'dbal',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => 'pages',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author' => 'Mario Rimann',
	'author_email' => 'typo3-coding@rimann.org',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '0.11.3',
	'constraints' => array(
		'depends' => array(
			'php' => '5.5.0',
			'typo3' => '6.0.0-10.4.99',
			'oelib' => '1.0.0-',
		),
		'conflicts' => array(
			'dbal' => '',
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
);

?>
