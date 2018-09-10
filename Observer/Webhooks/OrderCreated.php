<?php

namespace Skuiq\SyncModule\Observer\Webhooks;

use Magento\Framework\Event\ObserverInterface;

class OrderCreated implements ObserverInterface
{
  /**
   * @var \Skuiq\SyncModule\Logger\Logger
   */

  protected $_webhookAssistant;
  protected $_logger;

  /**
  * @param \Skuiq\SyncModule\Logger\Logger $logger
  */

  public function __construct(
    \Skuiq\SyncModule\Observer\WebhookAssistant $webhookAssistant,
    \Skuiq\SyncModule\Logger\Logger $logger
    )
  {
    $this->_webhookAssistant = $webhookAssistant;
    $this->_logger = $logger;
  }

  public function execute(\Magento\Framework\Event\Observer $observer)
  {
    try {
      $store_info = $this->_webhookAssistant->get_store_info_if_extension_is_active();
      if (!$store_info)
        return;   //We ignore the webhook if still not connected.

      $orderData = $observer->getEvent()->getInvoice()->getOrder()->getData();
      $this->_logger->info("Created/Updated Order - ". $orderData['entity_id']);

      $event_data = [
          'auth'   => $store_info['destination'],
          'order'  => $orderData
      ];
      //Data, event and timeout.
      $this->_webhookAssistant->post_to_endpoint($event_data, $store_info['store_id'] , 'orders/created', 10);

    }
    catch (\Exception $exception){
        $this->_logger->critical($exception);
    }
  }
}
