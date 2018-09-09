<?php

namespace Skuiq\SyncModule\Helper;

class Integration
{

  /**
   *  @var \Magento\Integration\Model\IntegrationFactory
   *  @var \Magento\Integration\Model\OauthService
   *  @var \Magento\Integration\Model\AuthorizationService
   */

  protected $_integrationFactory;
  protected $_oauthService;
  protected $_authorizationService;

  /**
   * @param \Magento\Integration\Model\IntegrationFactory $integrationFactory
   * @param \Magento\Integration\Model\OauthService $oauthService
   */
   public function __construct(
     \Magento\Integration\Model\IntegrationFactory $integrationFactory,
     \Magento\Integration\Model\OauthService $oauthService,
     \Magento\Integration\Model\AuthorizationService $authorizationService
     )
   {
     $this->_integrationFactory = $integrationFactory;
     $this->_oauthService = $oauthService;
     $this->_authorizationService = $authorizationService;

   }
	public function get_or_create_integration()
	{
    $name = 'SkuIQ';
    $email = 'support@skuiq.com';

    // Check if the integration already exists.
    $integrationFactory = $this->_integrationFactory->create();
    $currentIntegration = $integrationFactory->load($name,'name')->getData();
    if (!empty($currentIntegration)) {
      $consumerData = $this->_oauthService->loadConsumer($currentIntegration['consumer_id'])->getData();
      return $consumerData['key'];
      }
    $newIntegration = array (
            'name'       => $name,
            'email'      => $email,
            'status'     => '1',
            'setup_type' => '0'
        );
      try{
        $integration = $integrationFactory->setData($newIntegration);
        $integration->save();

        $consumer = $this->_oauthService->createConsumer(['name' => $name]);

        $integration->setConsumerId($consumer->getId());
        $integration->save();

        // We want to have all permissions for future feature releases.
        $this->_authorizationService->grantAllPermissions($integration->getId());
        $consumerData = $consumer->getData();
        return $consumerData['key'];

      }catch(Exception $e){
          #TODO : Log fatal errors.
          echo 'Error : '.$e->getMessage();
      }

    }

}
