<?php
namespace Skuiq\SyncModule\Block;

class Config extends \Magento\Framework\View\Element\Template
{
	/**
	 *  @var \Magento\Integration\Model\IntegrationFactory
	 */
			protected $_integrationFactory;
			protected $_OrmSettingsFactory;

	/**
	 *  @param \Magento\Integration\Model\IntegrationFactory $integrationFactory
	 */

	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Integration\Model\IntegrationFactory $integrationFactory,
		\Skuiq\SyncModule\Model\OrmSettingsFactory $OrmSettingsFactory
		)
	{
		parent::__construct($context);
		$this->_integrationFactory = $integrationFactory;
		$this->_OrmSettingsFactory = $OrmSettingsFactory;

	}

	public function isConnected()
	{
		try {
			$integrationExists = $this->_integrationFactory->create()->load('SkuIQ-Sync','name')->getData();
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
}
