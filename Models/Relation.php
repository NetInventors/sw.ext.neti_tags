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
}