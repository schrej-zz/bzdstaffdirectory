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
 * Testcase for the function model in the 'bzdstaffdirectory' extension.
 *
 * @package		TYPO3
 * @subpackage	tx_bzdstaffdirectory
 * @author		Mario Rimann <typo3-coding@rimann.org>
 */
class tx_bzdstaffdirectory_Model_Function_testcase extends tx_phpunit_testcase {
	private $fixture;
	private $uid;

	protected function setUp() {
		$this->testingFramework = new Tx_Oelib_TestingFramework('tx_bzdstaffdirectory');

		$this->uid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_functions',
			array(
				'title' => 'Master of Desaster',
			)
		);
		$this->createFunction($this->uid);
	}

	protected function tearDown() {
		$this->testingFramework->cleanUp();
		unset($this->testingFramework);
		unset($this->fixture);
	}

	/**
	 * Creates an instance of a function model in $this->fixture.
	 *
	 * @param integer a location's UID, must be >= 0
	 */
	private function createFunction($functionUid) {
		try {
			$this->fixture = Tx_Oelib_MapperRegistry::get('tx_bzdstaffdirectory_Mapper_Function')
					->find($functionUid);
		} catch (Tx_Oelib_Exception_NotFound $exception) {
			$this->fixture = null;
		}
	}

	public function testGetUid() {
		$this->assertEquals(
			$this->uid,
			$this->fixture->getUid()
		);
	}

	public function testGetTitleReturnsTitle() {
		$this->assertEquals(
			'Master of Desaster',
			$this->fixture->getTitle()
		);
	}
}

?>
