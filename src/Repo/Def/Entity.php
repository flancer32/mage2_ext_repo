<?php
/**
 * Default implementation for entity repository to do universal operations with specific entity data (CRUD).
 *
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Flancer32\Lib\Repo\Def;

use Flancer32\Lib\DataObject;

class Entity
    extends \Flancer32\Lib\Repo\Def\Crud
    implements \Flancer32\Lib\Repo\IEntity
{
    /** @var  string Class name for the related entity. */
    protected $_entityClassName;
    /** @var  string Entity name (table name w/o prefix) */
    protected $_entityName;
    /** @var  string Name of the first attribute from primary key */
    protected $_idFieldName;
    /** @var  \Flancer32\Lib\Repo\Data\IEntity entity instance */
    protected $_refEntity;
    /** @var \Flancer32\Lib\Repo\IGeneric */
    protected $_repoGeneric;

    /**
     * Entity constructor.
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Flancer32\Lib\Repo\IGeneric $repoGeneric
     * @param string $entityClassName
     */
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        \Flancer32\Lib\Repo\IGeneric $repoGeneric,
        $entityClassName
    ) {
        parent::__construct($resource);
        $this->_repoGeneric = $repoGeneric;
        $this->_entityClassName = $entityClassName;
        /* init entity that is related for the repo */
        $this->_initRefEntity();
    }

    /**
     * @param array $data
     * @return DataObject
     */
    protected function _createEntityInstance($data = null)
    {
        /** @var DataObject $result */
        $result = new $this->_entityClassName();
        if ($data) {
            $result->setData($data);
        }
        return $result;
    }

    protected function _initRefEntity()
    {
        if (is_null($this->_refEntity)) {
            $this->_refEntity = new $this->_entityClassName();
            $this->_entityName = $this->_refEntity->getEntityName();
            $ids = $this->_refEntity->getPrimaryKeyAttrs();
            /* get first field (and alone for one-field primary keys)*/
            $this->_idFieldName = reset($ids);
        }
    }

    /** @inheritdoc */
    public function create($data)
    {
        $result = $this->_repoGeneric->addEntity($this->_entityName, $data);
        return $result;
    }

    /** @inheritdoc */
    public function delete($where)
    {
        $result = $this->_repoGeneric->delete($this->_entityName, $where);
        return $result;
    }

    /** @inheritdoc */
    public function deleteById($id)
    {
        if (is_array($id)) {
            /* probably this is complex PK */
            $pk = $id;
        } else {
            $pk = [$this->_idFieldName => $id];
        }
        $result = $this->_repoGeneric->deleteEntityById($this->_entityName, $pk);
        return $result;
    }

    /** @inheritdoc */
    public function get(
        $where = null,
        $order = null,
        $limit = null,
        $offset = null,
        $columns = null,
        $group = null,
        $having = null
    ) {
        $result = $this->_repoGeneric->getEntities($this->_entityName, null, $where, $order, $limit, $offset);
        return $result;
    }

    /** @inheritdoc */
    public function getById($id)
    {
        if (is_array($id)) {
            /* probably this is complex PK */
            $pk = $id;
        } else {
            $pk = [$this->_idFieldName => $id];
        }
        $result = $this->_repoGeneric->getEntityById($this->_entityName, $pk);
        if ($result) {
            $result = $this->_createEntityInstance($result);
        }
        return $result;
    }

    /** @inheritdoc */
    public function getQueryToSelect()
    {
        $result = $this->_conn->select();
        $tbl = $this->_resource->getTableName($this->_entityName);
        $result->from($tbl);
        return $result;
    }

    /** @inheritdoc */
    public function getQueryToSelectCount()
    {
        $result = $this->_conn->select();
        $tbl = $this->_resource->getTableName($this->_entityName);
        $result->from($tbl, "COUNT({$this->_idFieldName})");
        return $result;
    }

    public function replace($data)
    {
        if ($data instanceof DataObject) {
            $data = $data->getData();
        }
        $result = $this->_repoGeneric->replaceEntity($this->_entityName, $data);
        return $result;
    }

    /** @inheritdoc */
    public function update($data, $where)
    {
        $result = $this->_repoGeneric->updateEntity($this->_entityName, $data, $where);
        return $result;
    }

    /** @inheritdoc */
    public function updateById($id, $data)
    {
        if (is_array($id)) {
            /* probably this is complex PK */
            $where = '';
            foreach ($id as $key => $value) {
                $val = is_int($value) ? $value : $this->_conn->quote($value);
                $where .= "($key=$val) AND ";
            }
            $where .= '1'; // WHERE ... AND 1;
        } else {
            $val = is_int($id) ? $id : $this->_conn->quote($id);
            $where = $this->_idFieldName . '=' . $val;
        }
        $result = $this->_repoGeneric->updateEntity($this->_entityName, $data, $where);
        return $result;
    }
}