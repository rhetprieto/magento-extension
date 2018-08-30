<?php
namespace Skuiq\SyncModule\Block;

class Config extends \Magento\Framework\View\Element\Template
{
	/**
	 *  @var \Magento\Integration\Model\IntegrationFactory
	 */
			protected $_integrationFactory;

	/**
	 *  @param \Magento\Integration\Model\IntegrationFactory $integrationFactory
	 */

	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Integration\Model\IntegrationFactory $integrationFactory
		)
	{
		parent::__construct($context);
		$this->_integrationFactory = $integrationFactory;
	}

	public function isConnected()
	{
		$integrationExists = $this->_integrationFactory->create()->load('SkuIQ','name')->getData();
		if (!empty($integrationExists))
			return TRUE;
		else
			return FALSE;
	}

	public function getAuthUrl(){
    return $this->getUrl("skuiq_syncmodule/oauth"); // Controller Url
	}
}
