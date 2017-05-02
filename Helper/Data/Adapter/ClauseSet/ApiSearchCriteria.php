<?php

/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Repo\Helper\Data\Adapter\ClauseSet;

use \Flancer32\Lib\Repo\Helper\Repo\Query\ClauseSet\Processor\FilterParser as RepoFilter;

class ApiSearchCriteria
{
    const FN_EQ = 'eq';
    const FN_GT = 'gt';
    const FN_GTE = 'gteq';
    const FN_LIKE = 'like';
    const FN_LT = 'lt';
    const FN_LTE = 'lteq';
    const FN_NEQ = 'neq';

    /**
     * @param \Magento\Framework\Api\Search\SearchCriteriaInterface $in
     * @return \Flancer32\Lib\Repo\Data\ClauseSet
     */
    public function getClauseSet(\Magento\Framework\Api\Search\SearchCriteriaInterface $in)
    {
        $result = new \Flancer32\Lib\Repo\Data\ClauseSet();
        $filter = $this->getFilterFromApiCriteria($in);
        $order = $this->getOrderFromApiCriteria($in);
        $pagination = $this->getPaginationFromApiCriteria($in);
        $result->filter = $filter;
        $result->order = $order;
        $result->pagination = $pagination;
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

    protected function getPaginationFromApiCriteria(\Magento\Framework\Api\Search\SearchCriteriaInterface $criteria)
    {
        $result = new \Flancer32\Lib\Repo\Data\ClauseSet\Pagination();

        $pageSize = $criteria->getPageSize();
        $page = $criteria->getCurrentPage();

        $offset = ($page - 1) * $pageSize;
        $offset = ($offset < 0) ? 0 : $offset;

        $result->limit = $pageSize;
        $result->offset = $offset;
        return $result;
    }

    /**
     * Convert Magento API filter to Flancer32 Repo filter.
     *
     * Magento API filter has 2 levels structure:
     *  - group of filters united by AND;
     *  - each filter in group is a group of conditions united by AND;
     *
     * @param \Magento\Framework\Api\Search\SearchCriteriaInterface $criteria
     * @return \Flancer32\Lib\Repo\Data\ClauseSet\Filter
     */
    protected function getFilterFromApiCriteria(\Magento\Framework\Api\Search\SearchCriteriaInterface $criteria)
    {
        $result = new \Flancer32\Lib\Repo\Data\ClauseSet\Filter();
        $entriesRepoTop = [];
        $groupsApi = $criteria->getFilterGroups();
        foreach ($groupsApi as $groupApi) {
            $entriesRepo = [];
            /* prevent duplications (https://github.com/magento/magento2/issues/4308) */
            $processed = [];
            /** @var \Magento\Framework\Api\Filter $item */
            foreach ($groupApi->getFilters() as $item) {
                $field = $item->getField();
                $condType = $item->getConditionType();
                $value = $item->getValue();
                $hash = "$field|$condType|$value";
                if (!isset($processed[$hash])) {
                    $condition = new \Flancer32\Lib\Repo\Data\ClauseSet\Filter\Condition();
                    $condition->alias = $field;
                    $func = $this->mapApiCondToRepoFunc($condType);
                    $condition->func = $func;
                    $condition->value = $value;
                    $entriesRepo[] = $condition;
                    $processed[$hash] = true;
                }
            }
            /* compose nested filters group */
            $groupRepo = new \Flancer32\Lib\Repo\Data\ClauseSet\Filter\Group();
            $groupRepo->entries = $entriesRepo;
            $groupRepo->with = \Flancer32\Lib\Repo\Data\ClauseSet\Filter\Group::OP_AND;
            /* and place it into the top level filter group */
            $entriesRepoTop[] = $groupRepo;
        }
        /* compose top level group for repo filter */
        $groupRepoTop = new \Flancer32\Lib\Repo\Data\ClauseSet\Filter\Group();
        $groupRepoTop->entries = $entriesRepoTop;
        $groupRepoTop->with = \Flancer32\Lib\Repo\Data\ClauseSet\Filter\Group::OP_AND;
        $result->group = $groupRepoTop;
        return $result;
    }

    protected function mapApiCondToRepoFunc($cond)
    {
        $result = '';
        switch (strtolower(trim($cond))) {
            case self::FN_EQ:
                $result = RepoFilter::FN_EQ;
                break;
            case self::FN_NEQ:
                $result = RepoFilter::FN_NEQ;
                break;
            case self::FN_GT:
                $result = RepoFilter::FN_GT;
                break;
            case self::FN_GTE:
                $result = RepoFilter::FN_GTE;
                break;
            case self::FN_LT:
                $result = RepoFilter::FN_LT;
                break;
            case self::FN_LTE:
                $result = RepoFilter::FN_LTE;
                break;
            case self::FN_LIKE:
                $result = RepoFilter::FN_LIKE;
                break;

        }
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