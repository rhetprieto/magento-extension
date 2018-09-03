<?php
namespace Skuiq\SyncModule\Model;

class OrmSettings extends \Magento\Framework\Model\AbstractModel implements OrmSettingsInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'skuiq_syncmodule_skuiq_settings';

    protected function _construct()
    {
        $this->_init('Skuiq\SyncModule\Model\ResourceModel\OrmSettings');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
