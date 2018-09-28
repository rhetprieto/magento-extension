<?php
namespace Skuiq\SyncModule\Helper;

class GetStoreInfo
{
    /**
     * @var \Magento\Store\Model\Information
     * @var \Magento\Store\Model\Store
     * @var \Magento\Directory\Model\RegionFactory
     * @var \Magento\Framework\App\ProductMetadataInterface
     * @var \Skuiq\SyncModule\Logger\Logger
     */

    protected $storeInfo;
    protected $store;
    protected $regionFactory;
    protected $productMetadata;
    protected $logger;

    /**
     * @param \Magento\Store\Model\Information $storeInfo
     * @param \Magento\Store\Model\Store $store
     * @param \Magento\Directory\Model\RegionFactory $regionFactory
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     * @param \Skuiq\SyncModule\Logger\Logger $logger
     */

    public function __construct(
        \Magento\Store\Model\Information $storeInfo,
        \Magento\Store\Model\Store $store,
        \Magento\Directory\Model\RegionFactory $regionFactory,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Skuiq\SyncModule\Logger\Logger $logger
    ) {
        $this->storeInfo = $storeInfo;
        $this->store = $store;
        $this->regionFactory = $regionFactory;
        $this->productMetadata = $productMetadata;
        $this->logger = $logger;
    }
    /**
     * @return array
     */
    public function get()
    {
        try {
            $store_info = $this->storeInfo->getStoreInformationObject($this->store);
            $state = $this->regionFactory->create()->load($store_info->getRegionId())->getName();

            return [
                'signup_store_name'     => $store_info->getName(),
                'signup_store_phone'    => $store_info->getPhone(),
                'signup_store_address1' => $store_info['street_line1'],
                'signup_store_city'     => $store_info->getCity(),
                'signup_store_country'  => $store_info->getCountry(),
                'signup_store_state'    => $state,
                'signup_store_zip'      => $store_info->getPostcode(),
                'magento_version'       => $this->productMetadata->getVersion()
            ];
        } catch (\Exception $exception) {
            $this->logger->critical($exception);
        }
    }
}
