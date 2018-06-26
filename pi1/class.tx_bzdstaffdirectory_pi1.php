<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005-2012 Mario Rimann (typo3-coding@rimann.org)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/



/**
 * Plugin 'BZD Staff Directory' for the 'bzdstaffdirectory' extension.
 *
 * @author	Mario Rimann <typo3-coding@rimann.org>
 */

// I'm too lazy to change the stuff to make autoloading work

$extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('bzdstaffdirectory');

require_once $extPath . 'Mapper/class.tx_bzdstaffdirectory_Mapper_Generic.php';
require_once $extPath . 'Mapper/class.tx_bzdstaffdirectory_Mapper_Function.php';
require_once $extPath . 'Mapper/class.tx_bzdstaffdirectory_Mapper_Location.php';
require_once $extPath . 'Mapper/class.tx_bzdstaffdirectory_Mapper_Person.php';
require_once $extPath . 'Mapper/class.tx_bzdstaffdirectory_Mapper_Team.php';

require_once $extPath . 'Model/class.tx_bzdstaffdirectory_Model_Generic.php';
require_once $extPath . 'Model/class.tx_bzdstaffdirectory_Model_Function.php';
require_once $extPath . 'Model/class.tx_bzdstaffdirectory_Model_Location.php';
require_once $extPath . 'Model/class.tx_bzdstaffdirectory_Model_Person.php';
require_once $extPath . 'Model/class.tx_bzdstaffdirectory_Model_Team.php';

class tx_bzdstaffdirectory_pi1 extends Tx_Oelib_TemplateHelper {
	var $prefixId = 'tx_bzdstaffdirectory_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_bzdstaffdirectory_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey = 'bzdstaffdirectory';	// The extension key.
	var $pi_checkCHash = TRUE;
	var $langArr;
	var $sys_language_mode;

	/**
	 * [Put your description here]
	 */
	function main($content,$conf)	{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		// Init FlexForm configuration for plugin:
		$this->pi_initPIflexForm();
		$this->getTemplateCode();

		$this->arrConf = unserialize($GLOBALS["TYPO3_CONF_VARS"]["EXT"]["extConf"]['bzdstaffdirectory']);


		// Define the path to the upload folder
		$this->uploadFolder = 'uploads/tx_bzdstaffdirectory/';

		// Deinfe the path to the media folder
		$this->mediaFolder = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('bzdstaffdirectory') . 'media/';

		// load available syslanguages
		$this->initLanguages();
		// sys_language_mode defines what to do if the requested translation is not found
		$this->sys_language_mode = $this->conf['sys_language_mode']?$this->conf['sys_language_mode'] : $GLOBALS['TSFE']->sys_language_mode;

		// Get Listing-Type from Flexform-Settings
		$this->code = (string)strtoupper(trim($this->pi_getFFvalue($this->cObj->data['pi_flexform'],'listtype','s_welcome')));

		// Get Configuration Data (TypoScript Setup). Depending on "CODE" (what to show)
		$this->lconf = $this->conf[$this->code."."];


		switch($this->code)
		{
			case "TEAMLIST"	:	$content .= $this->show_teamlist();
								break;
			case "BOX"		:	$content .= $this->show_box();
								break;
			case "DETAIL"	:	$content .= $this->renderDetailView();
								break;
			default			:	$content .= $this->pi_getLL('error_noListType');
								break;
		}
		return $this->pi_wrapInBaseClass($content);
	}


	/**
	 * Calls the view class for the detail view and returns the HTML code for
	 * the view.
	 *
	 * @return string HTML source for the detail view
	 */
	function renderDetailView() {
		if (!intval($this->piVars['showUid'])) {
			return '';
		}


		if (intval($this->piVars['backPid'])) {
			$this->conf['DETAIL.']['backPid'] = intval($this->piVars['backPid']);
		}

		if (isset($this->piVars['vcf'])) {
			$detailViewClassName = 'tx_bzdstaffdirectory_pi1_frontEndVcfView';
			require_once(__DIR__ . '/class.tx_bzdstaffdirectory_pi1_frontEndVcfView.php');
		} else {
			$detailViewClassName = 'tx_bzdstaffdirectory_pi1_frontEndDetailView';
			require_once(__DIR__ . '/class.tx_bzdstaffdirectory_pi1_frontEndDetailView.php');
		}

		$detailView = new $detailViewClassName(
			intval($this->piVars['showUid']),
			$this->conf,
			$this->cObj
		);
		$result = $detailView->render();

		$detailView->__destruct();
		unset($detailView);

		if (isset($this->piVars['vcf'])) {
			//		header("Pragma: public"); // required
//		header("Expires: 0");
//		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//		header("Cache-Control: private",false); // required for certain browsers
		header("Content-Type: text/x-vcard");
		header('Content-Disposition: attachment; filename="vCard.vcf";');
//		header("Content-Transfer-Encoding: binary");
//		header("Content-Length: 20".(2));
			echo trim($result);
			exit;
		}
		return $result;
	}



	/**
	 * Generates a teamlist. The teamleaders are shown on top of the list, then the rest of the team members follow.
	 *
	 * @return	string		the complete HTML Output for this module
	 */
	function show_teamlist()	{
		$content = '';
		$errorMessage = '';

		// Define the team UID(s) that are selected in the flexform. This is a comma separated list if more than one UID.
		$this->teamUidList = $this->getConfValueString('usergroup','s_teamlist');

		// define the detail page (either from the TS setup, or from the FlexForm).
		$this->detailPage = $this->getConfValueInteger('detailPage', 's_teamlist');

		// Define the sortOrder
		$sortOrder = $this->getConfValueString('sortOrder', 's_teamlist');
		if ($sortOrder) {
			$sortDirection = ' ' . $this->getConfValueString('sortOrderDirection', 's_teamlist');
		}
		$this->teamListSortOrder = $sortOrder . $sortDirection;

		// Check if a detail page has been defined.
		if (!empty($this->detailPage)) {
			if ($this->getConfValueBoolean('ignoreGroupSelection','s_teamlist')) {
				// Define the PID for the startingpoint
				$startingpoint = $this->getAllowedPids();

				$this->teamMembersUIDArray = $this->getTeamMembersFromStartingpoint($startingpoint, $this->teamListSortOrder);

				// Initialize the team leaders array as an empty array as no team leaders
				// will be shown when all persons are shown and the team selection is ignored!
				$this->teamLeadersUIDArray = array();
			} else {
				if ($this->teamUidList) {
					// Select the team leaders
					$this->teamLeadersUIDArray = $this->getTeamLeadersFromMM(
						$this->teamUidList,
						$this->getConfValueBoolean('ignoreTeamLeaders', 's_teamlist')
					);

					// select the team members
					$this->teamMembersUIDArray = $this->getTeamMembersFromMM($this->teamUidList, $this->teamListSortOrder);
				} else {
					$errorMessage .= $this->pi_getLL('error_noGroupUID');
				}
			}
		} else {
			// no detail page defined
			$errorMessage .= $this->pi_getLL('error_noDetailPage');
		}

		// only call the real output functions if no error occured until now,
		// show the error message otherwise.
		if ($errorMessage == '') {
			// switch to the selected list style
			switch($this->getConfValueString('liststyle', 's_teamlist')) {
				case 'images'	:	$content .= $this->showTeamlistImages();
									break;
				case 'names'	:	$content .= $this->showTeamlistNames();
									break;
				case 'grouped'	:	$content .= $this->showTeamListGrouped();
									break;
				case 'mixed'	:	// Fallthrough is intended!
				default			:	$content .= $this->showTeamlistMixed();
									break;
			}
		} else {
			$content .= $errorMessage;
		}

		return $content;
	}

	/**
	 * Returns the HTML code needed to show the jump menu on the front end.
	 *
	 * @param	array		array of complete team records
	 *
	 * @return	string		the HTML code for the jump menu
	 */
	function getJumpMenu($teams) {
		$result = '';

		if (count($teams)) {
			$options = '';
			foreach ($teams as $currentTeam) {
				$url = $this->cObj->getTypoLink_URL(
					$GLOBALS['TSFE']->id
				);
				$url .= '#bzdgroup_'.$currentTeam['uid'];
				$this->setMarker('jumpmenu_id', $url);
				$this->setMarker(
					'jumpmenu_name',
					htmlspecialchars($currentTeam['group_name'])
				);
				// merge the marker content with the template
				$options .= $this->getSubpart('JUMPMENU_ITEM');
			}
		}

		// merge the jumpmenu template with some content
		$this->setMarker('jumpmenu_options', $options);
		$this->setMarker('jumpmenu_please_choose', $this->pi_getLL('label_pleaseChoose'));
		$result = $this->getSubpart('TEMPLATE_JUMPMENU');

		return $result;
	}

	/**
	 * Returns an array containing an associative array for each group.
	 *
	 * @param	integer		a single team UID to retrieve from database
	 * @param	boolean		whether to retrieve localized records, dafault is false
	 *
	 * @return	array		associative array containing all informations for the requested team, may be null
	 */
	function getTeamArray($teamUID, $doTranslate = false) {
		$whereClause = 'l18n_parent = 0'
			.Tx_Oelib_Db::enableFields('tx_bzdstaffdirectory_groups')
			.' AND uid ='.$teamUID;
		$res_groups = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',	// SELECT
			'tx_bzdstaffdirectory_groups',	// FROM
			$whereClause,	//WHERE
			'',	// GROUP BY
			'',	// ORDER BY
			'1'	//LIMIT
		);
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_groups) > 0) {
			while ($currentGroup = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_groups)) {
				// get the translated record if the content language is not the default language
				if ($GLOBALS['TSFE']->sys_language_content && $doTranslate) {
					$OLmode = ($this->sys_language_mode == 'strict'?'hideNonTranslated':'');
					$translated_record = $GLOBALS['TSFE']->sys_page->getRecordOverlay(
						'tx_bzdstaffdirectory_groups',
						$currentGroup,
						$GLOBALS['TSFE']->sys_language_content,
						$OLmode
					);
					if ($this->sys_language_mode != 'strict'
						OR !empty($translated_record['l18n_parent'])
						) {
						// found a valid translation, return the person with the translated information.
						$currentGroup = $translated_record;
					} else {
						// There's an empty translation found (can only happen if sys_language_mode = strict).
						// Act as if NO group could be retrieved from the database.
						$currentGroup = NULL;
					}
				} else {
					// no translation requested or available - return the record in default language
				}

				// Add the group to the groups array if it is valid
				if ($currentGroup != NULL) {
					$teamArray = $currentGroup;
				}
			}
		} else {
			// no team found
			$teamArray = NULL;
		}

		return $teamArray;
	}

	/**
	 * Generates the HTML output for the TEAMLIST NAMES (just a list of names)
	 *
	 * @return	string	The HTML output
	 */
	function showTeamlistNames() {
		$content = '';
		$content .= 'ERROR: Feature not implemented.';
		return $content;
	}

	/**
	 * Generates the HTML output for the TEAMLIST IMAGES (just a list of images)
	 *
	 * @return	string	The HTML output
	 */
	function showTeamlistImages() {
		$content = '';
		$content .= 'ERROR: Feature not implemented.';
		return $content;
	}

	/**
	 * Generates the HTML output for the TEAMLIST GROUPED (list of team-members
	 * from multiple teams, grouped by team)
	 *
	 * @return	string		the HTML output
	 */
	function showTeamListGrouped() {
		$content = '';

		$this->setLabelFields();

		// get the teams (ordered as in the content element) and add them to an
		// array.
		// Does the user want do ignore the group selection?
		if ($this->getConfValueBoolean('ignoreGroupSelection', 's_teamlist')) {
			// Then it fetchs all available groups in startingpoint.
			$startingpoint = $this->getAllowedPids();
			$selectedGroups = implode(',', $this->getGroupsFromStartingpoint($startingpoint));
		}
		else {
			// Otherwise it fetchs only chosen groups.
			$selectedGroups = $this->getConfValueString('usergroup', 's_teamlist');
		}

		foreach (explode(',', $selectedGroups) as $currentGroup) {
			$team = $this->getTeamArray($currentGroup, true);
			if ($team) {
				$teams[] = $team;
			}
		}

		$content .= $this->getJumpMenu($teams);

		// create a list for each team and prepend it with an anchor
		foreach ($teams as $currentTeam) {
			$groupLeaderUIDs = $this->getTeamLeadersFromMM(
				$currentTeam['uid'],
				$this->getConfValueBoolean('ignoreTeamLeaders', 's_teamlist')
			);
			$groupMemberUIDs = $this->getTeamMembersFromMM(
				$currentTeam['uid'],
				$this->teamListSortOrder
			);

			// set the header for each team
			$currentTeamHTML = '';
			$this->setMarker(
				'anchor',
				'bzdgroup_'.$currentTeam['uid']
			);
			$this->setMarker(
				'team_name',
				$currentTeam['group_name']
			);
			$currentTeamHeader = $this->getSubpart('TEAM_HEADER');

			// Now output the team leaders and members
			foreach($groupLeaderUIDs as $currentLeaderUID) {
				$currentTeamHTML .= $this->showPersonInTeamList(
					$currentLeaderUID,
					true
				);
			}
			foreach($groupMemberUIDs as $currentMemberUID) {
				// Don't display this person in the team members section if it is a teamleader!
				if (!in_array($currentMemberUID, $groupLeaderUIDs)) {
					$currentTeamHTML .= $this->showPersonInTeamList(
						$currentMemberUID,
						false
					);
				}
			}

			// Combine anything and return it
			$content .= $currentTeamHeader
						.$this->getSubpart('GROUPED_LIST_HEADER')
						.$currentTeamHTML
						.$this->getSubpart('GROUPED_LIST_FOOTER');
		}

        $content =
            $this->getSubpart('GROUPED_LIST_ALL_HEADER')
            .$content
            .$this->getSubpart('GROUPED_LIST_ALL_FOOTER');

		return $content;
	}

	/**
	 * Gets all the groups from a given startingpoint.
	 *
	 * @param string comma separated list of PIDs
	 *
	 * @return array array of the group uids
	 */
	function getGroupsFromStartingpoint($pidList) {
		$groups = array();

		$res_groups = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid',	// SELECT
			'tx_bzdstaffdirectory_groups',	// FROM
			'pid IN(' . $pidList . ') AND l18n_parent = 0',	//WHERE
			'',	// GROUP BY
			'',	// ORDER BY
			''	//LIMIT
		);

		while($group = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_groups))	{
			$groups[] = $group['uid'];
		}


		return $groups;
	}

	/**
	 * Generates the HTML Output for the TEAMLIST MIXED (=names and images (the default))
	 *
	 * @return	string		the HTML output
	 */
	function showTeamlistMixed() {
		$this->setLabelFields();

		// create and display the list header
		$content = $this->getSubpart('LIST_HEADER');

		// output the team leaders
		foreach($this->teamLeadersUIDArray as $currentLeaderUID) {
			$content .= $this->showPersonInTeamList($currentLeaderUID, true);
		}

		if (count($this->teamMembersUIDArray) < 1) {
			// ERROR: There are no team members found for this/these team(s).
			// This can happen and won't be treated as an error at the moment (may be a team consists only of team leaders).
		} else {
			if (!is_array($this->teamLeadersUIDArray)) {
				// There are no team leaders (empty array), but there are team members:

				// Call the "output person record" function once per team member.
				foreach ($this->teamMembersUIDArray as $memberUID) {
					$content .= $this->showPersonInTeamList($memberUID, false);
				}
			} else {
				// There are team leaders!

				// Call the "output person record" function once per team member.
				foreach ($this->teamMembersUIDArray as $memberUID) {
					// Don't display this person in the team members section if it is a teamleader!
					if (!in_array($memberUID, $this->teamLeadersUIDArray)) {
						$content .= $this->showPersonInTeamList($memberUID, false);
					}
				}
			}
		}

		// add the table footer
		$content .= $this->getSubpart('LIST_FOOTER');

		return $content;
	}

	/**
	 * Returns the uids of the contact person(s) that are selected in the
	 * flexform config of the current plugin.
	 *
	 * @return array the uid(s) of the contact person(s), may be empty
	 */
	function getContactPersonFromPlugin() {
		$config = $this->getConfValueString(
			'source_plugin',
			's_contactbox'
		);

		if ($config == '') {
			return array();
		}

		return explode(
			',',
			$config
		);
	}

	/**
	 * Returns the leaders from the selected teams and returns their uid as
	 * an array.
	 *
	 * @return array the uid(s) of the contact person(s), may be empty
	 */
	function getContactPersonFromTeams() {
		// read the selected teams
		$selectedTeams = $this->getConfValueString(
			'source_teamleaders',
			's_contactbox'
		);

		if ($selectedTeams == '') {
			return array();
		}

		// fetch the team leaders for each team
		$contactPersons = $this->getTeamLeadersFromMM(
			$selectedTeams,
			false
		);

		return $contactPersons;
	}

	/**
	 * Returns the uids of the contact person for this page.
	 * If this page has a contact person set, this person will be returned.
	 * If this page hat no contact person set, the parent pages will be checked one by one.
	 *
	 * @param	integer		the uid of the page
	 *
	 * @return	array		the uid(s) of the contact person(s)
	 */
	function getContactPersonforPage($pageUid) {
		$result = array();
		$isOK = false;
		while (!$isOK && $pageUid != 0) {
			// get the UID of the person respective for this page
			$res_person = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'uid_foreign',	// SELECT
				'tx_bzdstaffdirectory_pages_persons_mm',	// FROM
				'uid_local = '.$pageUid,	//WHERE
				'',	// GROUP BY
				'sorting',	// ORDER BY
				''	//LIMIT
			);

			if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_person) > 0) {
				// found at least one contact person
				// add it to the array, end the loop (don't check the parent page(s)!)
				while ($res_person_line = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_person)) {
					$result[] = $res_person_line['uid_foreign'];
				}
				$isOK = true;
			} else {
				// get the UID of the next parent page
				$res_page = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'pid',	// SELECT
					'pages',	// FROM
					'uid = '.$pageUid,	//WHERE
					'',	// GROUP BY
					'',	// ORDER BY
					'1'	//LIMIT
				);
				$res_page_line = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_page);
				if ($res_page_line['pid'] != 0) {
					$pageUid = $res_page_line['pid'];
				} else {
					// the next top page ha uid 0 (zero) which is the root page
					// the root cannot have a contact person associated
					$isOK = true;
				}
			}
		}

		return $result;
	}

	/**
	 * Shows the BOX module, also known as "contact person box", on the page.
	 *
	 * @return	string		the HTML code needed to render the contact box
	 */
	function show_box()	{
		$content = '';

		// select the contact person to show in the module
		$selectSource = $this->getConfValueString(
			'source',
			's_contactbox'
		);
		switch ($selectSource) {
			case 'plugin': $personUIDs = $this->getContactPersonFromPlugin();
				break;
			case 'teamleaders': $personUIDs = $this->getContactPersonFromTeams();
				break;
			case 'page':
				// The fall-through is intended!
			default:
				$personUIDs = $this->getContactPersonForPage($GLOBALS['TSFE']->id);
		}

		if (is_array($personUIDs) && count($personUIDs) != 0) {

			// define the header, will always be shown
			if (count($personUIDs) > 1) {
				$this->setMarker('header_contactperson', $this->pi_getLL('header_contactperson_plural'));
			} else {
				$this->setMarker('header_contactperson', $this->pi_getLL('header_contactperson_singular'));
			}

			$content .= $this->getSubpart('TEMPLATE_BOX_HEADER');

			foreach ($personUIDs as $currentUID) {
				// get the details of the contact person from the database
				$person = $this->getPersonDetails($currentUID);

				// check if fetching the person's details was successful
				if ($person) {
					// check whether a translation is requested or not
					if ($GLOBALS['TSFE']->sys_language_content) {
						// a translation is requested
						$OLmode = ($this->sys_language_mode == 'strict'?'hideNonTranslated':'');
						$translated_record = $GLOBALS['TSFE']->sys_page->getRecordOverlay('tx_bzdstaffdirectory_persons', $person,$GLOBALS['TSFE']->sys_language_content, $OLmode);

						//check if a valid translation is available
						if ($this->sys_language_mode != 'strict' OR !empty($translated_record['l18n_parent'])) {
							// found a valid translation, show the person with the translated information
							$content .= $this->showPersonBox($translated_record);
						} else {
							$content .= $this->pi_getLL('error_contactPersonNotTranslated');
						}
					} else {
						// no translation requested
						$content .= $this->showPersonBox($person);
					}
				} else {
					// $person is NULL
					$content .= $this->pi_getLL('error_personDetailsNotFetched');
				}

				// unset the person array for next loop
				unset($person);
			}
			$content .= $this->getSubpart('TEMPLATE_BOX_FOOTER');
		} else {
			// no person is found for this page - that's ok, no error message
		}

		return $content;
	}

	/**
	 * Generates the HTML Code to show a contact person box on the page
	 *
	 * @param	array		associative array containing all the information of a person record
	 *
	 * @return	string		the html code
	 */
	function showPersonBox($person) {
		$content = '';
		
		// Define the detail-Page (either from the TS setup, or from the FlexForm-Setting).
		$this->detailPage = $this->getConfValueInteger('detailPage', 's_contactbox');

		if ($this->hasValue('title', $person)) {
			$this->setMarker('title', $this->getValue('title', $person, true));
			$this->setMarker('label_title', $this->pi_getLL('label_title'));
		} else {
			$this->hideSubparts('title', 'field_wrapper');
		}

		if ($this->hasValue('first_name', $person)) {
			$this->setMarker('first_name', $this->getValue('first_name', $person, true));
			$this->setMarker('label_first_name', $this->pi_getLL('label_first_name'));
		} else {
			$this->hideSubparts('first_name', 'field_wrapper');
		}

		if ($this->hasValue('last_name', $person)) {
			$this->setMarker('last_name', $this->getValue('last_name', $person, true));
			$this->setMarker('label_last_name', $this->pi_getLL('label_last_name'));
		} else {
			$this->hideSubparts('last_name', 'field_wrapper');
		}

		if ($this->hasValue('function', $person)) {
			$this->setMarker('function', $this->getValue('function', $person, true));
			$this->setMarker('label_function', $this->pi_getLL('label_function'));
		} else {
			$this->hideSubparts('function', 'field_wrapper');
		}

		// define the marker for the image (always shown)
		$this->setMarker('image', $this->getImage($person));

		// create the link to the detail page
		$linkToDetailPage = $this->linkToDetailPage($this->pi_getLL('label_link_detail'), $this->getValue('uid', $person));
		$this->setMarker('link_detail', $linkToDetailPage);

		// merge the marker content with the template
		$content .= $this->getSubpart('TEMPLATE_BOX_PERSON');

		return $content;
	}

	/**
	 * Returns a date formatted as a string.
	 * The date is formatted as given by the TS-Setup. Depending on the function
	 * that calls this function, the second parameter represents the name of the
	 * TS config to take. If the second parameter is an empty string, the
	 * default value is taken.
	 *
	 * @param	integer		the date as integer value
	 * @param	string		the config name, which date config to take
	 *
	 * @return	string		formatted date
	 */
	function getFormattedDate($dateInt, $format = 'dateFormatYMD') {
		$result = '';
		$result = strftime($this->getConfValueString($format), $dateInt);
		return $result;
	}

	/**
	 * Returns the age of the person as a string, followed by the word "years".
	 *
	 * @param	integer		the birthdate of the person as integer value
	 *
	 * @return	string		the person's age + " years"
	 */
	function getAge($birthdate) {
		$year_diff  = date("Y") - strftime('%Y', $birthdate);
		$month_diff = date("m") - strftime('%m', $birthdate);
		$day_diff   = date("d") - strftime('%d', $birthdate);
		if ($month_diff < 0) {
			$year_diff--;
		} elseif (($month_diff==0) && ($day_diff < 0)) {
			$year_diff--;
		}

		return $year_diff . ' ' . $this->pi_getLL('years');
	}

	/**
	 * Returns the HTML Code to show the list of files that are stored for this person.
	 *
	 * @param	array		associative array containing all the information
	 *
	 * @return	string		HTML Code
	 */
	function getFileList($person) {
		$files = explode(',', $person['files']);

		// build the array of file extensions that should get opened in a new window
		$extensionArray = explode(',', $this->getConfValueString('fileExtensionsToOpenInNewWindow'));

		$fileList = '<ul>';
		foreach($files as $filename) {
// FIXME: Define the path in a global place!
			// get the extension of the current file
			$fileExtension = end(explode('.', $filename));

			if (in_array($fileExtension, $extensionArray)) {
				// this type of files need to be opened in a new window (target="_blank")
				$fileList .= '<li><a href="uploads/tx_bzdstaffdirectory/'. $filename .'" target="_blank">' . $filename . '</a></li>';
			} else {
				// all other files will be opened in the same window
				$fileList .= '<li><a href="uploads/tx_bzdstaffdirectory/'. $filename .'" target="_top">' . $filename . '</a></li>';
			}
		}
		$fileList .= '</ul>';

		return $fileList;
	}

	/**
	 * Returns the HTML Code needed to show a list of group names on which a
	 * user is a member.
	 *
	 * If the team record is linked to a page that contains additional
	 * information about this team, the entry in the returned list will be
	 * linked to this page.
	 *
	 * @param	integer		the UID of the person to look up
	 *
	 * @return	string		HTML Code (unordered list if more than one group)
	 */
	function getGroups($uid) {
		$result = '';
		$memberOf = $this->getMemberOfGroups($uid);
		if (count($memberOf) > 1) {
			$memberOfList = '';

			// we have more than one group and need to build a list
			foreach ($memberOf as $actualGroupUID) {
				$actualGroup = $this->getTeamDetails($actualGroupUID, true);

				// check if the team name should be linked to the team page
				if ($actualGroup['infopage']) {
					$teamName = $this->pi_linkTP(
						htmlspecialchars($actualGroup['group_name']),
						array(),
						true,
						$actualGroup['infopage']
					);
				} else {
					$teamName = htmlspecialchars($actualGroup['group_name']);
				}
				$memberOfList .= '<li>' . $teamName . '</li>';
			}
			$result = '<ul>' . $memberOfList . '</ul>';
			$this->setMarker('label_groups', $this->pi_getLL('label_groups_plural'));
		} else {
			// just one single group found, no list is needed
			$actualGroup = $this->getTeamDetails($memberOf[0], true);

			// check if the team name should be linked to the team page
			if ($actualGroup['infopage']) {
				$result = $this->pi_linkTP(
					htmlspecialchars($actualGroup['group_name']),
					array(),
					true,
					$actualGroup['infopage']
				);
			} else {
				$result = htmlspecialchars($actualGroup['group_name']);
			}
			$this->setMarker('label_groups', $this->pi_getLL('label_groups_singular'));
		}

		return $result;
	}

	/**
	 * Returns the HTML Code needed to show a list of location names on which a
	 * user is located.
	 *
	 * If the location record is linked to a page that contains additional
	 * information about this location, the entry in the returned list will be
	 * linked to this page.
	 *
	 * @param	integer		the UID of the person to look up
	 *
	 * @return	string		HTML Code (unordered list if more than one location)
	 */
	function getLocationList($uid) {
		$result = '';
		$memberOf = $this->getLocationsForPerson($uid);
		if (count($memberOf) > 1) {
			$memberOfList = '';

			// we have more than one location and need to build a list
			foreach ($memberOf as $currentLocationUid) {
				$currentLocation = $this->getLocationDetails($currentLocationUid, true);

				// check if the location title should be linked to a detail page
				if ($currentLocation['infopage']) {
					$locationName = $this->pi_linkTP(
						htmlspecialchars($currentLocation['title']),
						array(),
						true,
						$currentLocation['infopage']
					);
				} else {
					$locationName = htmlspecialchars($currentLocation['title']);
				}
				$memberOfList .= '<li>' . $locationName . '</li>';
			}
			$result = '<ul>' . $memberOfList . '</ul>';
			$this->setMarker('label_location', $this->pi_getLL('label_location_plural'));
		} else {
			// just one single location found, no list is needed
			$currentLocation = $this->getLocationDetails($memberOf[0], true);

			// check if the team name should be linked to the team page
			if ($currentLocation['infopage']) {
				$result = $this->pi_linkTP(
					htmlspecialchars($currentLocation['title']),
					array(),
					true,
					$currentLocation['infopage']
				);
			} else {
				$result = htmlspecialchars($currentLocation['title']);
			}
			$this->setMarker('label_location', $this->pi_getLL('label_location_singular'));
		}

		return $result;
	}

	/**
	 * Returns the HTML Code to show the image of the person.
	 *
	 * @param	array		associative array containing all the information
	 *
	 * @return	string		the HTML Code
	 */
	function getImage($person) {
		$result = '';
		$fN = $this->getValue('image', $person);

		// Get Configuration Data (TypoScript Setup). Depending on "CODE" (what to show)
		$lconf = $this->conf[$this->code.'.'];

		if (empty($fN) && $this->getConfValueBoolean('showDummyPictures', 's_template')) {
			switch($this->getValue('gender', $person))
			{
				case 2	:	$lconf['image.']['file'] = $this->getConfValueString('dummyPictureFemale', $sheet = 's_template', true);
									break;
				case 1	:	$lconf['image.']['file'] = $this->getConfValueString('dummyPictureMale', $sheet = 's_template', true);
									break;
				case 0	:	// The fallthrough is intended.
				default	:	// no gender specified or "not defined" is selected
									break;
			}

			// just set the unisex dummy image, if this is not forbidden in the setup
			if ($lconf['image.']['file'] == '' && $this->getConfValueBoolean('showUnisexDummyImage', 's_template')) {
				$lconf['image.']['file'] = $this->getConfValueString('dummyPictureDefault', $sheet = 's_template', true);
			}
		} else {
			$lconf['image.']['file'] = 'uploads/tx_bzdstaffdirectory/' . $fN;
		}

		// Depending on the settings in the Flexform of the content object, the image will be wrapped with a link (to click enlarge the image).
		$imageconf = array();
		if ( $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'click_enlarge','s_detailview') == TRUE AND $fN != '')	{
			// Render the pop-up image with the size limitations from TS Setup.
			$popUpImageArray = $this->cObj->getImgResource($lconf['image.']['file'], $lconf['image.']['popup.']);

			$imageconf['enable'] = 1;
			$imageconf['JSwindow'] = 1;
			$imageconf['wrap'] = '<a href="javascript: close();"> | </a>';

			$result = $this->cObj->imageLinkWrap($this->cObj->cObjGetSingle('IMAGE',$lconf),$popUpImageArray[3],$imageconf);

		} else	{
			$result = $this->cObj->cObjGetSingle('IMAGE', $lconf);
		}

		return $result;
	}

	/**
	 * Generates the E-mail address for the detail view.
	 *
	 * @param	array		associative array containing all the information
	 * @param	string		the mode selected in the configuration / flexform, may be empty
	 *
	 * @return	string		the HTML code for displaying the E-Mail address
	 */
	function getEmail($person, $spamProtectionMode = '') {
		$emailArray = array();
		$email = '';
		$address = $this->getValue('email', $person);

		// Output of the e-mail address depending on the settings from flexform (spam protection mode)
		switch($spamProtectionMode)
		{
			case "jsencrypted"	:	$emailArray = $this->email_jsencrypted($address);
								break;
			case "asimage"		:	$emailArray = $this->email_asimage($address);
								break;
			case "asimagejsencrypted":	$emailArray = $this->email_asimage($address, true);
								break;
			case "plain"		:
			default				:	$emailArray['display'] = $address;
								break;
		}
		$email = $emailArray['begin'] . $emailArray['display'] . $emailArray['end'];

		return $email;
	}

	/**
	 * Converts an Array (which contains UIDs) to a comma separated string to use in DB queries.
	 *
	 * @param	array	the UIDs
	 *
	 * @return	string	the UIDs, comma separated
	 */
	function convertArrayToCommaseparatedString($inputArray) {
		$result = '';

		foreach ($inputArray as $uid) {
			$result .= ', ' . $uid;
		}

		return trim($result, ',');
	}


	/**
	 * Queries the Database to select all details of a single person.
	 * If requested, it gets overlayed with a valid translation and given back as a translated record.
	 *
	 * @param	integer		the uid of the person to fetch from the database
	 * @param	bolean		whether it should get translated or not, default is not to translate
	 *
	 * @return	array		associative array containing all the information, may be NULL
	 */
	function getPersonDetails($uid, $doTranslate = false) {
		$res_personDetails = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',	// SELECT
			'tx_bzdstaffdirectory_persons',	// FROM
			'uid = ' . $uid .Tx_Oelib_Db::enableFields('tx_bzdstaffdirectory_persons'),	//WHERE
			'',	// GROUP BY
			'',	// ORDER BY
			'1'	//LIMIT
		);
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_personDetails) > 0) {
			$person = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_personDetails);

			// get the translated record if the content language is not the default language
			if ($GLOBALS['TSFE']->sys_language_content && $doTranslate) {
				$OLmode = ($this->sys_language_mode == 'strict'?'hideNonTranslated':'');
				$translated_record = $GLOBALS['TSFE']->sys_page->getRecordOverlay('tx_bzdstaffdirectory_persons', $person,$GLOBALS['TSFE']->sys_language_content, $OLmode);
				if ($this->sys_language_mode != 'strict' OR !empty($translated_record['l18n_parent'])) {
					// found a valid translation, return the person with the translated information.
					$person = $translated_record;
				} else {
					// There's an empty translation found (can only happen if sys_language_mode = strict).
					// Act as if NO person could be retrieved from the database.
					$person = NULL;
				}
			} else {
				// no translation requested or available - return the record in default language
			}
		} else {
			$person = NULL;
		}

		return $person;
	}

	/**
	 * Gets all the persons from a given startingpoint.
	 * Used for the teamlist with active "ignoreGroupSelection" flag
	 *
	 * @param	string		comma separated list of PIDs
	 * @param	string		the sort order (a field name)
	 *
	 * @return	array		array of the persons uids
	 */
	function getTeamMembersFromStartingpoint($pidList, $sortOrder) {
		$groupMembers = array();

		$res_groupMembers = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid',	// SELECT
			'tx_bzdstaffdirectory_persons',	// FROM
			'pid IN(' . $pidList . ') AND l18n_parent = 0' .Tx_Oelib_Db::enableFields('tx_bzdstaffdirectory_persons'),	//WHERE
			'',	// GROUP BY
			$sortOrder,	// ORDER BY
			''	//LIMIT
		);
		while($member = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_groupMembers))	{
			$groupMembers[] = $member['uid'];
		}


		return $groupMembers;
	}

	/**
	 * Get all associated team leaders for a given team.
	 * The persons can be sorted by a given sort order.
	 *
	 * @param	string		comma separated list of team UIDs to look for
	 * @param	boolean		whether the team leaders should be ignored
	 *
	 * @return	array		array of the leader uids
	 */
	 function getTeamLeadersFromMM($teamUIDs, $ignoreTeamLeaders = false) {
	 	$groupLeaders = array();
	 	$groupLeadersSorted = array();
	 	$sortOrder = $this->getConfValueString(
			'sortOrderForLeaders',
			's_teamlist'
		);
	 	if ($sortOrder) {
	 		$sortOrder .= ' ' . $this->getConfValueString(
				'sortOrderForLeadersDirection',
				's_teamlist'
			);
	 	}

		$allowedPids = $this->getAllowedPids();
		if (!empty($allowedPids)) {
			$additionalWhereClause = ' AND pid IN('.$allowedPids.')';
		}

		// don't show the team leaders, if ignoreTeamLeaders switch is set
		if (!$ignoreTeamLeaders) {
			// show the team leaders in the teamlist
			$res_groupLeaders = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',	// SELECT
				'tx_bzdstaffdirectory_groups_teamleaders_mm',	// FROM
				'uid_local IN(' . $teamUIDs .')',	//WHERE
				'',	// GROUP BY
				'sorting',	// ORDER BY
				''	//LIMIT
			);

			if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_groupLeaders) > 0)	{
				while($member = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_groupLeaders))	{
					$groupLeaders[] = $member['uid_foreign'];
				}

				// Maybe we need to bring the (now unordered) users into a certain
				// sorting.
				if ($sortOrder != 'sorting ASC' AND $sortOrder != 'sorting DESC') {
					// Second call to the DB: get the right order!
					// TODO: Bring this block into it's own function!
					$groupLeadersUIDList = $this->convertArrayToCommaseparatedString($groupLeaders);
					$res_groupLeadersSorted = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'uid',	// SELECT
						'tx_bzdstaffdirectory_persons',	// FROM
						'uid IN(' . $groupLeadersUIDList . ') AND l18n_parent = 0'
							.$additionalWhereClause .Tx_Oelib_Db::enableFields('tx_bzdstaffdirectory_persons'),	//WHERE
						'',	// GROUP BY
						$sortOrder,	// ORDER BY
						''	//LIMIT
					);
					while($member = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_groupLeadersSorted))	{
						$groupLeadersSorted[] = $member['uid'];
					}
				} else {
					$groupLeadersSorted = $groupLeaders;
				}
			}
		} else {
			// don't show the team leaders, just return an empty array
		}
		return $groupLeadersSorted;
	 }

	/**
	 * Gets a list of PIDs from which we can fetch person records.
	 *
	 * @return	string		comma-separated list of PIDs, may be empty
	 */
	function getAllowedPids() {
		if (!$this->hasConfValueString('startingpoint', 's_teamlist')) {
			return '';
		}

		$startingPoint = $this->getConfValueInteger(
			'startingpoint',
			's_teamlist'
		);

		if ($this->hasConfValueInteger('recursive', 's_teamlist')) {
			$recursion = $this->getConfValueInteger(
				'recursive',
				's_teamlist'
			);
		} else {
			$recursion = 0;
		}

		$result = $this->pi_getPidList(
			$startingPoint,
			$recursion
		);

		return $result;
	}

	/**
	 * Gets all associated team members for a given team.
	 * The persons can be sorted by a given sort order.
	 *
	 * @param	string		comma separated list of team UIDs to look for
	 * @param	string		field name used to order the records
	 *
	 * @return	array		array of all member uids
	 */
	function getTeamMembersFromMM($team_uid, $sortOrder = '') {
		$groupMembers = array();
		$groupMembersSorted = array();

		$allowedPids = $this->getAllowedPids();
		if (!empty($allowedPids)) {
			$additionalWhereClause = ' AND pid IN('.$allowedPids.')';
		}

		$res_groupMembers = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',	// SELECT
			'tx_bzdstaffdirectory_persons_usergroups_mm',	// FROM
			'uid_foreign IN(' . $team_uid .')',	//WHERE
			'',	// GROUP BY
			'',	// ORDER BY
			''	//LIMIT
		);

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_groupMembers) > 0)	{
			while($member = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_groupMembers))	{
				$groupMembers[] = $member['uid_local'];
			}

			// Second call to the DB: get the right order!
			$groupMembersUIDList = $this->convertArrayToCommaseparatedString($groupMembers);
			$res_groupMembersSorted = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'uid',	// SELECT
				'tx_bzdstaffdirectory_persons',	// FROM
				'uid IN(' . $groupMembersUIDList . ') AND l18n_parent = 0'
					.$additionalWhereClause .Tx_Oelib_Db::enableFields('tx_bzdstaffdirectory_persons'),	//WHERE
				'',	// GROUP BY
				$sortOrder,	// ORDER BY
				''	//LIMIT
			);
			while($member = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_groupMembersSorted))	{
				$groupMembersSorted[] = $member['uid'];
			}
		}
		return $groupMembersSorted;
	}

	/**
	 * Sets all the labels that could be used in the template.
	 */
	function setLabelFields() {
		$allLabelFields = array(
			'function',
			'phone',
			'email',
			'xing',
			'room',
			'officehours',
			'location',
			'tasks',
			'files',
			'groups',
			'date_birthdate',
			'date_incompany',
			'date_age',
			'first_name',
			'last_name',
			'officehours',
			'location_singular',
			'tasks',
			'files',
			'groups_singular',
			'opinion',
			'universal_field_1',
			'universal_field_2',
			'universal_field_3',
			'universal_field_4',
			'universal_field_5',
		);
		foreach($allLabelFields as $key) {
			$this->setMarker(
				'label_'.$key,
				$this->pi_getLL('label_'.$key)
			);
		}
	}

	/**
	 * Generates the HTML output for the list entry of exact one person.
	 *
	 * @param	integer		the uid of the person to show
	 * @param	boolean		whether this person is a group leader or not
	 *
	 * @return	string		the complete HTML for this persons entry in the list
	 */
	function showPersonInTeamList($uid, $isLeader = false)	{
		$result = '';
		$person = array();
		$showImage = $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'showimages','s_teamlist');

		// get the details of the actual person
		$person = $this->getPersonDetails($uid, true);
		if ($person) {
			if ($isLeader) {
				$this->setMarker('class', 'tx_bzdstaffdirectory_teamlist_person leader');
			} else {
				$this->setMarker('class', 'tx_bzdstaffdirectory_teamlist_person');
			}

			if ($showImage) {
				// If no image is stored for this user, a dummy picture will be shown.
				$this->setMarker('image', $this->getImage($person));
			} else {
				$this->hideSubparts('image', 'listitem_wrapper');
			}


			// define all the standard fields (these are all fields, that can
			// be output to the frontend directly from the DB without changes)
			$allStandardFields = array(
				'first_name',
				'last_name',
				'phone',
				'opinion',
				'tasks',
				'room',
				'officehours'
			);

			// depending on the configuration of the universal fields: show or hide them
			for ($i = 1; $i <= 5; $i++) {
				if ($this->arrConf['useUniversalField_' . $i]) {
					$allStandardFields[] = 'universal_field_' . $i;
				} else {
					$this->hideSubparts('universal_field_' . $i, 'listitem_wrapper');
				}
			}

			// fill the markers of all the simple fields
			foreach($allStandardFields as $key) {
				if ($this->hasValue($key, $person)) {
					$this->setMarker($key, $this->getValue($key, $person, true));
				} else {
					$this->hideSubparts($key, 'listitem_wrapper');
				}
			}


			// Now set all the other markers, that will contain processed data
			if ($this->hasValue('title', $person)) {
				$this->setMarker('title', $this->getValue('title', $person, true));
				$this->setMarker('label_title', $this->pi_getLL('label_title'));
			} else {
				$this->hideSubparts('title', 'listitem_wrapper');
			}

			// Shows the function of the person if one is assigned.
			if ($this->hasValue('function', $person) || $this->hasValue('functions', $person)) {
				if ($this->hasValue('functions', $person)) {
					$functionObject = $this->getFunction($person);
					$function = $functionObject->getTitle();
				} else {
					$function = $this->getValue('function', $person);
				}

				$this->setMarker('function', $function);
			} else {
				$this->hideSubparts('function', 'listitem_wrapper');
			}

			if ($this->hasValue('email', $person)) {
				$spamProtectionMode = $this->getConfValueString('spamProtectionMode','s_teamlist');
				$this->setMarker('email', $this->getEmail($person, $spamProtectionMode));
				$this->setMarker('label_email', $this->pi_getLL('label_email'));
			} else {
				$this->hideSubparts('email', 'listitem_wrapper');
			}

			// Shows the location of the person if one is assigned.
			if ($this->hasValue('location', $person)) {
				$this->setMarker('location', $this->getLocationList($uid));
			} else {
				$this->hideSubparts('location', 'listitem_wrapper');
			}

			if ($this->getMemberOfGroups($uid)) {
				$this->setMarker('groups', $this->getGroups($uid));
			} else {
				$this->hideSubparts('groups', 'listitem_wrapper');
			}

			if ($this->hasValue('date_incompany', $person)) {
				$this->setMarker('date_incompany', $this->getFormattedDate($person['date_incompany'], 'dateFormatInCompany'));
				$this->setMarker('label_date_incompany', $this->pi_getLL('label_date_incompany'));
			} else {
				$this->hideSubparts('date_incompany', 'listitem_wrapper');
			}

			if ($this->hasValue('date_birthdate', $person)) {
				if ($this->getConfValueBoolean('showAgeInsteadOfBirthdate', 's_detailview')) {
					// show the age of the person instead of the birthdate
					$this->setMarker('date_birthdate', $this->getAge($person['date_birthdate']));
					$this->setMarker('label_date_birthdate', $this->pi_getLL('label_date_age'));
				} else {
					// show the birthdate
					$this->setMarker('date_birthdate', $this->getFormattedDate($person['date_birthdate'], 'dateFormatBirthday'));
					$this->setMarker('label_date_birthdate', $this->pi_getLL('label_date_birthdate'));
				}
			} else {
				$this->hideSubparts('date_birthdate', 'listitem_wrapper');
			}

			if ($this->hasValue('files', $person)) {
				$this->setMarker('files', $this->getFileList($person));
				$this->setMarker('label_files', $this->pi_getLL('label_files'));
			} else {
				$this->hideSubparts('files', 'listitem_wrapper');
			}

			// Fill the marker for the link to the detail view or hide it completely
			// if linking to the detail page is disabled completely.
			if (!$this->getConfValueBoolean('doNotLinkToDetailView', 's_teamlist')) {
				$this->setMarker(
					'link_detail',
					$this->linkToDetailPage(
						$this->pi_getLL('label_link_detail'),
						$this->getValue('uid', $person)
					)
				);
			} else {
				$this->hideSubparts('link_detail');
			}

			// Make the title and name be linked to the single view. This will only
			// be done if linking to details page is not completely disabled and
			// linking the names is enabled in the configuration.
			if (!$this->getConfValueBoolean('doNotLinkToDetailView', 's_teamlist')
				&& $this->getConfValueBoolean('linkNamesToSingleView', 's_teamlist')
			) {
				$linkedMarkers = array(
					'first_name',
					'last_name',
					'title',
				);
				foreach ($linkedMarkers as $currentMarker) {
					$this->setMarker(
						$currentMarker,
						$this->linkToDetailPage(
							$this->getValue($currentMarker, $person),
							$this->getValue('uid', $person)
						)
					);
				}
			}

			// merge the marker content with the template
			$result .= $this->getSubpart('LIST_ITEM');

			// reset the markers (may be they are empty for the next person)
			$this->markers = array();

			// reset the hidden subparts (may be they are needed in the next row)
			$this->resetSubpartsHiding();
		}

		return $result;
	}

	/**
	 * Returns the function of the current person as an instance of a function model.
	 *
	 * TODO: Remove this function as soon as the list view is nice MVC'ed :-)
	 *
	 * @param array associative array with all the details of this person
	 *
	 * @return tx_bzdstaffdirectory_Model_Function the function model
	 */
	private function getFunction($person) {
		$dbResult = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',	// SELECT
			'tx_bzdstaffdirectory_persons_functions_mm m'
				.' left join tx_bzdstaffdirectory_functions l'
				.' on m.uid_foreign=l.uid',	// FROM
			'm.uid_local IN(' . $person['uid'] .')'
				.' AND l.hidden=0 AND l.deleted=0',	//WHERE
			'',	// GROUP BY
			'm.sorting',	// ORDER BY
			'1'	//LIMIT
		);

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($dbResult) > 0)	{
			$relation = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($dbResult);
			$function = Tx_Oelib_MapperRegistry::get('tx_bzdstaffdirectory_Mapper_Function')
					->find($relation['uid_foreign']);
		}

		return $function;
	}

	/**
	 * Wraps the provided string in <a> tags and links it to the detail page.
	 * Depending on the configuration, this is either a "normal" link, or some
	 * magic JavaScript to open the detail view in a pop-up.
	 *
	 * @param	string		the string to wrap in <a> tags
	 * @param	integer		the UID of the person to show in the single view
	 *
	 * @return	string		the HTML code needed to represent the link
	 */
	function linkToDetailPage($textToLink, $uid) {
		$result = '';

		// Check whether a normal link or a pop-up is requested
		if (!$this->getConfValueBoolean('showSingleViewAsPopUp', 's_teamlist')) {
			$linkParams = array(
				'tx_bzdstaffdirectory_pi1[showUid]' => $uid,
			);

			// only add the backLink UID if not disabled via setup (to beautify
			// realURL URLs)
			if (!$this->getConfValueBoolean('disableBackLink', 's_teamlist')) {
				$linkParams['tx_bzdstaffdirectory_pi1[backPid]'] = $GLOBALS['TSFE']->id;
			}

			$result = $this->pi_linkTP(
				$textToLink,
				$linkParams,
				true,
				$this->detailPage
			);
		} else {
			$linkParams = array(
				'tx_bzdstaffdirectory_pi1[showUid]' => $uid,
				'popUp' => 1
			);

			$result = $this->pi_linkToPage(
				$textToLink,
				$this->detailPage,
				'',
				$linkParams
			);

			// Undoes the htmlspecialchars() call from the function above. If this
			// is not done, the further processin will result in URLs with stuff
			// like &amp;&amp;
			// See: https://bugs.oliverklee.com/show_bug.cgi?id=833
			$result = htmlspecialchars_decode($result);

			$result = $this->pi_openAtagHrefInJSwindow(
				$result,
				'bla',
				$this->getConfValueString('singleViewPopUpConfig')
			);
		}

		return $result;
	}

	/**
	 * Returns an image containing the provided e-mail address
	 *
	 * @param	string		the e-mail address to protect
	 * @param	boolean		whether the image should include an encrypted link
	 *
	 * @return	array		associative array containing the infos to fill the markers
	 */
	function email_asimage($email, $includeEncryptedLink = false)	{
		$emailconf["image."]["file"] = 'GIFBUILDER';
		$emailconf["image."]["file."]["10"] = 'TEXT';
		$emailconf["image."]["file."]["10."]["text"] = $email;
// FIXME: Make Font, Fontsize etc. configurable via Flexform!!
		$emailconf["image."]["file."]["10."]["fontFile"] = 't3lib/fonts/verdana.ttf';
		$emailconf["image."]["file."]["10."]["fontSize"] = '11';
		$emailconf["image."]["file."]["10."]["offset"] = '0, 14';
		$emailconf["image."]["file."]["10."]["nicetext"] = 1;
		$emailconf["image."]["file."]["XY"] ='[10.w]+1, [10.h]+4';

		$result['display'] = $this->cObj->cObjGetSingle('IMAGE',$emailconf);
		if ($includeEncryptedLink) {
			$encrypted = $this->email_jsencrypted($email);
			$result['begin'] = $encrypted['begin'];
			$result['end'] = $encrypted['end'];
		} else {
			$result['begin'] = '';
			$result['end'] = '';
		}

		return $result;
	}


	/**
	 * Returns the provided e-mail address encrypted with the default
	 * TYPO3-JavaScript-Encryption.
	 *
	 * @param	string		the e-mail address to protect
	 *
	 * @return	array		associative array containing the parts to fill the markers
	 */
	function email_jsencrypted($email)	{
		$mailto = $this->cObj->getMailTo($email,$email);
		$result = array();
		$result['display'] = $this->translate('label_send_email');
		$result['begin'] = '<a href="'.$mailto[0].'">';
		$result['end'] = '</a>';

		return $result;
	}


	/**
	 * Queries the database and gets all details on the selected groups/teams.
	 *
	 * @param	integer		the UID of the team to select
	 * @param	boolean		whether to translate the records, default is no
	 *
	 * @return	array		all the fields of the selected team, may be null
	 */
	function getTeamDetails($uid, $doTranslate = false) {
		$res_groupDetails = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',	// SELECT
			'tx_bzdstaffdirectory_groups',	// FROM
			'uid = ' . $uid .Tx_Oelib_Db::enableFields('tx_bzdstaffdirectory_groups'),	//WHERE
			'',	// GROUP BY
			'',	// ORDER BY
			'1'	//LIMIT
		);
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_groupDetails) > 0) {
			$group = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_groupDetails);

			// get the translated record if the content language is not the default language
			if ($GLOBALS['TSFE']->sys_language_content && $doTranslate) {
				$OLmode = ($this->sys_language_mode == 'strict'?'hideNonTranslated':'');
				$translated_record = $GLOBALS['TSFE']->sys_page->getRecordOverlay(
					'tx_bzdstaffdirectory_groups',
					$group,
					$GLOBALS['TSFE']->sys_language_content,
					$OLmode
				);
				if ($this->sys_language_mode != 'strict'
					OR !empty($translated_record['l18n_parent'])
					) {
					// found a valid translation, return the groups with the translated information.
					$group = $translated_record;
				} else {
					// There's an empty translation found (can only happen if sys_language_mode = strict).
					// Act as if NO group could be retrieved from the database.
					$group = NULL;
				}
			} else {
				// no translation requested or available - return the record in default language
			}
		} else {
			$group = NULL;
		}

		return $group;
	}

	/**
	 * Queries the database and gets all details on the selected location.
	 *
	 * @param	integer		the UID of the location to select
	 * @param	boolean		whether to translate the records, default is no
	 *
	 * @return	array		all the fields of the selected location, may be null
	 */
	function getLocationDetails($uid, $doTranslate = false) {
		$resLocationDetails = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',	// SELECT
			'tx_bzdstaffdirectory_locations',	// FROM
			'uid = ' . $uid .Tx_Oelib_Db::enableFields('tx_bzdstaffdirectory_locations'),	//WHERE
			'',	// GROUP BY
			'',	// ORDER BY
			'1'	//LIMIT
		);
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($resLocationDetails) > 0) {
			$location = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resLocationDetails);

			// get the translated record if the content language is not the default language
			if ($GLOBALS['TSFE']->sys_language_content && $doTranslate) {
				$OLmode = ($this->sys_language_mode == 'strict'?'hideNonTranslated':'');
				$translated_record = $GLOBALS['TSFE']->sys_page->getRecordOverlay(
					'tx_bzdstaffdirectory_locations',
					$location,
					$GLOBALS['TSFE']->sys_language_content,
					$OLmode
				);
				if ($this->sys_language_mode != 'strict'
					OR !empty($translated_record['l18n_parent'])
					) {
					// found a valid translation, return the groups with the translated information.
					$location = $translated_record;
				} else {
					// There's an empty translation found (can only happen if sys_language_mode = strict).
					// Act as if NO group could be retrieved from the database.
					$location = NULL;
				}
			} else {
				// no translation requested or available - return the record in default language
			}
		} else {
			$location = NULL;
		}

		return $location;
	}


	/**
	 * Returns an array containing team UIDs of which the provided person is memberOf.
	 *
	 * @param	integer		UID of the person to search for
	 *
	 * @return	array		containing team UIDs
	 */
	function getMemberOfGroups($uid) {
		$groups = array();

		$res_groups = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',	// SELECT
			'tx_bzdstaffdirectory_persons_usergroups_mm m'
				.' left join tx_bzdstaffdirectory_groups g'
				.' on m.uid_foreign=g.uid',	// FROM
			'm.uid_local IN(' . $uid .')'
				.' AND g.hidden=0 AND g.deleted=0',	//WHERE
			'',	// GROUP BY
			'm.sorting',	// ORDER BY
			''	//LIMIT
		);

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_groups) > 0)	{
			while($member = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_groups))	{
				$groups[] = $member['uid_foreign'];
			}
		}
		return $groups;
	}

	/**
	 * Returns an array containing location UIDs that were assigned to a given
	 * person.
	 *
	 * @param	integer		UID of the person to search for
	 *
	 * @return	array		containing location UIDs
	 */
	function getLocationsForPerson($uid) {
		$locations = array();

		$res_locations = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',	// SELECT
			'tx_bzdstaffdirectory_persons_locations_mm m'
				.' left join tx_bzdstaffdirectory_locations l'
				.' on m.uid_foreign=l.uid',	// FROM
			'm.uid_local IN(' . $uid .')'
				.' AND l.hidden=0 AND l.deleted=0',	//WHERE
			'',	// GROUP BY
			'm.sorting',	// ORDER BY
			''	//LIMIT
		);

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_locations) > 0)	{
			while($location = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_locations))	{
				$locations[] = $location['uid_foreign'];
			}
		}
		return $locations;
	}

	/**
	 * fills the internal array '$this->langArr' with the available syslanguages
	 *
	 * @return	void
	 */
	function initLanguages () {

		$lres = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'sys_language',
			'1=1' . $this->cObj->enableFields('sys_language'));


		$this->langArr = array();
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($lres)) {
			$this->langArr[$row['uid']] = $row;
		}

		return;
	}

	/**
	 * Checks whether a given person record has a certain field set.
	 *
	 * @param	string		field name to check
	 * @param	array		associative array containing all the information
	 *
	 * @returm	boolean		the answer
	 */
	function hasValue($key, $person) {
		$result = false;

		if (!empty($person[$key])) {
			$result = true;
		}

		return $result;
	}

	/**
	 * Returns the value of a field contained in an array.
	 * The result can optionally be htmlspecialchars'ed.
	 *
	 * @param	string		field name
	 * @param	array		associative array containing all the information
	 * @param	boolean		whether the string should be htmlspecialchars'ed befor beeing returned
	 *
	 * @return	string		the field value
	 */
	function getValue($key, $person, $doHtmlSpecialChars = false) {
		$result = '';
		if ($doHtmlSpecialChars) {
			$result = htmlspecialchars($person[$key]);
		} else {
			$result = $person[$key];
		}

		return $result;
	}

	/**
	 * Adds a path in front of the file name.
	 * This is used for files that are selected in the Flexform of the front end plugin.
	 *
	 * If no path is provided, the default (uploads/[extension_name]/) is used as path.
	 *
	 * An example (default, with no path provided):
	 * If the file is named 'template.tmpl', the output will be 'uploads/[extension_name]/template.tmpl'.
	 * The '[extension_name]' will be replaced by the name of the calling extension.
	 *
	 * @param	string		the file name
	 * @param	string		the path to the file (without filename), must contain a slash at the end, may contain a slash at the beginning (if not relative)
	 *
	 * @return	string		the complete path including file name
	 *
	 * @access	protected
	 */
	function addPathToFileName($fileName, $path = '') {
		if (empty($path)) {
			$path = 'uploads/tx_bzdstaffdirectory/';
		}

		return $path.$fileName;
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzdstaffdirectory/pi1/class.tx_bzdstaffdirectory_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzdstaffdirectory/pi1/class.tx_bzdstaffdirectory_pi1.php']);
}

?>
