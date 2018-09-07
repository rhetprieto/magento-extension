<?php
namespace Skuiq\SyncModule\Model;

use Skuiq\SyncModule\Api\ExchangeAdminTokenForIntegrationInterface as ApiInterface;

class ApiExchangeAdminToken implements ApiInterface {

  /**
  *  @var \Skuiq\SyncModule\Helper\Integration
  */

	protected $_integrationHelper;

  /**
  * @param \Skuiq\SyncModule\Helper\Integration $integrationHelper
  */

  public function __construct(
    \Skuiq\SyncModule\Helper\Integration $integrationHelper
    )
    {
      $this->_integrationHelper = $integrationHelper;
    }

    public function create_dynamic_integration() {
      try {
        $consumerKey = $this->_integrationHelper->get_or_create_integration();
        $response = array (
          "oauth_consumer_key" => $consumerKey
        );
        return json_encode($response);
      } catch (\Exception $exception) {
        return json_encode($exception->getMessage());
    }
  }
}
