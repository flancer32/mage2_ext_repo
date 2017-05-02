<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Repo\Data\ClauseSet\Filter;

/**
 * Standalone condition for filtering set.
 *
 * @property string $alias alias for table's column or expression.
 * @property string $func equation or function.
 * @property string|string[] $value function's arguments ("WHERE amount>0 OR id IN (1, 20, 300)").
 */
class Condition
    extends \Flancer32\Lib\Data
{

}