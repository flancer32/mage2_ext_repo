<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Repo\Data\ClauseSet\Order;

/**
 * SQL clause:
 *  "ORDER BY $alias"
 *  "ORDER BY $alias DESC"
 *
 * @property string $alias alias for column or expression.
 * @property bool $desc 'true' to set ordering direction to DESCENDING.
 */
class Entry
    extends \Flancer32\Lib\Data
{

}