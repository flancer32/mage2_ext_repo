<?php
/**
 * Default implementation for generic repository to do universal operations with data (CRUD).
 *
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Repo\Repo\Def;

use Flancer32\Lib\Data;

class  Generic
    extends \Flancer32\Lib\Repo\Repo\Def\Db
    implements \Flancer32\Lib\Repo\Repo\IGeneric
{

    public function addEntity($entity, $bind)
    {
        $result = null;
        $tbl = $this->_resource->getTableName($entity);
        if ($bind instanceof Data) {
            $data = (array)$bind->get();
        } else {
            $data = $bind;
        }
        $rowsAdded = $this->_conn->insert($tbl, $data);
        if ($rowsAdded) {
            $result = $this->_conn->lastInsertId($tbl);
        }
        return $result;
    }

    public function deleteEntity($entity, $where)
    {
        $tbl = $this->_resource->getTableName($entity);
        $result = $this->_conn->delete($tbl, $where);
        return $result;
    }

    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function deleteEntityById($entity, $id)
    {
        $tbl = $this->_resource->getTableName($entity);
        $where = [];
        foreach ($id as $field => $value) {
            $where["`$field`=?"] = $value;
        }
        $result = $this->_conn->delete($tbl, $where);
        return $result;
    }

    public function getEntities($entity, $cols = null, $where = null, $order = null, $limit = null, $offset = null)
    {
        $tbl = $this->_resource->getTableName($entity);
        $query = $this->_conn->select();
        if (is_null($cols)) {
            $cols = '*';
        }
        $query->from($tbl, $cols);
        if ($where) {
            $query->where($where);
        }
        if ($order) {
            $query->order($order);
        }
        if ($limit) {
            $query->limit($limit, $offset);
        }
        $result = $this->_conn->fetchAll($query);
        return $result;
    }

    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function getEntityById($entity, $id, $cols = null)
    {
        $tbl = $this->_resource->getTableName($entity);
        /* columns to select */
        $cols = ($cols) ? $cols : '*';
        $query = $this->_conn->select();
        $query->from($tbl, $cols);
        foreach (array_keys($id) as $field) {
            $query->where("`$field`=:$field");
        }
        $result = $this->_conn->fetchRow($query, $id);
        return $result;
    }

    public function replaceEntity($entity, $bind)
    {
        $tbl = $this->_resource->getTableName($entity);
        $keys = array_keys($bind);
        $columns = implode(',', $keys);
        $values = ":" . implode(',:', $keys);
        $query = "REPLACE $tbl ($columns) VALUES ($values)";
        $this->_conn->query($query, $bind);
        $result = $this->_conn->lastInsertId($tbl);
        return $result;
    }

    public function updateEntity($entity, $bind, $where = null)
    {
        $tbl = $this->_resource->getTableName($entity);
        if ($bind instanceof Data) {
            $data = $bind->get();
        } else {
            $data = $bind;
        }
        $result = $this->_conn->update($tbl, $data, $where);
        return $result;
    }

    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function updateEntityById($entity, $bind, $id)
    {
        $tbl = $this->_resource->getTableName($entity);
        if ($bind instanceof Data) {
            $data = $bind->get();
        } else {
            $data = $bind;
        }
        $where = '1';
        foreach ($id as $field => $value) {
            $where .= " AND $field=" . $this->_conn->quote($value);
        }
        $result = $this->_conn->update($tbl, $data, $where);
        return $result;
    }
}