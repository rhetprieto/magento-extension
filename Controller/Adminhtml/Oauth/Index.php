<?php

namespace Skuiq\SyncModule\Controller\Adminhtml\Oauth;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Backend\App\Action
{

  /**
   *  @var \Magento\Integration\Model\IntegrationFactory
   *  @var \Magento\Integration\Model\OauthService
   *  @var \Magento\Integration\Model\AuthorizationService
   *  @var \Magento\Integration\Model\Oauth\Token
   */
  protected $_integrationFactory;
  protected $_oauthService;
  protected $_authorizationService;
  protected $_token;

  protected $resultRedirect;
  /**
   * @param \Magento\Integration\Model\IntegrationFactory $integrationFactory
   * @param \Magento\Integration\Model\OauthService $oauthService
   * @param \Magento\Integration\Model\AuthorizationService $authorizationService
   * @param \Magento\Integration\Model\Oauth\Token $token
   */
   public function __construct(
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

     $this->resultFactory = $context->getResultRedirectFactory();
   }
	public function execute()
	{
    $name = 'SkuIQ';
    $email = 'support@skuiq.com';
    $endpoint = "http://app.skuiq.test:3000/register/magento2";

    // Code to check whether the Integration is already present or not
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

            // This integration will have full access.
            $this->_authorizationService->grantAllPermissions($integrationId);

            // // We activate and authorize the token.
            // $uri = $this->_token->createVerifierToken($consumerId);
            //
            // $this->_token->setType('access');
            // $this->_token->save();

            // $resultRedirect = $this->resultRedirectFactory->create();
            // // $resultRedirect->setUrl('https://app.skuiq.com/register/magento2');
            // $resultRedirect->setUrl('http://app.skuiq.test:3000/register/magento2');
            // return $resultRedirect;

        }catch(Exception $e){
            // We should notify that it's been errors on the process. We should give the option to "retry"
            echo 'Error : '.$e->getMessage();
        }


	}
}
      protected function sendInfoToSkuiq() {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('customer/account/');
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
