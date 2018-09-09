<?php
namespace Skuiq\SyncModule\Model;

use Skuiq\SyncModule\Api\ActivateSkuiqSyncInterface as ApiInterface;

/**
 * Model that contains updated cart information.
 */
class ApiActivateSync implements ApiInterface {

  protected $_OrmSettingsFactory;

  public function __construct(
    \Skuiq\SyncModule\Model\OrmSettingsFactory $OrmSettingsFactory
    )
    {
      $this->_OrmSettingsFactory = $OrmSettingsFactory;
    }

    public function activate_sync($store_id, $destination) {
      try {
        $settings_exists = $this->_OrmSettingsFactory->create()->load('skuiq','name')->getData();
        if(empty($settings_exists)) {
          $settings = $this->_OrmSettingsFactory->create();
            //destination will store the key will use to authenticate the webhooks.
            $data = array(
                'name'        => 'skuiq',
                'store_id'    => $store_id,
                'destination' => $destination,
                'is_active'   => 1
            );
            $settings = $settings->setData($data);
            $settings->save();
            $response = ['success' => "The connection has been sucessfully established. The setup screen has been deactivated for the customer."];
            return json_encode($response);
          } else {
            $response = ['success' => "The store has already been setup."];
            return json_encode($response);
          }

      } catch (\Exception $exception) {
        $response = ['Error' => $exception->getMessage()];
        return json_encode($response);
    }
  }
}
