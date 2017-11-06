<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

namespace NetiTags\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Shopware\Models\Plugin\Plugin;

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
     *     name="title",
     *     nullable=false,
     * )
     */
    protected $title;

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
     * @var Plugin
     * @ORM\Column(
     *     type="integer",
     *     name="plugin_id",
     *     nullable=true
     * )
     * @ORM\ManyToOne(targetEntity="Shopware\Models\Plugin\Plugin")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $plugin;

    /**
     * @var ArrayCollection|Relation[]
     * @ORM\OneToMany(
     *     targetEntity="Relation",
     *     mappedBy="tableRegistry",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     */
    protected $relations;

    /**
     * TableRegistry constructor.
     */
    public function __construct()
    {
        $this->relations = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }


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
     * @param Plugin $plugin
     * @return $this
     */
    public function setPlugin(Plugin $plugin)
    {
        $this->plugin = $plugin;

        return $this;
    }

    /**
     * @return ArrayCollection|Relation[]
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * @param Relation $relation
     * @return $this
     */
    public function addRelation(Relation $relation)
    {
        if ($this->relations->contains($relation)) {
            return $this;
        }

        $this->relations->add($relation);
        // needed to update the owning side of the relationship!
        $relation->setTableRegistry($this);

        return $this;
    }

    /**
     * @param Relation $relation
     * @return $this
     */
    public function removeRelation(Relation $relation)
    {
        if (!$this->relations->contains($relation)) {
            return $this;
        }

        $this->relations->removeElement($relation);
        // needed to update the owning side of the relationship!
        $relation->setTableRegistry(null);

        return $this;
    }
}
