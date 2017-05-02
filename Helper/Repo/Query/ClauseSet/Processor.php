<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Repo\Helper\Repo\Query\ClauseSet;

use Flancer32\Lib\Repo\Repo\Query\Expression;

/**
 * Apply set of clauses on query.
 */
class Processor
{
    /** @var  \Flancer32\Lib\Repo\Helper\Repo\Query\ClauseSet\Processor\FilterParser */
    protected $subFilterParser;

    public function __construct(
        \Flancer32\Lib\Repo\Helper\Repo\Query\ClauseSet\Processor\FilterParser $subFilterParser
    ) {
        $this->subFilterParser = $subFilterParser;
    }


    /**
     * @param \Magento\Framework\DB\Select $query
     * @param \Flancer32\Lib\Repo\Data\ClauseSet $clauses
     * @param bool $filterOnly 'true' - apply only filter clauses (for totals)
     */
    public function exec(
        \Magento\Framework\DB\Select $query,
        \Flancer32\Lib\Repo\Data\ClauseSet $clauses,
        $filterOnly = false
    ) {
        $aliases = $this->mapAliases($query);
        $filter = $clauses->filter;
        $order = $clauses->order;
        $pagination = $clauses->pagination;
        $this->processFilter($query, $filter, $aliases);
        if (!$filterOnly) {
            $this->processOrder($query, $order, $aliases);
            $this->processPagination($query, $pagination);
        }
    }

    /**
     * @param $query
     * @return array
     */
    protected function mapAliases(\Magento\Framework\DB\Select $query)
    {
        $result = [];
        $columns = $query->getPart(\Zend_Db_Select::COLUMNS);
        foreach ($columns as $one) {
            $table = $one[0];
            $expression = $one[1];
            $alias = $one[2];
            $data = new \Flancer32\Lib\Repo\Helper\Repo\Query\ClauseSet\Processor\AliasMapEntry();
            $data->alias = $alias;
            $data->expression = $expression;
            $data->table = $table;
            $result[$alias] = $data;
        }
        return $result;
    }

    protected function processFilter($query, $filter, $aliases)
    {
        $where = $this->subFilterParser->parse($filter, $aliases);
        if ($where) $query->where($where);
    }

    protected function processOrder($query, $order, $aliases)
    {
        if ($order) {
            $entries = $order->entries;
            if (is_array($entries)) {
                $sqlOrder = [];
                /** @var \Flancer32\Lib\Repo\Data\ClauseSet\Order\Entry $entry */
                foreach ($entries as $entry) {
                    $alias = $entry->alias;
                    $dir = ($entry->desc) ? \Zend_Db_Select::SQL_DESC : \Zend_Db_Select::SQL_ASC;
                    if (isset($aliases[$alias])) {
                        /** @var \Flancer32\Lib\Repo\Helper\Repo\Query\ClauseSet\Processor\AliasMapEntry $mapped */
                        $mapped = $aliases[$alias];
                        $table = $mapped->table;
                        $expression = $mapped->expression;
                        if ($expression instanceof \Flancer32\Lib\Repo\Repo\Query\Expression) {
                            $sqlEntry = "$expression $dir";
                        } else {
                            /* don't add quotes to names (`table`,`column`), Zend FW will do it. */
                            $sqlEntry = "$table.$expression $dir";
                        }
                        $sqlOrder[] = $sqlEntry;
                    }
                }
                $query->order($sqlOrder);
            }
        }
    }

    protected function processPagination($query, $pagination)
    {
        if ($pagination) {
            $offset = $pagination->offset;
            $limit = $pagination->limit;
            if ($limit && $offset) {
                $query->limit($limit, $offset);
            } elseif ($limit) {
                $query->limit($limit);
            }
        }
    }
}