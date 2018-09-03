<?php

namespace Skuiq\SyncModule\Observer\Webhooks;

use Magento\Framework\Event\ObserverInterface;

class OrderDelete extends \Skuiq\SyncModule\Observer\Webhook{

    protected function get_event_path(){
      return [
        'path'    => 'orders/update',
        'timeout' => 3
       ];
    }

    protected function get_event_data(\Magento\Framework\Event\Observer $observer){
      $order = $observer->getEvent()->getOrder();
      return [
          'order' => $order->getData()
       ];
    }
}
