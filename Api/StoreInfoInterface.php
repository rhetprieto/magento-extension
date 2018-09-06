<?php
namespace Skuiq\SyncModule\Api;

interface StoreInfoInterface {
    /**
     * Gets the token.
     * @api
     * @return array
     */
    public function get_store_info();
}
