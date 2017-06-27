<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Repo\Repo\Query;

/**
 * Interface for selection query builders. Queries can be based on other selection queries.
 */
interface  IBuilder
{
    /**
     * Build selection query (optionally) based on other query.
     *
     * @param \Flancer32\Lib\Repo\Fw\Db\Select|null $source
     * @return \Flancer32\Lib\Repo\Fw\Db\Select
     */
    public function build(\Flancer32\Lib\Repo\Fw\Db\Select $source = null);

    /**
     * Get SELECT COUNT query.
     *
     * @param \Flancer32\Lib\Repo\Repo\Query\IBuilder $qbuild
     * @return \Magento\Framework\DB\Select
     *
     * @deprecated use 'build' method instead.
     */
    public function getCountQuery(\Flancer32\Lib\Repo\Repo\Query\IBuilder $qbuild = null);

    /**
     * Get SELECT query.
     *
     * @param \Flancer32\Lib\Repo\Repo\Query\IBuilder $qbuild
     * @return \Magento\Framework\DB\Select
     *
     * @deprecated use 'build' method instead.
     */
    public function getSelectQuery(\Flancer32\Lib\Repo\Repo\Query\IBuilder $qbuild = null);

}