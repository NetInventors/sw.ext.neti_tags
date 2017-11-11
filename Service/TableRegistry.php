<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   NetiTags
 * @author     bmueller
 */

namespace NetiTags\Service;

use Doctrine\ORM\Mapping\ClassMetadata;
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
     * @param string $entityName
     * @param Plugin $plugin
     *
     * @return bool
     * @throws ValidationException
     * @internal param string $name
     */
    public function register($title, $tableName, $entityName, Plugin $plugin)
    {
        $repository = $this->modelManager->getRepository(\NetiTags\Models\TableRegistry::class);
        $qbr        = $repository->createQueryBuilder('t');
        $qbr->andWhere(
            $qbr->expr()->orX(
                $qbr->expr()->eq('t.tableName', $qbr->expr()->literal($tableName)),
                $qbr->expr()->eq('t.entityName', $qbr->expr()->literal($entityName))
            )
        );
        $model = $qbr->getQuery()->getSingleResult();

        if (empty($model)) {
            $model = new \NetiTags\Models\TableRegistry();
        }

        $model->setTableName($tableName)
            ->setEntityName($entityName)
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
     * @param string $name
     *
     * @return array
     */
    private function getNames($name)
    {
        $tableName     = null;
        $entityName    = null;
        $classMetaData = null;

        $this->clearEntityCache();
        try {
            $classMetaData = $this->modelManager->getClassMetadata($name);
            $entityName    = $classMetaData->getName();
            $tableName     = $classMetaData->getTableName();
        } catch (\Exception $exception) {
            foreach ($this->modelManager->getMetadataFactory()->getAllMetadata() as $classMetaData) {
                /**
                 * @var $classMetaData ClassMetadata
                 */
                if ($name !== $classMetaData->getTableName()) {
                    continue;
                }

                $tableName  = $classMetaData->getTableName();
                $entityName = $classMetaData->getName();
            }
        }

        return array($tableName, $entityName);
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

    /**
     *
     */
    protected function clearEntityCache()
    {
        $metaDataCache = $this->modelManager->getConfiguration()->getMetadataCacheImpl();

        if (method_exists($metaDataCache, 'deleteAll')) {
            $metaDataCache->deleteAll();
        }
    }
}
