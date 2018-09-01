<?php

namespace Skuiq\SyncModule\Observer;

use Magento\Framework\Event\ObserverInterface;



class OrderCreated implements ObserverInterface
{
  /**
   * @var  \Magento\Framework\HTTP\ZendClient
   */

  protected $_httpClient;

  /**
  * @param \Magento\Framework\HTTP\ZendClient $httpClient
  */

  public function __construct(
    \Magento\Framework\HTTP\ZendClient $httpClient,
    \Psr\Log\LoggerInterface $logger
    )
  {
    $this->_httpClient = $httpClient;
  }

  public function execute(\Magento\Framework\Event\Observer $observer)
  {

    $order = $observer->getEvent()->getOrder();

    $endpointUrl = "http://app.skuiq.test:3000/auto-shop/magento2_oauth";
    $this->_httpClient->setUri($endpointUrl);

    $this->_httpClient->setConfig(['timeout' => 5]); // Setting a 5 seconds before timing out.
    $myEventData = $observer->getData('myEventData');
    $this->_httpClient->setParameterPost(
        [
            'order' => $order->getData()
        ]
    );
    $this->_httpClient->request(\Magento\Framework\HTTP\ZendClient::POST);

  }
}
