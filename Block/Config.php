<?php
namespace Skuiq\SyncModule\Block;

class Config extends \Magento\Framework\View\Element\Template
{
	/**
	 *  @var \Magento\Integration\Model\IntegrationFactory
	 * 	@var \Magento\Integration\Model\OauthService
	 *  @var \Magento\Store\Model\StoreManagerInterface
	 */
			protected $_integrationFactory;
			protected $_OrmSettingsFactory;
			protected $_oauthService;
			protected $_storeManager;
			protected $_getStoreInfo;

	/**
	*   @param \Magento\Store\Model\StoreManagerInterface $storeManager
	 *  @param \Magento\Integration\Model\IntegrationFactory $integrationFactory
	 * 	@param \Magento\Integration\Model\OauthService $oauthService
	 */

	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Integration\Model\IntegrationFactory $integrationFactory,
		\Magento\Integration\Model\OauthService $oauthService,
		\Skuiq\SyncModule\Model\OrmSettingsFactory $OrmSettingsFactory,
		\Skuiq\SyncModule\Helper\GetStoreInfo $getStoreInfo
		)
	{
		parent::__construct($context);
		$this->_integrationFactory = $integrationFactory;
		$this->_OrmSettingsFactory = $OrmSettingsFactory;
		$this->_oauthService = $oauthService;
		$this->_storeManager = $storeManager;
		$this->_getStoreInfo= $getStoreInfo;

	}

	public function isConnected()
	{
		try {
			$integrationExists = $this->_integrationFactory->create()->load('SkuIQ','name')->getData();
			if (!empty($integrationExists) && $this->is_webhook_active())
				return true;
			else
				return false;
			}
		catch(Exception $e){
				echo 'Error : '.$e->getMessage();
				return false;
		}
	}

	public function is_webhook_active(){
		$settings = $this->_OrmSettingsFactory->create();
		$settings = $settings->load('skuiq', 'name');
		return $settings['is_active'];
	}


	public function getAuthUrl(){
    return $this->getUrl("skuiq_syncmodule/oauth"); // Controller Url
	}

	public function getOauthData(){
		$currentIntegration = $this->_integrationFactory->create()->load('SkuIQ','name')->getData();
		$consumerID = $currentIntegration['consumer_id'];
		$consumer =  $this->_oauthService->loadConsumer($consumerID);
		$consumerData = $consumer->getData();
		$storeBaseUrl = $this->_storeManager->getStore()->getBaseUrl();

		$oauthData = array(
				'oauth_consumer_key' => $consumerData['key'],
				'store_base_url'     => $storeBaseUrl
		);

		return $oauthData;
	}

	public function getStoreInfo(){
		return $this->_getStoreInfo->get();
	}
}
