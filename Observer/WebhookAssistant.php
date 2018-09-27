<?php

namespace Skuiq\SyncModule\Observer;

class WebhookAssistant
{
    /**
     * @var  \Magento\Framework\HTTP\ZendClient
     * @var  \Skuiq\SyncModule\Model\OrmSettingsFactory
     * @var  \Skuiq\SyncModule\Logger\Logger
     */

    protected $httpClient;
    protected $settingsFactory;
    protected $logger;

    /**
     * @param \Magento\Framework\HTTP\ZendClient $httpClient
     * @param \Skuiq\SyncModule\Model\OrmSettingsFactory $settingsFactory
     * @param \Skuiq\SyncModule\Logger\Logger $logger
     */

    public function __construct(
        \Magento\Framework\HTTP\ZendClient $httpClient,
        \Skuiq\SyncModule\Model\OrmSettingsFactory $settingsFactory,
        \Skuiq\SyncModule\Logger\Logger $logger
    ) 
{ 
     
     
     
     
     
     
     
     
     
    
        $this->httpClient = $httpClient;
        $this->settingsFactory = $settingsFactory;
        $this->logger = $logger;
    }

    /**
     * @return array|bool
     */
    public function getInfoIfActive()
    {
        $settings = $this->settingsFactory->create();
        $settings = $settings->load('skuiq', 'name');
        if (!$settings['is_active']) {
            return false;   // Ignore webhook if the connection is still not set up.
        }

        return array( 'store_id' => $settings['store_id'], 'auth' => $settings['auth']);
    }

    /**
     * @param $data_array
     * @param $store_id
     * @param $event_type
     * @param $timeout
     */
    public function postToEndpoint($data_array, $store_id, $event_type, $timeout)
    {
        $endpoint_url = "http://api.skuiq.test:3000/magento2/webhooks/" . $store_id. '/' . $event_type;
        $this->httpClient->setUri($endpoint_url);
        $this->httpClient->setConfig(array('timeout' => $timeout));
        $this->httpClient->setParameterPost($data_array);
        $this->httpClient->request(\Magento\Framework\HTTP\ZendClient::POST);
    }
}
