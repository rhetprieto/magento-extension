<?php
namespace Skuiq\SyncModule\Helper;

class GetStoreInfo{
  /**
  *  @var \Magento\Store\Model\Information
  *  @var \Magento\Store\Model\Store
  *  @var \Magento\Directory\Model\RegionFactory
  *  @var \Magento\Framework\App\ProductMetadataInterface
  */

  protected $_storeInfo;
  protected $_store;
  protected $_regionFactory;
  protected $_productMetadata;

  /**
  * @param \Magento\Store\Model\Information $storeInfo
  * @param \Magento\Store\Model\Store $store
  * @param \Magento\Directory\Model\RegionFactory $regionFactory
  * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
  */

  public function __construct(
    \Magento\Store\Model\Information $storeInfo,
    \Magento\Store\Model\Store $store,
    \Magento\Directory\Model\RegionFactory $regionFactory,
    \Magento\Framework\App\ProductMetadataInterface $productMetadata
    )
    {
      $this->_storeInfo = $storeInfo;
      $this->_store = $store;
      $this->_regionFactory = $regionFactory;
      $this->_productMetadata = $productMetadata;
    }

    /**
     * @return array
     */
    public function get() {
      try {
        $storeInfo = $this->_storeInfo->getStoreInformationObject($this->_store);
        $state = $this->_regionFactory->create()->load($storeInfo->getRegionId())->getName();

        $response = array(
          'signup_store_name'     => $storeInfo->getName(),
          'signup_store_phone'    => $storeInfo->getPhone(),
          'signup_store_address1' => $storeInfo['street_line1'],
          'signup_store_city'     => $storeInfo->getCity(),
          'signup_store_country'  => $storeInfo->getCountry(),
          'signup_store_state'    => $state,
          'signup_store_zip'      => $storeInfo->getPostcode(),
          'magento_version'       => $this->_productMetadata->getVersion()
        );

        return $response;

      } catch (\Exception $exception) {
    }
  }
}
