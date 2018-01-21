<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Repo\Helper\Dem;

use Magento\Framework\DB\Adapter\AdapterInterface as Dba;
use Magento\Framework\DB\Ddl\Table as Ddl;

class MageType
{
    /**
     * Column types in Magento: \Magento\Framework\DB\Ddl\Table::TYPE_...
     */
    const COL_BLOB = Ddl::TYPE_BLOB;
    const COL_BOOLEAN = Ddl::TYPE_BOOLEAN;
    const COL_DECIMAL = Ddl::TYPE_DECIMAL;
    const COL_INTEGER = Ddl::TYPE_INTEGER;
    const COL_SMALLINT = Ddl::TYPE_SMALLINT;
    const COL_TEXT = Ddl::TYPE_TEXT;
    const COL_TIMESTAMP = Ddl::TYPE_TIMESTAMP;

    /**
     * Default values for attributes: \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
     */

    const DEF_CURRENT_TIMESTAMP = Ddl::TIMESTAMP_INIT;

    /**
     * Index types in Magento: \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_...
     */

    const INDEX_FULLTEXT = Dba::INDEX_TYPE_FULLTEXT;
    const INDEX_INDEX = Dba::INDEX_TYPE_INDEX;
    const INDEX_PRIMARY = Dba::INDEX_TYPE_PRIMARY;
    const INDEX_UNIQUE = Dba::INDEX_TYPE_UNIQUE;

    /**
     * Other options.
     */

    const OPT_AUTO_INC = 'auto_increment';
    const OPT_DEFAULT = 'default';
    const OPT_NULLABLE = 'nullable';
    const OPT_PRECISION = 'precision';
    const OPT_SCALE = 'scale';
    const OPT_TYPE = 'type';
    const OPT_UNSIGNED = 'unsigned';

    /**
     * Foreign key action in the references:
     *  \Magento\Framework\DB\Adapter\AdapterInterface::FK_ACTION_...
     *  \Magento\Framework\DB\Ddl\Table::ACTION_...
     */

    const REF_ACTION_CASCADE = Dba::FK_ACTION_CASCADE;
    const REF_ACTION_NO_ACTION = Dba::FK_ACTION_NO_ACTION;
    const REF_ACTION_RESTRICT = Dba::FK_ACTION_RESTRICT;
}