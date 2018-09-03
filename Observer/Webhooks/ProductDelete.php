<?php

namespace Skuiq\SyncModule\Observer\Webhooks;

use Magento\Framework\Event\ObserverInterface;

class ProductDelete extends \Skuiq\SyncModule\Observer\Webhook{

    protected function get_event_path(){
      return [
        'path'    => 'products/delete',
        'timeout' => 3
       ];
    }

    protected function get_event_data(\Magento\Framework\Event\Observer $observer){
      $product = $observer->getEvent()->getProduct();
      return [
          'product' => $product->getData()
       ];
    }
}
