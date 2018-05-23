<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Repo\Api\Helper;


interface Dem
{
    /**
     * Create entity structures in DB (table, indexes, foreign keys) according to DEM definition.
     *
     * @param $entityAlias string Alias of the entity ('prxgt_acc_type_asset').
     * @param $demEntity array Associative array with entity definition (DEM subtree).
     * @throws \Zend_Db_Exception
     */
    public function createEntity($entityAlias, $demEntity);


    /**
     * Read JSON file with DEM, extract and return DEM node as an associative array.
     *
     * @param string $pathToDemFile absolute path to the DEM definition in JSON format.
     * @param string $pathToDemNode as "/dBEAR/package/Vendor/package/Module"
     * @return \Flancer32\Lib\Data
     * @throws \Exception
     */
    public function readDemPackage($pathToDemFile, $pathToDemNode);
}