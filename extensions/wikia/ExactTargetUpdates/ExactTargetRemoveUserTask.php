<?php

class ExactTargetRemoveUserTask extends ExactTargetBaseTask {

	/**
	 * Task for removing user data in ExactTarget
	 * @param int $iUserId Id of user to be deleted
	 */
	public function removeUserData( $iUserId ) {
		$oClient = $this->getClient();
		$this->removeSubscriber( $iUserId, $oClient );
		$this->removeUserDataExtension( $iUserId, $oClient );
		$this->removeUserPropertiesDataExtension( $iUserId, $oClient );
	}

	/**
	 * Removes Subscriber object in ExactTarget by API request if email is not used by other user
	 * @param int $iUserId
	 * @param ExactTargetSoapClient $oClient
	 */
	public function removeSubscriber( $iUserId, ExactTargetSoapClient $oClient ) {
		$sEmail = $this->getUserEmail( $iUserId, $oClient );
		if ( !$this->isEmailInUse( $sEmail, $oClient, $iUserId ) ) {
			$this->doRemoveSubscriber( $sEmail, $oClient );
		}
	}

	/**
	 * Sends remove request to actually remove Subscriber object in ExactTarget by API request
	 * @param string $sUserEmail
	 * @param ExactTargetSoapClient $oClient
	 */
	private function doRemoveSubscriber( string $sUserEmail, ExactTargetSoapClient $oClient ) {
		$oSubscriber = new ExactTarget_Subscriber();
		$oSubscriber->SubscriberKey = $sUserEmail;
		$this->performRemove( [ $oSubscriber ], $oClient, 'Subscriber' );
	}

	/**
	 * Removes DataExtension object in ExactTarget by API request
	 * that reflects Wikia entry from user table
	 * @param int $iUserId
	 * @param ExactTargetSoapClient $oClient
	 */
	public function removeUserDataExtension( int $iUserId, ExactTargetSoapClient $oClient ) {
		$oDE = $this->prepareUserDataExtensionObjectForRemove( $iUserId );
		$this->performRemove( [ $oDE ], $oClient);
	}

	/**
	 * Removes DataExtension objects in ExactTarget by API request
	 * that reflects Wikia entry from user_properties table
	 * @param int $iUserId
	 * @param ExactTargetSoapClient $oClient
	 */
	public function removeUserPropertiesDataExtension( int $iUserId, ExactTargetSoapClient $oClient ) {
		$aDE = $this->prepareUserPropertiesDataExtensionObjectsForRemove( $iUserId );
		$this->performRemove( $aDE, $oClient);
	}

	/**
	 * Prepares proper ExactTarget_DataExtensionObject object
	 * for sending remove request
	 * @param int $iUserId id of user to be removed
	 * @return ExactTarget_DataExtensionObject
	 */
	public function prepareUserDataExtensionObjectForRemove( int $iUserId ) {

		/* Create new DataExtensionObject that reflects user table data */
		$oDE = new ExactTarget_DataExtensionObject();

		/* Get Customer Keys specific for production or development */
		$aCustomerKeys = ExactTargetUpdatesHelper::getCustomerKeys();
		$oDE->CustomerKey = $aCustomerKeys['user'];

		/* Prepare query keys */
		$oDE->Keys = [ $this->wrapApiProperty( 'user_id',  $iUserId ) ];

		return $oDE;
	}

	/**
	 * Prepares array of proper ExactTarget_DataExtensionObject objects
	 * for sending remove request with user_properties to remove
	 * @param int $iUserId id of user to be removed
	 * @return ExactTarget_DataExtensionObject
	 */
	public function prepareUserPropertiesDataExtensionObjectsForRemove( int $iUserId ) {

		/*
		 * @var array $aUserPropertiesNames list of user properties sent to ExactTarget
		 * (see ExactTargetUpdatesHooks::prepareUserPropertiesParams)
		 */
		$aUserPropertiesNames = [
			'marketingallowed',
			'unsubscribed',
			'language'
		];

		$aDE = [];
		foreach ( $aUserPropertiesNames as $sPropertyName ) {
			/* Create new DataExtensionObject that reflects user table data */
			$oDE = new ExactTarget_DataExtensionObject();

			/* Get Customer Keys specific for production or development */
			$aCustomerKeys = ExactTargetUpdatesHelper::getCustomerKeys();
			$oDE->CustomerKey = $aCustomerKeys['user_properties'];

			/* Prepare query keys */
			$oDE->Keys = [
				$this->wrapApiProperty( 'up_user',  $iUserId ),
				$this->wrapApiProperty( 'up_property',  $sPropertyName )
			];

			$aDE[] = $oDE;
		}

		return $aDE;
	}

	/**
	 * Retrieves user email from ExactTarget based on provided user ID
	 * @param int $iUserId
	 * @param ExactTargetSoapClient $oClient
	 * @return null|string
	 */
	public function getUserEmail( $iUserId, ExactTargetSoapClient $oClient ) {
		$oSimpleFilterPart = $this->wrapSimpleFilterPart( 'user_id', $iUserId );
		$oRetrieveRequest = $this->wrapRetrieveRequest( 'user', [ 'user_email' ], $oSimpleFilterPart );
		$oRetrieveRequestMessage = $this->wrapRetrieveRequestMessage( $oRetrieveRequest );

		$oEmailResult = $oClient->Retrieve( $oRetrieveRequestMessage );
		if ( isset( $oEmailResult->Results->Properties->Property->Value ) ) {
			return $oEmailResult->Results->Properties->Property->Value;
		}

		$this->notice( __METHOD__ . ' user DataExtension object not found for user_id = ' . $iUserId );
		return null;
	}

	/**
	 * Checks whether there are any users that has provided email
	 * @param string $sEmail Email address to check in ExactTarget
	 * @param ExactTargetSoapClient $oClient
	 * @param int $iSkipUserId Skip this user ID when checking if email is used by any account
	 * @return bool
	 */
	public function isEmailInUse( $sEmail, $oClient, $iSkipUserId = null ) {
		/* @var stdClass $oResults */
		$oUsersIds = $this->retrieveUserIdsByEmail( $sEmail, $oClient );
		$iUsersCount = count( $oUsersIds->Results );

		// Email is in use when there are more than one user with email
		$ret = ( $iUsersCount > 1 );

		// One or less users
		if ( !$ret ) {
			// Email is in use when there's one user not equal to $iSkipUserId from parameters list
			$ret = $iUsersCount == 1 && $oUsersIds->Results->Properties->Property->Value != $iSkipUserId;
		}

		return $ret;
	}

	/**
	 * Retrieve from ExactTarget a list of user IDs that use provided email
	 * @param string $sEmail
	 * @param ExactTargetSoapClient $oClient
	 * @return stdClass
	 * e.g. many results
	 *     stdClass Object (
	 *         [Results] => Array of stdClass Objects
	 *     );
	 * e.g. one result
	 *     stdClass Object (
	 *         [Results] => stdClass Object (
	 *             [Properties] => stdClass Object (
	 *                 [Property] => stdClass Object (
	 *                     [Name] => string
	 *                     [Value] => int
	 *                 )
	 *             )
	 *         )
	 *      );
	 */
	public function retrieveUserIdsByEmail( $sEmail, $oClient ) {
		$oSimpleFilterPart = $this->wrapSimpleFilterPart( 'user_email', $sEmail );
		$oRetrieveRequest = $this->wrapRetrieveRequest( 'user', [ 'user_id' ], $oSimpleFilterPart );
		$oRetrieveRequestMessage = $this->wrapRetrieveRequestMessage( $oRetrieveRequest );
		return $oClient->Retrieve( $oRetrieveRequestMessage );
	}

}
