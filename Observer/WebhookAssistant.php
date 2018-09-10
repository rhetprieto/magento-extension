<?php

namespace Skuiq\SyncModule\Observer;

class WebhookAssistant
{
  /**
   * @var  \Magento\Framework\HTTP\ZendClient
   * @var  \Skuiq\SyncModule\Logger\Logger
   */

  protected $_httpClient;
  protected $_OrmSettingsFactory;
  protected $_logger;

  /**
  * @param \Magento\Framework\HTTP\ZendClient $httpClient
  * @param \Skuiq\SyncModule\Logger\Logger $logger
  */

  public function __construct(
    \Magento\Framework\HTTP\ZendClient $httpClient,
    \Skuiq\SyncModule\Model\OrmSettingsFactory $OrmSettingsFactory,
    \Skuiq\SyncModule\Logger\Logger $logger
    )
  {
    $this->_httpClient = $httpClient;
    $this->_OrmSettingsFactory = $OrmSettingsFactory;
    $this->_logger = $logger;
  }

  public function get_store_info_if_extension_is_active(){
    $settings = $this->_OrmSettingsFactory->create();
    $settings = $settings->load('skuiq', 'name');
    if (!$settings['is_active'])
      return false;   // Ignore webhook if the connection is still not set up.
    return array( 'store_id' => $settings['store_id'], 'auth' => $settings['destination']);
  }

  public function post_to_endpoint($data_array, $store_id, $event_type, $timeout){
                  #api.skuiq etc
    $endpointUrl = "http://app.skuiq.test:3000/register/magento2" . $store_id. '/' . $event_type;
    $this->_httpClient->setUri($endpointUrl);
    $this->_httpClient->setConfig(['timeout' => $timeout]);
    $this->_httpClient->setParameterPost($data_array);
    $this->_httpClient->request(\Magento\Framework\HTTP\ZendClient::POST);

  }
}
