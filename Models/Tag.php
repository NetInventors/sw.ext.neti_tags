<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

namespace NetiTags\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Tag
 *
 * @package NetiTags\Models
 * @ORM\Entity(repositoryClass="TagRepository")
 * @ORM\Table(name="s_neti_tags_tag")
 */
class Tag extends AbstractModel
{
    /**
     * Title of the record
     *
     * @var string
     * @ORM\Column(
     *     type="string",
     *     name="title",
     *     nullable=false
     * )
     */
    protected $title;

    /**
     * Description of the record
     *
     * @var string
     * @ORM\Column(
     *     type="text",
     *     name="description",
     *     nullable=true
     * )
     */
    protected $description;

    /**
     * @var Relation
     * @ORM\OneToMany(
     *     targetEntity="Relation",
     *     mappedBy="tag",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     */
    protected $relations;

    /**
     * Tag constructor.
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

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
     * Sets the Value to relations in the record
     *
     * @param Relation $relations
     *
     * @return self
     */
    public function setRelations($relations)
    {
        $updated = new ArrayCollection();
        foreach ($relations as $relation) {
            if (! $relation instanceof Relation) {
                continue;
            }

            $relation->setTag($this);
            $updated->add($relation);
        }

        foreach ($this->relations as $relation) {
            if (! $updated->contains($relation)) {
                $this->relations->removeElement($relation);
                Shopware()->Container()->get('models')->remove($relation);
            }
        }

        $this->relations = $relations;
    }

}
