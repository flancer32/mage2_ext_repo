<?php
/**
 * Tools related to operations with DEM (Domain Entities Map).
 *
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Repo\Setup\Dem;

use Flancer32\Lib\Data as DataObject;
use Flancer32\Lib\Repo\Setup\Dem\Cfg as DemCfg;

class Tool
{
    /** Path separator between packages. */
    const PS = \Flancer32\Lib\Repo\Config::DEM_PS;
    /** @var \Psr\Log\LoggerInterface */
    protected $logger;
    /** @var \Flancer32\Lib\Repo\Setup\Dem\Parser */
    protected $parser;
    /** @var \Magento\Framework\App\ResourceConnection */
    protected $resource;

    /**
     * Tool constructor.
     */
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        \Psr\Log\LoggerInterface $logger,
        \Flancer32\Lib\Repo\Setup\Dem\Parser $parser
    ) {
        $this->resource = $resource;
        $this->_conn = $resource->getConnection();
        $this->logger = $logger;
        $this->parser = $parser;
    }

    /**
     *
     * @param $entityAlias string Alias of the entity ('prxgt_acc_type_asset').
     * @param $demEntity array Associative array with entity definition (DEM subtree).
     */
    public function createEntity($entityAlias, $demEntity)
    {
        $tblName = $this->resource->getTableName($entityAlias);
        $this->logger->info("Create new table: $tblName.");
        /* init new object to create table in DB */
        $tbl = $this->_conn->newTable($tblName);
        if (isset($demEntity[DemCfg::COMMENT])) {
            $tbl->setComment($demEntity[DemCfg::COMMENT]);
        }
        $indexes = isset($demEntity[DemCfg::INDEX]) ? $demEntity[DemCfg::INDEX] : null;
        $relations = isset($demEntity[DemCfg::RELATION]) ? $demEntity[DemCfg::RELATION] : null;
        /* parse attributes */
        foreach ($demEntity[DemCfg::ATTRIBUTE] as $key => $attr) {
            $attrName = $attr[DemCfg::ALIAS];
            $attrType = $this->parser->entityGetAttrType($attr[DemCfg::TYPE]);
            $attrSize = $this->parser->entityGetAttrSize($attr[DemCfg::TYPE]);
            $attrOpts = $this->parser->entityGetAttrOptions($attr, $indexes);
            $attrComment = isset($attr[DemCfg::COMMENT]) ? $attr[DemCfg::COMMENT] : null;
            $tbl->addColumn($attrName, $attrType, $attrSize, $attrOpts, $attrComment);
        }
        /* parse indexes */
        if ($indexes) {
            foreach ($indexes as $key => $ndx) {
                /* PRIMARY indexes are processed as columns options */
                if ($ndx[DemCfg::TYPE] == DemType::INDEX_PRIMARY) {
                    continue;
                }
                /* process not PRIMARY indexes */
                $ndxFields = $this->parser->entityGetIndexFields($ndx);
                $ndxType = $this->parser->entityGetIndexType($ndx);
                $ndxOpts = $this->parser->entityGetIndexOptions($ndx);
                $ndxName = $this->_conn->getIndexName($entityAlias, $ndxFields, $ndxType);
                $this->logger->info("Create new index: $ndxName.");
                $tbl->addIndex($ndxName, $ndxFields, $ndxOpts);
            }
        }
        /* create new table */
        $this->_conn->createTable($tbl);
        /* parse relations */
        if ($relations) {
            foreach ($relations as $one) {
                /* one only column FK is supported by Magento FW */
                $ownColumn = reset($one[DemCfg::OWN][DemCfg::ALIASES]);
                $refTableAlias = $one[DemCfg::REFERENCE][DemCfg::ENTITY][DemCfg::COMPLETE_ALIAS];
                $refTable = $this->resource->getTableName($refTableAlias);
                $refColumn = reset($one[DemCfg::REFERENCE][DemCfg::ALIASES]);
                $onDelete = $this->parser->referenceGetAction($one[DemCfg::ACTION][DemCfg::DELETE]);
                /* there is no onUpdate in M2, $purge is used instead. Set default value 'false' for purge. */
                //$onUpdate = $this->_parser->referenceGetAction($one[DemCfg::ACTION][DemCfg::UPDATE]);
                $onUpdate = false;
                $fkName = $this->_conn->getForeignKeyName($tblName, $ownColumn, $refTable, $refColumn);
                $this->logger->info("Create new relation '$fkName' from '$tblName:$ownColumn' to '$refTable:$refColumn'.");
                try {
                    $this->_conn->addForeignKey($fkName, $tblName, $ownColumn, $refTable, $refColumn, $onDelete,
                        $onUpdate);
                } catch (\Exception $e) {
                    $msg = "Cannot create FK '$fkName'. Error: " . $e->getMessage();
                    $this->logger->error($msg);
                }
            }
        }
    }

    /**
     * Read JSON file with DEM, extract and return DEM node as an associative array.
     *
     * @param string $pathToDemFile absolute path to the DEM definition in JSON format.
     * @param string $pathToDemNode as "/dBEAR/package/Vendor/package/Module"
     * @return DataObject
     * @throws \Exception
     */
    public function readDemPackage($pathToDemFile, $pathToDemNode)
    {
        $json = file_get_contents($pathToDemFile);
        $data = json_decode($json, true);
        $paths = explode(self::PS, $pathToDemNode);
        foreach ($paths as $path) {
            if (strlen(trim($path)) > 0) {
                if (isset($data[$path])) {
                    $data = $data[$path];
                } else {
                    throw new \Exception("Cannot find DEM node '$pathToDemNode' in file '$pathToDemFile'.");
                }
            }
        }
        $result = new DataObject($data);
        return $result;
    }
}