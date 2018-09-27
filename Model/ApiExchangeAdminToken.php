<?php
namespace Skuiq\SyncModule\Model;

use Skuiq\SyncModule\Api\ExchangeAdminTokenForIntegrationInterface as ApiInterface;

class ApiExchangeAdminToken implements ApiInterface
{

    /**
     * @var \Skuiq\SyncModule\Helper\Integration
     */

    protected $integrationHelper;

    /**
     * @param \Skuiq\SyncModule\Helper\Integration $integrationHelper
     */

    public function __construct(
        \Skuiq\SyncModule\Helper\Integration $integrationHelper
    ) {
        $this->integrationHelper = $integrationHelper;
    }

    /**
     * @return false|string
     */
    public function apiCreateIntegration()
    {
        try {
            $consumerKey = $this->integrationHelper->getOrCreateIntegration();
            $response =  array(
                "oauth_consumer_key" => $consumerKey
            );
            return json_encode($response);
        } catch (\Exception $exception) {
            return json_encode($exception->getMessage());
        }
    }
}
