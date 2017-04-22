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
    /**
     * @param \Magento\Framework\DB\Select $query
     * @param \Flancer32\Lib\Repo\Data\ClauseSet $clauses
     */
    public function exec(
        \Magento\Framework\DB\Select $query,
        \Flancer32\Lib\Repo\Data\ClauseSet $clauses
    ) {
        $order = $clauses->order;
        $aliases = $this->mapAliases($query);
        if ($order) {
            $entries = $order->entries;
            if (is_array($entries)) {
                $sqlOrder = [];
                /** @var \Flancer32\Lib\Repo\Data\ClauseSet\Order\Entry $entry */
                foreach ($entries as $entry) {
                    $alias = $entry->alias;
                    $dir = ($entry->desc) ? 'DESC' : 'ASC';
                    if (isset($aliases[$alias])) {
                        /** @var \Flancer32\Lib\Repo\Helper\Repo\Query\ClauseSet\Processor\AliasMapEntry $mapped */
                        $mapped = $aliases[$alias];
                        $table = $mapped->table;
                        $expression = $mapped->expression;
                        if ($expression instanceof \Flancer32\Lib\Repo\Repo\Query\Expression) {
                            $sqlEntry = "$expression $dir";
                        } else {
                            /* don't add quotes to names (`table`,`column`), Zend will do it. */
                            $sqlEntry = "$table.$expression $dir";
                        }
                        $sqlOrder[] = $sqlEntry;
                    }
                }
                $query->order($sqlOrder);
            }
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
}