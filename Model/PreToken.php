<?php
namespace Skuiq\SyncModule\Model;

use Skuiq\SyncModule\Api\PreTokenServiceInterface as ApiInterface;

/**
 * Model that contains updated cart information.
 */
class PreToken implements ApiInterface {

  /**
   *  @var  \Magento\Store\Model\StoreManagerInterface
   *  @var \Magento\Integration\Model\OauthService
   *  @var \Magento\Integration\Model\Oauth\TokenFactory
   *  @var \Magento\Framework\Stdlib\DateTime\DateTime
   *  @var \Psr\Log\LoggerInterface
   */

  protected $_storeManager;
  protected $_oauthService;
  protected $_tokenFactory;
  protected $_dateHelper;
  protected $_logger;
  /**
   * @param \Magento\Integration\Model\OauthService $oauthService
   * @param \Magento\Integration\Model\AuthorizationService $authorizationService
   * @param \Magento\Integration\Model\Oauth\TokenFactory $tokenFactory
   * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateHelper
   * @param \Psr\Log\LoggerInterface $logger
   */

  public function __construct(
    \Magento\Integration\Model\OauthService $oauthService,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Integration\Model\Oauth\TokenFactory $tokenFactory,
    \Magento\Framework\Stdlib\DateTime\DateTime $dateHelper,
    \Psr\Log\LoggerInterface $logger
    )
    {
      $this->_oauthService = $oauthService;
      $this->_storeManager = $storeManager;
      $this->_tokenFactory = $tokenFactory;
      $this->_dateHelper = $dateHelper;
      $this->_logger = $logger;

    }

    public function getPreToken($oauth_consumer_key) {

      try {
        $consumer = $this->_oauthService->loadConsumerByKey($oauth_consumer_key);

        $consumer->setUpdatedAt($this->_dateHelper->gmtDate());
        $consumer->save();
        if (!$consumer->getId()) {
          $response = ['Error' => 'We couldnt find an associated id.'];
          return json_encode($response);
        }

        $consumerData = $consumer->getData();

        $verifier = $this->_tokenFactory->create()->createVerifierToken($consumer['entity_id']);
        $storeBaseUrl = $this->_storeManager->getStore()->getBaseUrl();


          $response = [       'oauth_consumer_key' => $oauth_consumer_key,
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
