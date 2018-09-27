<?php

namespace Skuiq\SyncModule\Observer;

class WebhookAssistant
{
  /**
   * @var  \Magento\Framework\HTTP\ZendClient
   * @var  \Skuiq\SyncModule\Logger\Logger
   */

    protected $httpClient;
    protected $OrmSettingsFactory;
    protected $logger;

    /**
     * @param \Magento\Framework\HTTP\ZendClient $httpClient
     * @param \Skuiq\SyncModule\Model\OrmSettingsFactory $OrmSettingsFactory
     * @param \Skuiq\SyncModule\Logger\Logger $logger
     */

  public function __construct(
      \Magento\Framework\HTTP\ZendClient $httpClient,
      \Skuiq\SyncModule\Model\OrmSettingsFactory $OrmSettingsFactory,
      \Skuiq\SyncModule\Logger\Logger $logger
   )
  {
         $this->httpClient = $httpClient;
    $this->OrmSettingsFactory = $OrmSettingsFactory;
    $this->logger = $logger;
  }

    /**
     * @return array|bool
     */
    public function getInfoIfActive(){
           $settings = $this->_OrmSettingsFactory->create();
    $settings = $settings->load('skuiq', 'name');
    if (!$settings['is_active'])
      return false;   // Ignore webhook if the connection is still not set up.
    return array( 'store_id' => $settings['store_id'], 'auth' => $settings['destination']);
  }

  public function post_to_endpoint($data_array, $store_id, $event_type, $timeout){

    $endpointUrl = "http://api.skuiq.test:3000/magento2/webhooks/" . $store_id. '/' . $event_type;
    $this->_httpClient->setUri($endpointUrl);
    $this->_httpClient->setConfig(['timeout' => $timeout]);
    $this->_httpClient->setParameterPost($data_array);
    $this->_httpClient->request(\Magento\Framework\HTTP\ZendClient::POST);

  }
}
