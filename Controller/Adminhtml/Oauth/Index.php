<?php

namespace Skuiq\SyncModule\Controller\Adminhtml\Oauth;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Backend\App\Action
{

  /**
   *  @var  \Magento\Store\Model\StoreManagerInterface
   *  @var \Magento\Integration\Model\IntegrationFactory
   *  @var \Magento\Integration\Model\OauthService
   *  @var \Magento\Integration\Model\AuthorizationService
   *  @var \Magento\Integration\Model\Oauth\Token
   */

  protected $_storeManager;
  protected $_integrationFactory;
  protected $_oauthService;
  protected $_authorizationService;
  protected $_token;

  protected $resultRedirect;
  /**
   * @param \Magento\Store\Model\StoreManagerInterface $storeManager
   * @param \Magento\Integration\Model\IntegrationFactory $integrationFactory
   * @param \Magento\Integration\Model\OauthService $oauthService
   * @param \Magento\Integration\Model\AuthorizationService $authorizationService
   * @param \Magento\Integration\Model\Oauth\Token $token
   */
   public function __construct(
     \Magento\Store\Model\StoreManagerInterface $storeManager,
     \Magento\Backend\App\Action\Context $context,
     \Magento\Integration\Model\IntegrationFactory $integrationFactory,
     \Magento\Integration\Model\OauthService $oauthService,
     \Magento\Integration\Model\AuthorizationService $authorizationService,
     \Magento\Integration\Model\Oauth\Token $token,
     \Magento\Framework\Controller\ResultFactory $result
     )
   {
     parent::__construct($context);
     $this->_integrationFactory = $integrationFactory;
     $this->_oauthService = $oauthService;
     $this->_authorizationService = $authorizationService;
     $this->_token = $token;
     $this->_storeManager = $storeManager;

     $this->resultFactory = $context->getResultRedirectFactory();
   }
	public function execute()
	{
    $name = 'SkuIQ';
    $email = 'support@skuiq.com';
    $endpoint = "http://app.skuiq.test:3000/register/magento2?";
    $resultRedirect = $this->resultRedirectFactory->create();
    $storeBaseUrl = $this->_storeManager->getStore()->getBaseUrl();
    $consumerData = array();

    // Check if the integration already exists.
    $integrationExists = $this->_integrationFactory->create()->load($name,'name')->getData();
    if(empty($integrationExists)){
        $integrationData = array(
            'name' => $name,
            'email' => $email,
            'status' => '1',
            'endpoint' => $endpoint,
            'setup_type' => '0'
        );
        try{
            $integrationFactory = $this->_integrationFactory->create();
            $integration = $integrationFactory->setData($integrationData);
            $integration->save();
            $integrationId = $integration->getId();
            $consumerName = 'Integration' . $integrationId;

            // We create a consumer
            $consumer = $this->_oauthService->createConsumer(['name' => $consumerName]);
            $consumerId = $consumer->getId();
            $integration->setConsumerId($consumer->getId());
            $integration->save();

            // We want to have full permissions for future features release.
            $this->_authorizationService->grantAllPermissions($integrationId);

            // Get the data from the consumer to send as parameters.
            $consumerData = $consumer->getData();

        }catch(Exception $e){
            echo 'Error : '.$e->getMessage();
        }
   	  } // If the consumer already exists.
      else {
          $consumerID = $integrationExists['consumer_id'];
          $consumer = $this->_oauthService->loadConsumer($consumerID);
          $consumerData = $consumer->getData();
      }

      $myargs = array(
          'oauth_consumer_key' => $consumerData['key'],
          'store_base_url'     => $storeBaseUrl
      );
      $resultRedirect->setUrl($endpoint .http_build_query($myargs));
      return $resultRedirect;

  }
      /**
       * Check current user permission
       *
       * @return bool
       */
      protected function _isAllowed()
      {
          return $this->_authorization->isAllowed('Skuiq_SyncModule::Config');
      }

}
