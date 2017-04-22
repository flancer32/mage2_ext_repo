<?php

/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Repo\Helper\Data\Adapter\ClauseSet;

class ApiSearchCriteria
{
    /**
     * @param \Magento\Framework\Api\Search\SearchCriteriaInterface $in
     * @return \Flancer32\Lib\Repo\Data\ClauseSet
     */
    public function getClauseSet(\Magento\Framework\Api\Search\SearchCriteriaInterface $in)
    {
        $result = new \Flancer32\Lib\Repo\Data\ClauseSet();
        $order = $this->getOrderFromApiCriteria($in);
        $result->order = $order;
        return $result;
    }

    protected function getOrderFromApiCriteria(\Magento\Framework\Api\Search\SearchCriteriaInterface $criteria)
    {
        $result = new \Flancer32\Lib\Repo\Data\ClauseSet\Order();
        $entries = [];
        $orders = $criteria->getSortOrders();
        foreach ($orders as $item) {
            $field = $item->getField();
            $direction = $item->getDirection();
            if ($field) {
                $entry = new \Flancer32\Lib\Repo\Data\ClauseSet\Order\Entry();
                $entry->alias = $field;
                if ($direction == 'DESC') $entry->desc = true;
                $entries[] = $entry;
            }
        }
        $result->entries = $entries;
        return $result;
    }

    /**
     * @param \Flancer32\Lib\Repo\Data\ClauseSet $in
     * @return \Magento\Framework\Api\Search\SearchCriteriaInterface
     */
    public function getSearchCriteria(\Flancer32\Lib\Repo\Data\ClauseSet $in)
    {
        throw new \Exception("Is not implemented yet.");
    }
}