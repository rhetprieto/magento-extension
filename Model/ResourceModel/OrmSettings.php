<?php

namespace Skuiq\SyncModule\Model\ResourceModel;

class OrmSettings extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('skuiq_syncmodule_skuiq_settings', 'skuiq_syncmodule_skuiq_settings_setting_id');
    }
}
