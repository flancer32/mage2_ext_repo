<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Flancer32\Lib\Repo\Repo;

use Flancer32\Lib\DataObject;

/**
 * Base interface for CRUD repositories (general Create-Read-Update-Delete operations).
 */
interface ICrud
    extends \Flancer32\Lib\Repo\Repo\IDataSource
{
    /**
     * Create new data instance (simple entity or aggregate) using $data. Exception is thrown in case of any error.
     *
     * @param DataObject|array $data
     * @return bool|int|string|array|DataObject ID (integer|string|array) or 'true|false' (if insertion is failed)
     * or array|DataObject (if newly created object is returned).
     */
    public function create($data);

    /**
     * @param $where
     * @return int The number of affected rows.
     */
    public function delete($where);

    /**
     * @param int|string|array $id
     * @return int The number of affected rows.
     */
    public function deleteById($id);

    /**
     * Replace data for the entity.
     *
     * @param array $data [COL_NAME=>$value, ...]
     * @return int Count of the updated rows.
     */
    public function replace($data);

    /**
     * @param array $data [COL_NAME=>$value, ...]
     * @param mixed $where
     * @return int Count of the updated rows.
     */
    public function update($data, $where);

    /**
     * Update instance in the DB (look up by ID values).
     *
     * @param array|DataObject $data
     * @param int|string|array $id
     * @return int The number of affected rows.
     */
    public function updateById($data, $id);
}