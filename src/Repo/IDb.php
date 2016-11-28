<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Flancer32\Lib\Repo\Repo;

/**
 * Base interface for MySQL DB repository.
 */
interface IDb
{
    /**
     * Retrieve resource connection specified by $name
     *
     * @param string $name
     * @return \Magento\Framework\DB\Adapter\AdapterInterface
     */
    public function getConnection($name = null);

    /**
     * Retrieve resource (database).
     *
     * @return \Magento\Framework\App\ResourceConnection
     */
    public function getResource();

}