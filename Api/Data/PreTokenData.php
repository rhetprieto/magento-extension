<?php

namespace Skuiq\SyncModule\Api\Data;

interface PreTokenServiceInterface
{
    /**
    * @return int[]
    **/
    public function getSettings();

    /**
    * @return string[]
    **/
    public function getExtra();
}
