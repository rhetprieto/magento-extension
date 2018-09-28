<?php

namespace Skuiq\SyncModule\Observer\Webhooks;

use Magento\Framework\Event\ObserverInterface;

class OrderDelete implements ObserverInterface
{
    /**
     * @var \Skuiq\SyncModule\Observer\WebhookAssistant
     * @var \Skuiq\SyncModule\Logger\Logger
     */

    protected $webhookAssistant;
    protected $logger;

    /**
     * @param \Skuiq\SyncModule\Observer\WebhookAssistant $webhookAssistant
     * @param \Skuiq\SyncModule\Logger\Logger $logger
     */

    public function __construct(
        \Skuiq\SyncModule\Observer\WebhookAssistant $webhookAssistant,
        \Skuiq\SyncModule\Logger\Logger $logger
    ) {
        $this->webhookAssistant = $webhookAssistant;
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $store_info = $this->webhookAssistant->getInfoIfActive();
            if (!$store_info) {
                return;   //We ignore the webhook if still not connected.
            }

            $order_data = $observer->getEvent()->getOrder()->getData();
            $this->logger->info("Deleted Order - " . $order_data['entity_id']);

            $event_data = [
                'auth'     => $store_info['auth'],
                'order_id' => $order_data['entity_id']
            ];
            //Data, event and timeout.
            $this->webhookAssistant->postToEndpoint($event_data, $store_info['store_id'], 'orders/update', 10);
        } catch (\Exception $exception) {
            $this->logger->critical($exception);
        }
    }
}
