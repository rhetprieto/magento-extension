<?php
namespace Skuiq\SyncModule\Model;

use Skuiq\SyncModule\Api\StoreInfoInterface as ApiInterface;

/**
 * Model that contains updated cart information.
 */
class ApiStoreInfo implements ApiInterface {
  /**
  *  @var \Magento\Store\Model\StoreManagerInterface
  *  @var \Magento\Store\Model\Information
  *  @var \Magento\Store\Model\Store
  *  @var \Magento\Directory\Model\RegionFactory
  */

  protected $_storeManager;
  protected $_storeInfo;
  protected $_store;
  protected $_regionFactory;

  /**
  * @param \Magento\Store\Model\StoreManagerInterface $storeManager
  * @param \Magento\Store\Model\Information $storeInfo
  * @param \Magento\Store\Model\Store $store
  * @param \Magento\Directory\Model\RegionFactory $regionFactory
  */

  public function __construct(
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Store\Model\Information $storeInfo,
    \Magento\Store\Model\Store $store,
    \Magento\Directory\Model\RegionFactory $regionFactory
    )
    {
      $this->_storeManager = $storeManager;
      $this->_storeInfo = $storeInfo;
      $this->_store = $store;
      $this->_regionFactory = $regionFactory;
    }

    public function get_store_info() {
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
          'signup_store_zip'      => $storeInfo->getPostcode()
        );

        return json_encode($response);

      } catch (\Exception $exception) {
        $response = ['Error' => $exception->getMessage()];
        return json_encode($response);
    }
  }
}
