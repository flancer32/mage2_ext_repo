<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Repo\Data\ClauseSet\Filter;


/**
 * Group of filters. Group contains $enries (simple conditions and/or other groups)
 * and operation to unite entries ('OR', 'AND', ...).
 *
 * @property \Flancer32\Lib\Data[] $entries
 * @property string $with operation to apply to group entries (see self::OP_).
 */
class Group
    extends \Flancer32\Lib\Data
{
    /**#@+ Operations to apply to group entries. */
    const OP_AND = 'AND';
    const OP_NOT = 'NOT';
    const OP_OR = 'OR';
    /**#@-  */
}