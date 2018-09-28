<?php
namespace Skuiq\SyncModule\Block;

class Config extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Integration\Model\IntegrationFactory
     * @var \Skuiq\SyncModule\Model\OrmSettingsFactory
     * @var \Magento\Integration\Model\OauthService
     * @var \Magento\Store\Model\StoreManagerInterface
     * @var \Skuiq\SyncModule\Helper\GetStoreInfo
     * @var \Skuiq\SyncModule\Logger\Logger
     */

    protected $integrationFactory;
    protected $settingsFactory;
    protected $oauthService;
    protected $storeManager;
    protected $getStoreInfo;
    protected $logger;

    /**
     * Config constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Integration\Model\IntegrationFactory $integrationFactory
     * @param \Magento\Integration\Model\OauthService $oauthService
     * @param \Skuiq\SyncModule\Model\OrmSettingsFactory $settingsFactory
     * @param \Skuiq\SyncModule\Helper\GetStoreInfo $getStoreInfo
     */

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Integration\Model\IntegrationFactory $integrationFactory,
        \Magento\Integration\Model\OauthService $oauthService,
        \Skuiq\SyncModule\Model\OrmSettingsFactory $settingsFactory,
        \Skuiq\SyncModule\Helper\GetStoreInfo $getStoreInfo,
        \Skuiq\SyncModule\Logger\Logger $logger
    ) {

        parent::__construct($context);
        $this->integrationFactory = $integrationFactory;
        $this->settingsFactory = $settingsFactory;
        $this->oauthService = $oauthService;
        $this->storeManager = $storeManager;
        $this->getStoreInfo= $getStoreInfo;
        $this->logger = $logger;
    }

    /**
     * @return bool / true only if : The integration exists, and the integration status is 1, and webhook is active.
     */
    public function isConnected()
    {
        try {
            $integration = $this->integrationFactory->create()->load('SkuIQ', 'name')->getData();
            return (!empty($integration) && $integration['status'] && $this->isWebhookActive()) ? true : false;
        } catch (\Exception $exception) {
                $this->logger->critical($exception);
                return false;
        }
    }

    /**
     * @return bool
     */
    public function isWebhookActive()
    {
        $settings = $this->settingsFactory->create();
        $settings = $settings->load('skuiq', 'name');
        return $settings['is_active'];
    }

    /**
     * @return string
     */
    public function getAuthUrl()
    {
        return $this->getUrl("skuiq_syncmodule/oauth"); // Controller Url
    }

    /**
     * @return array
     */
    public function getOauthData()
    {
        $integration = $this->integrationFactory->create()->load('SkuIQ', 'name')->getData();
        $consumer_id = $integration['consumer_id'];
        $consumer =  $this->oauthService->loadConsumer($consumer_id);
        $consumer_data = $consumer->getData();
        $store_base_url = $this->storeManager->getStore()->getBaseUrl();

        return array(
            'oauth_consumer_key' => $consumer_data['key'],
            'store_base_url'     => $store_base_url
        );
    }

    public function getStoreInfo()
    {
        return $this->getStoreInfo->get();
    }
}
