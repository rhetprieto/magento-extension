<?php

namespace Skuiq\SyncModule\Observer\Webhooks;

use Magento\Framework\Event\ObserverInterface;

class ProductSave extends \Skuiq\SyncModule\Observer\Webhook{

    protected function get_event_path(){
      return [
        'path'    => 'products/update',
        'timeout' => 4
       ];
    }

    protected function get_event_data(\Magento\Framework\Event\Observer $observer){
      $product = $observer->getEvent()->getProduct();
      return [
          'product' => $product->getData()
       ];
    }
}
