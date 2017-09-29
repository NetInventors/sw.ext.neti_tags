<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

namespace NetiTags\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Relation
 *
 * @package NetiTags\Models
 * @ORM\Entity(repositoryClass="RelationRepository")
 * @ORM\Table(name="s_neti_tags_relation")
 */
class Relation extends AbstractModel
{
    /**
     * @ORM\Column(type="integer", name="tag_id", nullable=true)
     */
    protected $tagId;

    /**
     * @var Tag
     * @ORM\ManyToOne(
     *     targetEntity="Tag",
     *     inversedBy="relation"
     * )
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $tag;

    /**
     * @ORM\Column(type="integer", name="table_registry_id")
     */
    protected $tableRegistryId;

    /**
     * @var TableRegistry
     * @ORM\ManyToOne(
     *     targetEntity="TableRegistry",
     *     inversedBy="relation"
     * )
     * @ORM\JoinColumn(name="table_registry_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $tableRegistry;

    /**
     * @ORM\Column(type="integer", name="relation_id")
     */
    protected $relationId;

    /**
     * @return Tag
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param Tag $tag
     *
     * @return $this
     */
    public function setTag(Tag $tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @return TableRegistry
     */
    public function getTableRegistry()
    {
        return $this->tableRegistry;
    }

    /**
     * @param TableRegistry $tableRegistry
     *
     * @return $this
     */
    public function setTableRegistry(TableRegistry $tableRegistry)
    {
        $this->tableRegistry = $tableRegistry;

        return $this;
    }

    /**
     * Gets the value of relationId from the record
     *
     * @return mixed
     */
    public function getRelationId()
    {
        return $this->relationId;
    }

    /**
     * Sets the Value to relationId in the record
     *
     * @param mixed $relationId
     *
     * @return self
     */
    public function setRelationId($relationId)
    {
        $this->relationId = $relationId;

        return $this;
    }

    /**
     * Sets the Value to tagId in the record
     *
     * @param mixed $tagId
     *
     * @return self
     */
    public function setTagId($tagId)
    {
        $this->tagId = $tagId;

        return $this;
    }

    /**
     * Sets the Value to tableRegistryId in the record
     *
     * @param mixed $tableRegistryId
     *
     * @return self
     */
    public function setTableRegistryId($tableRegistryId)
    {
        $this->tableRegistryId = $tableRegistryId;

        return $this;
    }
}
