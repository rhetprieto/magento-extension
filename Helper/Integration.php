<?php

namespace Skuiq\SyncModule\Helper;

class Integration
{

    /**
     * @var \Magento\Integration\Model\IntegrationFactory
     * @var \Magento\Integration\Model\OauthService
     * @var \Magento\Integration\Model\AuthorizationService
     * @var \Skuiq\SyncModule\Logger\Logger
     */

    protected $integrationFactory;
    protected $oauthService;
    protected $authorizationService;
    protected $logger;

    /**
     * @param \Magento\Integration\Model\IntegrationFactory $integrationFactory
     * @param \Magento\Integration\Model\OauthService $oauthService
     * @param \Magento\Integration\Model\AuthorizationService $authorizationService
     * @param \Skuiq\SyncModule\Logger\Logger $logger
     */
    public function __construct(
        \Magento\Integration\Model\IntegrationFactory $integrationFactory,
        \Magento\Integration\Model\OauthService $oauthService,
        \Magento\Integration\Model\AuthorizationService $authorizationService,
        \Skuiq\SyncModule\Logger\Logger $logger
    ) {

        $this->integrationFactory = $integrationFactory;
        $this->oauthService = $oauthService;
        $this->authorizationService = $authorizationService;
        $this->logger = $logger;
    }

    /**
     * @return string
     */
    public function getOrCreateIntegration()
    {
        $name = 'SkuIQ';
        $email = 'support@skuiq.com';

        // Check if the integration already exists.
        $integration_factory = $this->integrationFactory->create();
        $current_integration = $integration_factory->load($name, 'name')->getData();
        if (!empty($current_integration)) {
            $consumer_data = $this->oauthService->loadConsumer($current_integration['consumer_id'])->getData();
            return $consumer_data['key'];
        }

        $new_integration =  [
            'name'       => $name,
            'email'      => $email,
            'status'     => '0',
            'setup_type' => '0'
        ];
        try {
            $integration = $integration_factory->setData($new_integration);
            $integration->save();

            $consumer = $this->oauthService->createConsumer(['name' => $name]);

            $integration->setConsumerId($consumer->getId());
            $integration->save();

            // We want to have all permissions for future feature releases.
            $this->authorizationService->grantAllPermissions($integration->getId());
            $consumer_data = $consumer->getData();
            return $consumer_data['key'];
        } catch (\Exception $exception) {
            $this->logger->critical($exception);
        }
    }
}
