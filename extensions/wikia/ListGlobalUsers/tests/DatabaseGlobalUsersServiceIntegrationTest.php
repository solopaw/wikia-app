<?php

/**
 * @group Integration
 */
class DatabaseGlobalUsersServiceIntegrationTest extends WikiaDatabaseTest {
	/** @var GlobalUsersService $dataBaseGlobalUsersService */
	private $dataBaseGlobalUsersService;

	protected function setUp() {
		parent::setUp();
		require_once __DIR__ . '/../ListGlobalUsers.setup.php';
		$this->dataBaseGlobalUsersService = new DatabaseGlobalUsersService();
	}

	public function testReturnsMappingOfGroupMemberUserIdsOrderedByName() {
		$resultMap = $this->dataBaseGlobalUsersService->getGroupMembers( [ 'staff', 'vstf' ] );

		$this->assertCount( 2, $resultMap );
		$this->assertEquals( 'KossuthLajos', $resultMap[1]['name'] );
		$this->assertEquals( 'FerencJozsef', $resultMap[3]['name'] );

		$this->assertEquals( [ 'staff', 'vstf' ], $resultMap[1]['groups'] );
		$this->assertEquals( [ 'vstf' ], $resultMap[3]['groups'] );
	}

	public function testReturnsEmptyMapForEmptySetOfGroups() {
		$this->assertEmpty( $this->dataBaseGlobalUsersService->getGroupMembers( [] ) );
	}

	protected function getDataSet() {
		return $this->createYamlDataSet( __DIR__ . '/fixtures/database_global_users_service.yaml' );
	}
}
