<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     dpogodda
 */

namespace NetiTags\Models;

use Doctrine\ORM\Mapping as ORM;
use Shopware\Components\Model\ModelEntity;
use Shopware\Recovery\Update\DependencyInjection\Container;

/**
 * Class AbstractModel
 * @package NetiTags\Models
 * @ORM\MappedSuperclass()
 * @ORM\HasLifecycleCallbacks()
 */
abstract class AbstractModel extends ModelEntity
{
    /**
     * Intern identifier
     *
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * Date & time when the record was created
     *
     * @var \DateTime
     * @ORM\Column(
     *     type="datetime",
     *     name="create_date",
     *     nullable=false
     * )
     */
    protected $createDate;

    /**
     * Date & time when the record was changed the last time
     *
     * @var \DateTime
     * @ORM\Column(
     *     type="datetime",
     *     name="change_date",
     *     nullable=false
     * )
     */
    protected $changeDate;

    /**
     * Flags whether the record is enabled or not
     *
     * @var bool
     * @ORM\Column(
     *     type="boolean",
     *     name="enabled",
     *     nullable=false,
     *     options={"default": true}
     * )
     */
    protected $enabled = true;

    /**
     * Flags whether the record is deleted or not
     *
     * @var bool
     * @ORM\Column(
     *     type="boolean",
     *     name="deleted",
     *     nullable=false,
     *     options={"default": false}
     * )
     */
    protected $deleted = false;

    /**
     * Relation to the user who created the record
     *
     * @var \Shopware\Models\User\User
     * @ORM\Column(
     *     type="integer",
     *     name="created_by",
     *     nullable=true
     * )
     * @ORM\ManyToOne(targetEntity="Shopware\Models\User\User")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    protected $createdBy;

    /**
     * @ORM\PrePersist()
     * @throws \Exception
     */
    public function onPrePersist()
    {
        $date = new \DateTime();
        $this->setCreateDate($date);
        $this->setChangeDate($date);

        $this->setCreatedBy();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function onPreUpdate()
    {
        $this->setChangeDate(new \DateTime());
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * @param \DateTime $createDate
     * @return $this
     */
    protected function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getChangeDate()
    {
        return $this->changeDate;
    }

    /**
     * @param \DateTime $changeDate
     * @return $this
     */
    protected function setChangeDate($changeDate)
    {
        $this->changeDate = $changeDate;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (bool)$enabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     * @return $this
     */
    public function setDeleted($deleted)
    {
        $this->deleted = (bool)$deleted;

        return $this;
    }

    /**
     * @return \Shopware\Models\User\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @throws \Exception
     * @return $this
     */
    protected function setCreatedBy()
    {
        /** @var Container $container */
        $container = Shopware()->Container();

        /** @var \Enlight_Controller_Front $frontController */
        $frontController = $container->get('front');

        if ('backend' !== $frontController->Request()->getModuleName()) {
            return $this;
        }

        /** @var \Shopware_Components_Auth $auth */
        $auth = $container->get('auth');

        if (!$auth->hasIdentity()) {
            return $this;
        }

        $identity = $auth->getIdentity();

        $this->createdBy = $identity->id;

        return $this;
    }
}