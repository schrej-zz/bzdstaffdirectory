<?php
/***************************************************************
* Copyright notice
*
* (c) 2009-2012 Mario Rimann (typo3-coding@rimann.org)
* All rights reserved
*
* This script is part of the TYPO3 project. The TYPO3 project is
* free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/



/**
 * Class 'tx_bzdstaffdirectory_Mapper_Location' for the 'bzdstaffdirectory' extension.
 *
 * This class represents a mapper for locations.
 *
 * @package TYPO3
 * @subpackage tx_bzdstaffdirectory
 *
 * @author Mario Rimann <typo3-coding@rimann.org>
 */
class tx_bzdstaffdirectory_Mapper_Location extends tx_bzdstaffdirectory_Mapper_Generic {
	/**
	 * @var string the name of the database table for this mapper
	 */
	protected $tableName = 'tx_bzdstaffdirectory_locations';

	/**
	 * @var string the model class name for this mapper, must not be empty
	 */
	protected $modelClassName = 'tx_bzdstaffdirectory_Model_Location';

	/**
	 * The fields to overlay when localizing this object
	 *
	 * @var array
	 */
	protected $fieldsToOverlay = array(
		'title',
		'address',
		'zip',
		'city',
		'country',
		'infopage',
	);
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzdstaffdirectory/Mapper/class.tx_bzdstaffdirectory_Mapper_Location.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzdstaffdirectory/Mapper/class.tx_bzdstaffdirectory_Mapper_Location.php']);
}
?>