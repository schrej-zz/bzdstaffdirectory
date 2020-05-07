<?php
/***************************************************************
* Copyright notice
*
* (c) 2008-2009 Niels Pardon (mail@niels-pardon.de)
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
 * Class 'frontEndView' for the 'bzdstaffdirectory' extension. Originally
 * written for the Seminar Manager (tx_seminars).
 *
 * This class represents a basic view.
 *
 * @package TYPO3
 * @subpackage tx_bzdstaffdirectory
 *
 * @author Niels Pardon <mail@niels-pardon.de>
 */
abstract class tx_bzdstaffdirectory_pi1_frontEndView extends Tx_Oelib_Templatehelper {
	/**
	 * @var string same as plugin name
	 */
	public $prefixId = 'tx_bzdstaffdirectory_pi1';

	/**
	 * @var string the extension key
	 */
	public $extKey = 'bzdstaffdirectory';

	/**
	 * The constructor. Initializes the TypoScript configuration, initializes
	 * the flex forms, gets the template HTML code, sets the localized labels
	 * and set the CSS classes from TypoScript.
	 *
	 * @param array TypoScript configuration for the plugin
	 * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer the parent cObj content, needed for the flexforms
	 */
	public function __construct(array $configuration, \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj) {
		$this->cObj = $cObj;
		$this->init($configuration);
		$this->pi_initPIflexForm();

		$this->getTemplateCode();
		$this->setLabels();
		$this->setCSS();
	}

	/**
	 * Frees as much memory that has been used by this object as possible.
	 */
/* Destruct not necessary anymore in PHP 7.x and up JS 2019.02.10
	public function __destruct() {
		parent::__destruct();
	}
*/

	/**
	 * Renders the view and returns its content.
	 *
	 * @return string the view's content
	 */
	abstract public function render();
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/seminars/pi1/class.tx_seminars_pi1_frontEndView.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/seminars/pi1/class.tx_seminars_pi1_frontEndView.php']);
}
?>