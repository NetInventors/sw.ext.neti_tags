<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

namespace NetiTags\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class TableRegistry
 * @package NetiTags\Models
 * @ORM\Entity(repositoryClass="TableRegistryRepository")
 * @ORM\Table(name="s_neti_tags_table_registry")
 */
class TableRegistry extends AbstractModel
{
    /**
     * @var string
     * @ORM\Column(
     *     type="string",
     *     name="table_name",
     *     nullable=false,
     *     unique=true
     * )
     */
    protected $tableName;

    /**
     * @var \Shopware\Models\Plugin\Plugin
     * @ORM\Column(
     *     type="integer",
     *     name="plugin_id",
     *     nullable=true
     * )
     * @ORM\ManyToOne(targetEntity="Shopware\Models\Plugin\Plugin")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    protected $plugin;

    /**
     * @var Relation
     * @ORM\OneToMany(
     *     targetEntity="Relation",
     *     mappedBy="tableRegistry",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     */
    protected $relation;

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     * @return $this
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * @return \Shopware\Models\Plugin\Plugin
     */
    public function getPlugin()
    {
        return $this->plugin;
    }

    /**
     * @param \Shopware\Models\Plugin\Plugin $plugin
     * @return $this
     */
    public function setPlugin(\Shopware\Models\Plugin\Plugin $plugin)
    {
        $this->plugin = $plugin;

        return $this;
    }
}