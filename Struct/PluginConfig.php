<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

namespace NetiTags\Struct;

use NetiFoundation\Struct\AbstractClass;

/**
 * Class PluginConfig
 * @package NetiTags\Struct
 */
class PluginConfig extends AbstractClass
{
    /**
     * @var bool $activeForSubshop - Enable plugin for this subshop
     */
    protected $activeForSubshop;

    /**
     * @return bool
     */
    public function isActiveForSubshop()
    {
        return $this->activeForSubshop;
    }
}