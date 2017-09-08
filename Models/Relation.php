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
 * @package NetiTags\Models
 * @ORM\Entity(repositoryClass="RelationRepository")
 * @ORM\Table(name="s_neti_tags_relation")
 */
class Relation extends AbstractModel
{
    /**
     * @var Tag
     * @ORM\ManyToOne(
     *     targetEntity="Tag",
     *     inversedBy="relation"
     * )
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    protected $tag;

    /**
     * @var TableRegistry
     * @ORM\ManyToOne(
     *     targetEntity="TableRegistry",
     *     inversedBy="relation"
     * )
     * @ORM\JoinColumn(referencedColumnName="id")
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
}
