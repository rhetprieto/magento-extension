<?php

namespace Skuiq\SyncModule\Observer;

use Magento\Framework\Event\ObserverInterface;

class Webhook implements ObserverInterface
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

  public function execute(\Magento\Framework\Event\Observer $observer)
  {
    try {
      $settings = $this->_OrmSettingsFactory->create();
      $settings = $settings->load('skuiq', 'name');
      if (!$settings['is_active'])
        return;   // Ignore webhook if the connection is still not set up.

      $event_path = $this->get_event_path();
      $event_data = $this->get_event_data($observer);

      $this->_logger->info($event_path['path']. " - " . current($event_data)['entity_id'] );
      //$endpointUrl = "https://api.skuiq.com/magento2/webhooks/". $settings['store_id'] . '/' . $event_path['path'];
      $endpointUrl = "http://app.skuiq.test:3000/auto-shop/magento2_oauth/" . $settings['store_id'] . '/' . $event_path['path'];
      $this->_httpClient->setUri($endpointUrl);

      $this->_httpClient->setConfig(['timeout' => $event_path['timeout']]);
      $this->_httpClient->setParameterPost(
          [ // Destination field will be used to authenticate this requests within our app.
              'auth'       => $settings['destination'],
              'event_data' => $event_data
          ]
      );
      $this->_httpClient->request(\Magento\Framework\HTTP\ZendClient::POST);
    }
    catch (\Exception $exception){
        $this->_logger->critical($exception);
    }
  }

    // Kids know best.
    protected function get_event_path(){
      return false;
    }
    protected function get_event_data(\Magento\Framework\Event\Observer $observer){
      return false;
    }
}
