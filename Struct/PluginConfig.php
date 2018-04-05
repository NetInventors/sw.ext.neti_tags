<?php
/**
 * @copyright  Copyright (c) 2018, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Struct;

use NetiFoundation\Struct\AbstractClass;

/**
 * Class PluginConfig
 *
 * @package NetiTags\Struct
 */
class PluginConfig extends AbstractClass
{
    /**
     * @var boolean
     */
    protected $deletprotecting = true;

    /**
     * Gets the value of deletprotecting from the record
     *
     * @return bool
     */
    public function isDeletprotecting()
    {
        return $this->deletprotecting;
    }
}
