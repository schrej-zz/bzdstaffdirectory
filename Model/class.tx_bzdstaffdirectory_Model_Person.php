<?php
/***************************************************************
* Copyright notice
*
* (c) 2008-2009 Oliver Klee <typo3-coding@oliverklee.de>
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
 * Class 'tx_bzdstaffdirectory_Model_Person' for the 'bzdstaffdirectory' extension.
 * The original code is from the Seminar Manager (tx_seminars), thanks Oli!
 *
 * This class represents a person.
 *
 * @package TYPO3
 * @subpackage tx_bzdstaffdirectory
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 * @author Mario Rimann <typo3-coding@rimann.org>
 */
class tx_bzdstaffdirectory_Model_Person extends tx_bzdstaffdirectory_Model_Generic {

	/**
	 * The list of allowed standard fields.
	 *
	 * @var array $standardFields
	 */
	private $standardFields;

	/**
	 * The constructor for this class.
	 */
	public function __construct() {
		// Sets all the standard fields.
		$this->standardFields = array(
			'first_name',
			'last_name',
			'email',
			'phone',
			'mobile_phone',
			'room',
			'officehours',
			'nickname',
			'universal_field_1',
			'universal_field_2',
			'universal_field_3',
			'universal_field_4',
			'universal_field_5',
		);
	}

	/**
	 * Returns the first name of the person.
	 *
	 * @return string the first name of the person, plain text
	 */
	public function getFirstName() {
		return $this->getAsString('first_name');
	}

	/**
	 * Returns the last name of the person.
	 *
	 * @return string the last name of the person, plain text
	 */
	public function getLastName() {
		return $this->getAsString('last_name');
	}

	/**
	 * Returns a boolean value whether the person has a title set or not.
	 *
	 * @return boolean whether a title is set or not
	 */
	public function hasTitle() {
		return $this->hasString('title');
	}

	/**
	 * Returns the title of the person.
	 *
	 * @return string the title of the person, may be empty
	 */
	public function getTitle() {
		return $this->getAsString('title');
	}

	/**
	 * Checks whether this person has any function assigned
	 *
	 * @return boolean true if at least one function is assigned, false otherwise
	 */
	public function hasFunction() {
		return (boolean)$this->getAsInteger('functions');
	}

	/**
	 * Returns the function of this person as an object.
	 *
	 * @return tx_bzdstaffdirectory_Model_Function the function of this person
	 */
	public function getFunction() {
		$dbResult = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',	// SELECT
			'tx_bzdstaffdirectory_persons_functions_mm m'
				.' left join tx_bzdstaffdirectory_functions l'
				.' on m.uid_foreign=l.uid',	// FROM
			'm.uid_local IN(' . $this->getUid() .')'
				.' AND l.hidden=0 AND l.deleted=0',	//WHERE
			'',	// GROUP BY
			'm.sorting',	// ORDER BY
			'1'	//LIMIT
		);

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($dbResult) > 0)	{
			$relation = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($dbResult);
			$functionMapper = Tx_Oelib_MapperRegistry::get('tx_bzdstaffdirectory_Mapper_Function');
			$function = $functionMapper->find($relation['uid_foreign']);

			// try to localize if we're not in the default language
			if ($languageAspect->getLegacyLanguageMode() > 0) {
				$function = $functionMapper->overlayRecord($function, $languageAspect->getLegacyLanguageMode());
/*
			if ($GLOBALS['TSFE']->sys_language_uid > 0) {
				$function = $functionMapper->overlayRecord($function, $GLOBALS['TSFE']->sys_language_uid);
*/
			}

		}

		return $function;
	}

	/**
	 * Returns the age of the person calculated from the birthdate and
	 * the current date.
	 *
	 * @return integer the age in years
	 */
	public function getAge() {
		$birthDate = $this->getBirthdate();

		$yearDiff  = date("Y") - $birthDate->format('Y');
		$monthDiff = date("m") - $birthDate->format('m');
		$dayDiff   = date("d") - $birthDate->format('d');
		if ($monthDiff < 0) {
			// today is at leat in the month before the birthday
			$yearDiff--;
		} elseif (($monthDiff==0) && ($dayDiff < 0)) {
			// today is in the same month as the birthday, but
			// before the birthday
			$yearDiff--;
		}

		return $yearDiff;
	}

	/**
	 * Returns the value of standard fields. A standard field is a simple
	 * text field in the database which can be returned as an unprocessed
	 * string.
	 *
	 * @throws Exception if $key is empty
	 * @throws Exception if $key is not in the array of valid keys
	 *
	 * @param string the key/fieldname of the field to return
	 *
	 * @return string the data for $key, may be empty
	 */
	public function getStandardField($key) {
		if (empty($key)) {
			throw new Exception('$key must not be empty!');
		}

		if (!in_array($key, $this->standardFields)) {
			throw new Exception($key . ' was an illegal key!');
		}

		return $this->getAsString($key);
	}

	/**
	 * Checks whether the person has a certain standard field set or not.
	 *
	 * @throws Exception if $key is empty
	 * @throws Exception if $key is not in the array of valid keys
	 *
	 * @param string the key/fieldname of the field to return
	 *
	 * @return boolean true if the requested field is not empty, false otherwise
	 */
	public function hasStandardField($key) {
		if (empty($key)) {
			throw new Exception('$key must not be empty!');
		}

		if (!in_array($key, $this->standardFields)) {
			throw new Exception($key . ' was an illegal key!');
		}

		return $this->hasString($key);
	}

	/**
	 * Returns the list of all standard fields.
	 *
	 * @return array all standard fields ($this->standardFields)
	 */
	public function getStandardFieldList() {
		return $this->standardFields;
	}

	/**
	 * Returns the birth date as DateTime object.
	 *
	 * @return DateTime the birth date of the person
	 */
	public function getBirthdate() {
		$result = new DateTime(
			strftime(
				'%Y-%m-%d',
				$this->getAsInteger('date_birthdate')
			)
		);

		return $result;
	}

	/**
	 * Checks whether the person has a birth date defined.
	 *
	 * @return boolean true if a birth date is set, false otherwise
	 */
	public function hasBirthDate() {
		return $this->hasInteger('date_birthdate');
	}

	/**
	 * Checks whether the person has a XING profile URL set.
	 *
	 * @return boolean true if the field is set, false otherwise
	 */
	public function hasXingProfile() {
		return $this->hasString('xing_profile_url');
	}

	/**
	 * Returns the link to the XING profile of the person.
	 *
	 * @return string the URL to the XING profile, may be empty
	 */
	public function getXingProfileLink() {
		return $this->getAsString('xing_profile_url');
	}

	/**
	 * Checks whether this person has set, since when he/she is in the company.
	 *
	 * @return boolean whether the person has the date_incompany field set
	 */
	public function hasDateInCompany() {
		return $this->hasInteger('date_incompany');
	}

	/**
	 * Returns the date since when this person is in the company.
	 *
	 * @return DateTime the date since when this person is in the company
	 */
	public function getDateInCompany() {
		$result = new DateTime(
			strftime(
				'%Y-%m-%d',
				$this->getAsInteger('date_incompany')
			)
		);

		return $result;
	}

	/**
	 * Checks whether this person has an opinion stored.
	 *
	 * @return boolean whether the field opinion is set and non empty
	 */
	public function hasOpinion() {
		return $this->hasString('opinion') && $this->getAsString('opinion') != '';
	}

	/**
	 * Checks whether this person has tasks stored.
	 *
	 * @return boolean whether the field tasks is set and non empty
	 */
	public function hasTasks() {
		return $this->hasString('tasks') && $this->getAsString('tasks') != '';
	}

	/**
	 * Returns the opinion of this person.
	 *
	 * @return string the opinion, may be empty
	 */
	public function getOpinion() {
		return $this->getAsString('opinion');
	}

	/**
	 * Returns the tasks of this person.
	 *
	 * @return string the tasks, may be empty
	 */
	public function getTasks() {
		return $this->getAsString('tasks');
	}

	/**
	 * Checks whether this person is member of any teams. For this, only the
	 * relation counter is checked.
	 *
	 * @return boolean whether this person is member of any team
	 */
	public function hasTeams() {
		return (boolean)$this->getAsInteger('usergroups');
	}

	/**
	 * Returns a list of teams on which this person is member of.
	 *
	 * @return Tx_Oelib_List a list of team objects
	 */
	public function getTeams() {
		$teams = new Tx_Oelib_List();

		$dbResult = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',	// SELECT
			'tx_bzdstaffdirectory_persons_usergroups_mm m'
				.' left join tx_bzdstaffdirectory_groups g'
				.' on m.uid_foreign=g.uid',	// FROM
			'm.uid_local IN(' . $this->getUid() .')'
				.' AND g.hidden=0 AND g.deleted=0',	//WHERE
			'',	// GROUP BY
			'm.sorting',	// ORDER BY
			''	//LIMIT
		);

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($dbResult) > 0)	{
			while($member = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($dbResult))	{
				$teamMapper = Tx_Oelib_MapperRegistry::get('tx_bzdstaffdirectory_Mapper_Team');
				$currentTeam = $teamMapper->find($member['uid_foreign']);

				// try to localize if we're not in the default language
				if ($languageAspect->getLegacyLanguageMode() > 0) {
					$currentTeam = $teamMapper->overlayRecord($currentTeam, $languageAspect->getLegacyLanguageMode());
/*				if ($GLOBALS['TSFE']->sys_language_uid > 0) {
					$currentTeam = $teamMapper->overlayRecord($currentTeam, $GLOBALS['TSFE']->sys_language_uid);
*/					
				}

				$teams->add($currentTeam);
			}
		}

		return $teams;
	}

	/**
	 * Checks whether this person is assigned to any location. For this, only
	 * the relation counter is checked.
	 *
	 * @return boolean whether this person is assigned to any team
	 */
	public function hasLocation() {
		return (boolean)$this->getAsInteger('location');
	}

	/**
	 * Returns a list of locations to which this person is assigned.
	 *
	 * @return Tx_Oelib_List a list of location objects
	 */
	public function getLocations() {
		$locations = new Tx_Oelib_List();

		$dbResult = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',	// SELECT
			'tx_bzdstaffdirectory_persons_locations_mm m'
				.' left join tx_bzdstaffdirectory_locations l'
				.' on m.uid_foreign=l.uid',	// FROM
			'm.uid_local IN(' . $this->getUid() .')'
				.' AND l.hidden=0 AND l.deleted=0',	//WHERE
			'',	// GROUP BY
			'm.sorting',	// ORDER BY
			''	//LIMIT
		);

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($dbResult) > 0)	{
			while($relation = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($dbResult))	{
				$locationMapper = Tx_Oelib_MapperRegistry::get('tx_bzdstaffdirectory_Mapper_Location');
				$currentLocation = $locationMapper->find($relation['uid_foreign']);

				// try to localize if we're not in the default language
				if (TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController->sys_language_uid > 0) {
					$currentLocation = $locationMapper->overlayRecord($currentLocation, TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController->sys_language_uid);
/*
				if ($GLOBALS['TSFE']->sys_language_uid > 0) {
					$currentLocation = $locationMapper->overlayRecord($currentLocation, $GLOBALS['TSFE']->sys_language_uid);
*/
				}

				$locations->add($currentLocation);
			}
		}

		return $locations;
	}

	/**
	 * Checks whether this person has an image stored.
	 *
	 * @return boolean true if an image is set, false otherwise
	 */
	public function hasImage() {
		return $this->hasString('image');
	}

	/**
	 * Returns the filename of the stored image for this person.
	 *
	 * @return string the filename of the portrait, may be empty
	 */
	public function getImage() {
		return $this->getAsString('image');
	}

	/**
	 * Checks whether this person has a gender defined.
	 *
	 * @return boolean true if no gender is set (value = 0), false otherwise
	 */
	public function hasGender() {
		return $this->hasInteger('gender');
	}

	/**
	 * Returns the gender of this person, represented as an integer:
	 * 0 = not set
	 * 1 = male
	 * 2 = female
	 *
	 * @return integer the gender of this person, may be zero but not null
	 */
	public function getGender() {
		return $this->getAsInteger('gender');
	}

	/**
	 * Checks whether this person has any files stored in it's record.
	 *
	 * @return boolean true if at least one file is assigned, false otherwise
	 */
	public function hasFiles() {
		return ($this->getAsString('files') != '');
	}

	/**
	 * Returns a list of file names that are assigned to this person.
	 *
	 * @return array the file names
	 */
	public function getFiles() {
		$result = array();

		if (!$this->hasFiles()) {
			return $result;
		}

		return explode(',', $this->getAsString('files'));
	}

	/**
	 * Checks whether a phone number was stored for this person.
	 *
	 * @return boolean true if phone number is stored, false otherwise
	 */
	public function hasPhone() {
		return $this->hasString('phone');
	}

	/**
	 * Returns the timestamp of the last modification.
	 *
	 * @return integer the timestamp of last modification
	 */
	public function getLastUpdateTimestamp() {
		return $this->getAsInteger('tstamp');
	}

	/**
	 * Checks whether the neccessary data for rendering a vCard is available.
	 * To fulfill the requirements, the following conditions must be true:
	 * - the person has at least one location assigned
	 * - the first assigned location has an address stored
	 * - the person has a phone number
	 *
	 * Other fields like name/email are mandatory, too. But they are required
	 * fields in the backend.
	 *
	 * @return boolean true if all is set, false otherwise
	 */
	public function hasVcfData() {
		if (!$this->hasLocation()) {
			return false;
		}

		if (!$this->getLocations()->current()->hasAddress()) {
			return false;
		}

		if (!$this->hasFunction()) {
			return false;
		}

		if (!$this->hasPhone()) {
			return false;
		}

		return true;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzdstaffdirectory/Model/class.tx_bzdstaffdirectory_Model_Person.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzdstaffdirectory/Model/class.tx_bzdstaffdirectory_Model_Person.php']);
}
?>