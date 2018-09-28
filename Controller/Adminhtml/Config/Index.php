<?php

namespace Skuiq\SyncModule\Controller\Adminhtml\Config;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Backend\App\Action\Context $context
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    protected $integrationHelper;
    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Skuiq\SyncModule\Helper\Integration $integrationHelper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Skuiq\SyncModule\Helper\Integration $integrationHelper
    ) {
             parent::__construct($context);
             $this->resultPageFactory = $resultPageFactory;

             $this->integrationHelper = $integrationHelper;
    }

    /**
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
           $this->integrationHelper->getOrCreateIntegration();
           return  $resultPage = $this->resultPageFactory->create();
    }
}
