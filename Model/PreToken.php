<?php
namespace Skuiq\SyncModule\Model;

use Skuiq\SyncModule\Api\PreTokenServiceInterface as ApiInterface;

class PreToken implements ApiInterface {

  /**
   *  @var  \Magento\Store\Model\StoreManagerInterface
   *  @var \Magento\Integration\Model\OauthService
   *  @var \Magento\Integration\Model\Oauth\TokenFactory
   *  @var \Magento\Integration\Model\Oauth\Token $token
   *  @var \Magento\Framework\Stdlib\DateTime\DateTime
   *  @var \Psr\Log\LoggerInterface
   */

  protected $_storeManager;
  protected $_oauthService;
  protected $_tokenFactory;
  protected $_dateHelper;
  protected $_logger;
  protected $_token;
  /**
   * @param \Magento\Integration\Model\OauthService $oauthService
   * @param \Magento\Integration\Model\AuthorizationService $authorizationService
   * @param \Magento\Integration\Model\Oauth\TokenFactory $tokenFactory
   * @param \Magento\Integration\Model\Oauth\Token $token
   * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateHelper
   * @param \Psr\Log\LoggerInterface $logger
   */

  public function __construct(
    \Magento\Integration\Model\OauthService $oauthService,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Integration\Model\Oauth\TokenFactory $tokenFactory,
    \Magento\Integration\Model\Oauth\Token $token,
    \Magento\Framework\Stdlib\DateTime\DateTime $dateHelper,
    \Psr\Log\LoggerInterface $logger
    )
    {
      $this->_oauthService = $oauthService;
      $this->_storeManager = $storeManager;
      $this->_token = $token;
      $this->_tokenFactory = $tokenFactory;
      $this->_dateHelper = $dateHelper;
      $this->_logger = $logger;

    }

    public function getPreToken($oauth_consumer_key) {

      try {
        $consumer = $this->_oauthService->loadConsumerByKey($oauth_consumer_key);
        $consumerId = $consumer->getId();
        $consumer->setUpdatedAt($this->_dateHelper->gmtDate());
        $consumer->save();
        if (!$consumerId) {
          $response = ['Error' => 'We couldnt find an associated id.'];
          return json_encode($response);
        }

        $consumerData = $consumer->getData();
        $tokenFactory = $this->_tokenFactory->create();

        // We need to remove preexistings tokens for this consumer, otherwise we might error.
        $existingToken = $tokenFactory->load($consumerId,'consumer_id');
        $existingToken->delete();

        // //$verifier = $this->_tokenFactory->create()->createVerifierToken($consumerId);
        // $tokenFactory->createVerifierToken($consumerId);
        // $tokenFactory->setType('access');
        // $tokenFactory->save();
        // $this->_token->createVerifierToken($consumer['entity_id']);
        // $this->_token->setType('access');
        // $this->_token->save();

        $verifier = $this->_tokenFactory->create()->createVerifierToken($consumerId);
        $storeBaseUrl = $this->_storeManager->getStore()->getBaseUrl();
        $response = [         'oauth_consumer_key' => $consumerData['key'],
                              'oauth_consumer_secret' => $consumerData['secret'],
                              'store_base_url' => $storeBaseUrl,
                              'oauth_verifier' => $verifier->getVerifier()
                      ];
          return json_encode($response);

      } catch (\Exception $exception) {
            $this->_logger->critical($exception);
            $response = ['Error' => $exception->getMessage()];
            return json_encode($response);
    }
  }
}
