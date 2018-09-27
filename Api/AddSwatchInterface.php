<?php
namespace Skuiq\SyncModule\Api;

interface AddSwatchInterface
{
    /**
     * Gets the token.
     * @api
     * @param int option_id
     * @param int store_id
     * @param int type
     * @param int value
     * @return string
     */
    public function addSwatch($option_id, $store_id, $type, $value);
}
