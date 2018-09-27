<?php
namespace Skuiq\SyncModule\Api;

interface ActivateSkuiqSyncInterface
{
    /**
     * Gets the token.
     * @api
     * @param int store_id
     * @param string destination
     * @return array
     */
    public function activateSync($store_id, $destination);
}
