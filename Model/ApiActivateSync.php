<?php
namespace Skuiq\SyncModule\Model;

use Skuiq\SyncModule\Api\ActivateSkuiqSyncInterface as ApiInterface;

class ApiActivateSync implements ApiInterface
{
    /**
     * ApiActivateSync constructor.
     * @var \Skuiq\SyncModule\Model\OrmSettingsFactory
     */

    protected $ormSettingsFactory;

    /**
     * ApiActivateSync constructor.
     * @param \Skuiq\SyncModule\Model\OrmSettingsFactory  $OrmSettingsFactory
     */
    public function __construct(
        \Skuiq\SyncModule\Model\OrmSettingsFactory $ormSettingsFactory
    ) {
        $this->ormSettingsFactory = $ormSettingsFactory;
    }

    /**
     * @param $store_id
     * @param $destination
     * @return array
     */
    public function activateSync($store_id, $auth)
    {
        try {
            $settings_exists = $this->ormSettingsFactory->create()->load('skuiq', 'name')->getData();
            if (empty($settings_exists)) {
                $settings = $this->ormSettingsFactory->create();
                //auth will store the key will use to authenticate the webhooks.
                $data = [
                    'name'        => 'skuiq',
                    'store_id'    => $store_id,
                    'auth'        => $auth,
                    'is_active'   => 1
                ];
                $settings = $settings->setData($data);
                $settings->save();
                $response = ['success' => "The connection has been sucessfully established."];
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
