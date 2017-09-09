<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service;

use Shopware\Components\Api\Exception\ValidationException;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Plugin\Plugin;

/**
 * Class TableRegistry
 *
 * @package NetiTags\Service
 */
class TableRegistry implements TableRegistryInterface
{
    /**
     * @var ModelManager
     */
    private $modelManager;

    /**
     * TableRegistry constructor.
     *
     * @param ModelManager $modelManager
     */
    public function __construct(
        ModelManager $modelManager
    ) {
        $this->modelManager = $modelManager;
    }

    /**
     * @param string $tableName
     *
     * @return \NetiTags\Models\TableRegistry|null
     */
    public function getByTableName($tableName)
    {
        $qbr = $this->modelManager->getRepository(\NetiTags\Models\TableRegistry::class);

        return $qbr->findOneBy(array(
            'tableName' => $tableName
        ));
    }

    /**
     * @param string $title
     * @param string $tableName
     * @param Plugin $plugin
     *
     * @return bool
     * @throws \Exception
     */
    public function register($title, $tableName, Plugin $plugin)
    {
        $qbr   = $this->modelManager->getRepository(\NetiTags\Models\TableRegistry::class);
        $model = $qbr->findOneBy(array(
            'tableName' => $tableName
        ));

        if (! empty($model)) {
            throw new \Exception(sprintf('Table "%s" already exsists', $tableName));
        }

        $model = new \NetiTags\Models\TableRegistry();
        $model->setTableName($tableName)
            ->setTitle($title)
            ->setPlugin($plugin);

        $violations = $this->modelManager->validate($model);
        if ((bool) $violations->count()) {
            throw new ValidationException($violations);
        }

        $this->modelManager->persist($model);
        $this->modelManager->flush($model);

        return true;
    }

    /**
     * @param string $tableName
     * @param Plugin $plugin
     *
     * @return bool
     * @throws \Exception
     */
    public function unregister($tableName, Plugin $plugin)
    {
        $qbr   = $this->modelManager->getRepository(\NetiTags\Models\TableRegistry::class);
        $model = $qbr->findOneBy(array(
            'tableName' => $tableName,
            'plugin'    => $plugin
        ));

        if (empty($model)) {
            throw new \Exception(sprintf('Table "%s" for Plugin "%s" not exsists', $tableName, $plugin->getName()));
        }

        $this->modelManager->remove($model);
        $this->modelManager->flush($model);

        return true;
    }
}
