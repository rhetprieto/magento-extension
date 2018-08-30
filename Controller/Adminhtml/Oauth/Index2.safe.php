<?php

namespace Skuiq\SyncModule\Controller\Adminhtml\Oauth;

use Magento\Framework\App\Bootstrap;


// #include_once('../app/bootstrap.php'); - Relative address
// #Absolute for testing purpose
// include_once('/users/rhet/sites/www/magento2/app/bootstrap.php');
// $bootstrap = Bootstrap::create(BP, $_SERVER);
//
//
// $objectManager = $bootstrap->getObjectManager();
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

//Set your Data
$name = 'SkuIQ';
$email = 'support@skuiq.com';
$endpoint = "http://app.skuiq.test:3000/register/magento2";

// Code to check whether the Integration is already present or not
$integrationExists = $objectManager->get('Magento\Integration\Model\IntegrationFactory')->create()->load($name,'name')->getData();
if(empty($integrationExists)){
    $integrationData = array(
        'name' => $name,
        'email' => $email,
        'status' => '1',
        'endpoint' => $endpoint,
        'setup_type' => '0'
    );
    try{
        // Code to create Integration
        $integrationFactory = $objectManager->get('Magento\Integration\Model\IntegrationFactory')->create();
        $integration = $integrationFactory->setData($integrationData);
        $integration->save();
        $integrationId = $integration->getId();$consumerName = 'Integration' . $integrationId;


        // Code to create consumer key
        $oauthService = $objectManager->get('Magento\Integration\Model\OauthService');
        $consumer = $oauthService->createConsumer(['name' => $consumerName]);
        $consumerId = $consumer->getId();
        $integration->setConsumerId($consumer->getId());
        $integration->save();
        // Code to grant permission
        $authrizeService = $objectManager->get('Magento\Integration\Model\AuthorizationService');
        $authrizeService->grantAllPermissions($integrationId);

        // Code to Activate and Authorize
        $token = $objectManager->get('Magento\Integration\Model\Oauth\Token');
        $uri = $token->createVerifierToken($consumerId);

        $token->setType('access');
        $token->save();

    }catch(Exception $e){
        // We should notify that it's been errors on the process. We should give the option to "retry"
        echo 'Error : '.$e->getMessage();
    }
}
