<?php
namespace Skuiq\SyncModule\Model;

use Magento\Framework\DataObject\IdentityInterface;

class OrmSettings extends \Magento\Framework\Model\AbstractModel implements
    OrmSettingsInterface,
    IdentityInterface
{
    const CACHE_TAG = 'skuiq_syncmodule_skuiq_settings';

    /**
     * OrmSettings constructor.
     */
    protected function __construct()
    {
        $this->_init('Skuiq\SyncModule\Model\ResourceModel\OrmSettings');
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
