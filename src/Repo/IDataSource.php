<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Flancer32\Lib\Repo;

use Flancer32\Lib\DataObject;

/**
 * Base interface for data sources repository (read-only mode).
 */
interface IDataSource
{

    /**
     * Generic method to get data from repository.
     *
     * @param null $where
     * @param null $order
     * @param null $limit
     * @param null $offset
     * @param null $columns
     * @param null $group
     * @param null $having
     * @return array Found data or empty array if no data found.
     */
    public function get(
        $where = null,
        $order = null,
        $limit = null,
        $offset = null,
        $columns = null,
        $group = null,
        $having = null
    );

    /**
     * Get the data instance by ID (ID can be an array for complex primary keys).
     *
     * @param int|string|array $id
     * @return DataObject|array|bool Found instance data or 'false'
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function getById($id);

    /**
     * Compose SELECT query for the simple entity or aggregate.
     *
     * @return \Magento\Framework\DB\Select
     */
    public function getQueryToSelect();

    /**
     * Compose COUNT SELECT query for the simple entity or aggregate.
     *
     * @return \Magento\Framework\DB\Select
     */
    public function getQueryToSelectCount();

}