<?php
namespace Skuiq\SyncModule\Model;

use Skuiq\SyncModule\Api\StoreInfoInterface as ApiInterface;

/**
 * Model that contains updated cart information.
 */
class ApiStoreInfo implements ApiInterface {
  /**
  *  @var \Magento\Store\Model\StoreManagerInterface
  */
  protected $_storeManager;

  /**
  * @param \Magento\Store\Model\StoreManagerInterface $storeManager
  */

  public function __construct(
    \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
      $this->_storeManager = $storeManager;
    }

    public function get_store_info() {
      try {
        $response = array(
          'name'        => $this->_storeManager->getStore()
        );
        return json_encode($response);


      } catch (\Exception $exception) {
        $response = ['Error' => $exception->getMessage()];
        return json_encode($response);
    }
  }
}
