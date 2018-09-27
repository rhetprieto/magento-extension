<?php
namespace Skuiq\SyncModule\Api;

interface PreTokenServiceInterface
{
    /**
     * Gets the token.
     * @api
     * @param string $oauth_consumer_key
     * @return array
     */
    public function getPreToken($oauth_consumer_key);
}
