<?php
/**
 * DEM (Domain entities map) related types.
 *
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Repo\Helper\Dem;

class DemType
{

    /**
     * Attribute subtypes modifiers.
     */
    const ATTRSUB_SMALL_INT = 'smallint';

    /**
     * Attribute types.
     */
    const ATTR_BINARY = 'binary';
    const ATTR_BOOLEAN = 'boolean';
    const ATTR_DATETIME = 'datetime';
    const ATTR_INTEGER = 'integer';
    const ATTR_NUMERIC = 'numeric';
    const ATTR_OPTION = 'option';
    const ATTR_TEXT = 'text';

    /**
     * Default values for attributes.
     */
    const DEF_CURRENT = 'current';

    /**
     * Index types.
     */
    const INDEX_PRIMARY = 'primary';
    const INDEX_SIMPLE = 'simple';
    const INDEX_TEXT = 'text';
    const INDEX_UNIQUE = 'unique';

    /**
     * References action.
     */
    const REF_ACTION_CASCADE = 'cascade';
    const REF_ACTION_RESTRICT = 'restrict';
}