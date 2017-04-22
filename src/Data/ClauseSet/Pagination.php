<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Repo\Data\ClauseSet;

/**
 * Structure to restrict data set by size:
 *  SQL: "LIMIT $limit OFFSET $offset"
 *
 * Properties $pages & $pageSize will be converted to $limit & $offset in case
 * no $limit & $offset available.
 *
 * @property int $limit
 * @property int $offset
 * @property int $pages
 * @property int $pageSize
 */
class Pagination
    extends \Flancer32\Lib\Data
{

}