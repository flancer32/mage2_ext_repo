<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Repo\Repo\Query;

/**
 * Interface for selection query builders. Queries can be based on other queries.
 */
interface  IBuilder
{
    /**
     * Get SELECT COUNT query.
     *
     * @param \Flancer32\Lib\Repo\Repo\Query\IBuilder $qbuild
     * @return \Magento\Framework\DB\Select
     */
    public function getCountQuery(\Flancer32\Lib\Repo\Repo\Query\IBuilder $qbuild = null);

    /**
     * Get SELECT query.
     *
     * @param \Flancer32\Lib\Repo\Repo\Query\IBuilder $qbuild
     * @return \Magento\Framework\DB\Select
     */
    public function getSelectQuery(\Flancer32\Lib\Repo\Repo\Query\IBuilder $qbuild = null);

}