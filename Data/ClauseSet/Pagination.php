<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Repo\Data\ClauseSet;

/**
 * Structure to restrict data set by size:
 *  SQL: "LIMIT $limit OFFSET $offset"
 *
 * @property int $limit
 * @property int $offset
 */
class Pagination
    extends \Flancer32\Lib\Data
{

}