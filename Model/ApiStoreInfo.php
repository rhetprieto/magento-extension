<?php
namespace Skuiq\SyncModule\Model;

use Skuiq\SyncModule\Api\StoreInfoInterface as ApiInterface;

/**
 * Endpoint that returns useful information about the store.
 */
class ApiStoreInfo implements ApiInterface
{
    /**
     * @var \Skuiq\SyncModule\Helper\GetStoreInfo
     */

    protected $storeInfo;

    /**
     * ApiStoreInfo constructor.
     * @param \Skuiq\SyncModule\Helper\GetStoreInfo $storeInfo
     */
    public function __construct(
        \Skuiq\SyncModule\Helper\GetStoreInfo $storeInfo
    ) {
        $this->storeInfo = $storeInfo;
    }

    /**
     * @return array|false
     */
    public function returnStoreInfo()
    {
        try {
            $response = $this->storeInfo->get();

            return json_encode($response);
        } catch (\Exception $exception) {
            $response = array('Error' => $exception->getMessage());
            return json_encode($response);
        }
    }
}
