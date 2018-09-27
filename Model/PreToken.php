<?php
namespace Skuiq\SyncModule\Model;

use Skuiq\SyncModule\Api\PreTokenServiceInterface as ApiInterface;

class PreToken implements ApiInterface
{

    /**
     * @var  \Magento\Store\Model\StoreManagerInterface
     * @var \Magento\Integration\Model\OauthService
     * @var \Magento\Integration\Model\Oauth\TokenFactory
     * @var \Magento\Integration\Model\Oauth\Token $token
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     * @var \Psr\Log\LoggerInterface
     */

    protected $storeManager;
    protected $oauthService;
    protected $tokenFactory;
    protected $dateHelper;
    protected $logger;
    protected $token;
    /**
     * @param \Magento\Integration\Model\OauthService $oauthService
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
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
    ) {

        $this->oauthService = $oauthService;
        $this->storeManager = $storeManager;
        $this->token = $token;
        $this->tokenFactory = $tokenFactory;
        $this->dateHelper = $dateHelper;
        $this->logger = $logger;
    }

    /**
     * @param string $oauth_consumer_key
     * @return array
     */
    public function getPreToken($oauth_consumer_key)
    {

        try {
            $consumer = $this->oauthService->loadConsumerByKey($oauth_consumer_key);
            $consumer_id = $consumer->getId();
            $consumer->setUpdatedAt($this->dateHelper->gmtDate());
            $consumer->save();
            if (!$consumer_id) {
                $response = array('Error' => "We couldn't find an associated id.");
                return json_encode($response);
            }

            $consumer_data = $consumer->getData();
            $token_factory = $this->tokenFactory->create();

            // We need to remove preexisting tokens for this consumer, otherwise we might error.
            $existing_token = $token_factory->load($consumer_id, 'consumer_id');
            $existing_token->delete();

            $verifier = $this->tokenFactory->create()->createVerifierToken($consumer_id);
            $store_base_url = $this->storeManager->getStore()->getBaseUrl();
            $response = array('oauth_consumer_key' => $consumer_data['key'],
                'oauth_consumer_secret' => $consumer_data['secret'],
                'store_base_url' => $store_base_url,
                'oauth_verifier' => $verifier->getVerifier()
            );
            return json_encode($response);
        } catch (\Exception $exception) {
            $this->logger->critical($exception);
            $response = array('Error' => $exception->getMessage());
            return json_encode($response);
        }
    }
}
