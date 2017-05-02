<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Repo\Setup\Dem;

/**
 * Parse DEM structures and return data to process in \Vendor\Module\Setup\InstallSchema classes.
 */
class Parser
{
    /**
     * Parse DEM attribute and indexes data and return options for Magento DDL field.
     *
     * @param $demAttr
     * @param $demIndexes
     *
     * @return array
     */
    public function entityGetAttrOptions($demAttr, $demIndexes)
    {
        $result = [];
        $alias = $demAttr[Cfg::ALIAS];
        /* parse common options */
        if (isset($demAttr[Cfg::NULLABLE])) {
            $result[MageType::OPT_NULLABLE] = $demAttr[Cfg::NULLABLE];
        }
        if (isset($demAttr[Cfg::DEFAULT_])) {
            $result[MageType::OPT_DEFAULT] = $demAttr[Cfg::DEFAULT_];
        }
        /* parse indexes to define PRIMARY option */
        foreach ($demIndexes as $ndx) {
            $ndxType = $ndx[Cfg::TYPE];
            if ($ndxType == DemType::INDEX_PRIMARY) {
                foreach ($ndx[Cfg::ALIASES] as $one) {
                    if ($alias == $one) {
                        $result[MageType::INDEX_PRIMARY] = true;
                        break;
                    }
                }
                break;
            }
        }
        /* parse type specific options */
        $type = key($demAttr[Cfg::TYPE]);
        switch ($type) {
            case DemType::ATTR_BINARY:
                break;
            case DemType::ATTR_BOOLEAN:
                break;
            case DemType::ATTR_DATETIME:
                if (isset($demAttr[Cfg::DEFAULT_]) && ($demAttr[Cfg::DEFAULT_] == DemType::DEF_CURRENT)) {
                    $result[MageType::OPT_DEFAULT] = MageType::DEF_CURRENT_TIMESTAMP;
                }
                break;
            case DemType::ATTR_INTEGER:
                if (isset($demAttr[Cfg::TYPE][DemType::ATTR_INTEGER][Cfg::UNSIGNED])) {
                    $result[MageType::OPT_UNSIGNED] = $demAttr[Cfg::TYPE][DemType::ATTR_INTEGER][Cfg::UNSIGNED];
                }
                if (isset($demAttr[Cfg::TYPE][DemType::ATTR_INTEGER][Cfg::AUTOINCREMENT])) {
                    $result[MageType::OPT_AUTO_INC] = $demAttr[Cfg::TYPE][DemType::ATTR_INTEGER][Cfg::AUTOINCREMENT];
                }
                break;
            case DemType::ATTR_NUMERIC:
                break;
            case DemType::ATTR_OPTION:
                break;
            case DemType::ATTR_TEXT:
                break;
        }
        return $result;
    }

    /**
     * Parse DEM attribute data and return size for Magento DDL field.
     *
     * @param $demAttrType
     *
     * @return null
     */
    public function entityGetAttrSize($demAttrType)
    {
        $result = null;
        $type = key($demAttrType);
        $typeData = reset($demAttrType);
        switch ($type) {
            case DemType::ATTR_BINARY:
                break;
            case DemType::ATTR_BOOLEAN:
                break;
            case DemType::ATTR_DATETIME:
                break;
            case DemType::ATTR_INTEGER:
                $result = isset($typeData[Cfg::LENGTH]) ? $typeData[Cfg::LENGTH] : null;
                break;
            case DemType::ATTR_NUMERIC:
                if (isset($typeData[Cfg::PRECISION])) {
                    /* we should have 2 elements in the result array*/
                    $result = [MageType::OPT_PRECISION => 10, MageType::OPT_SCALE => 0];
                    $result[MageType::OPT_PRECISION] = $typeData[Cfg::PRECISION];
                    if (isset($typeData[Cfg::SCALE])) {
                        $result[MageType::OPT_SCALE] = $typeData[Cfg::SCALE];
                    }
                }
                break;
            case DemType::ATTR_OPTION:
                break;
            case DemType::ATTR_TEXT:
                $result = isset($typeData[Cfg::LENGTH]) ? $typeData[Cfg::LENGTH] : null;
                break;
        }
        return $result;
    }

    /**
     * Parse DEM attribute data and return type for Magento DDL field.
     *
     * @param $demAttrType
     *
     * @return string
     */
    public function entityGetAttrType($demAttrType)
    {
        $result = MageType::COL_TEXT;
        $type = key($demAttrType);
        $typeData = reset($demAttrType);
        switch ($type) {
            case DemType::ATTR_BINARY:
                $result = MageType::COL_BLOB;
                break;
            case DemType::ATTR_BOOLEAN:
                $result = MageType::COL_BOOLEAN;
                break;
            case DemType::ATTR_DATETIME:
                $result = MageType::COL_TIMESTAMP;
                break;
            case DemType::ATTR_INTEGER:
                $result = MageType::COL_INTEGER;
                if (isset($typeData[Cfg::SUBTYPE])) {
                    if ($typeData[Cfg::SUBTYPE] == DemType::ATTRSUB_SMALL_INT) {
                        $result = MageType::COL_SMALLINT;
                    }
                }
                break;
            case DemType::ATTR_NUMERIC:
                $result = MageType::COL_DECIMAL;
                break;
            case DemType::ATTR_OPTION:
                $result = MageType::COL_TEXT;
                break;
            case DemType::ATTR_TEXT:
                $result = MageType::COL_TEXT;
                break;
        }
        return $result;
    }

    public function entityGetIndexFields($demIndex)
    {
        $result = $demIndex[Cfg::ALIASES];
        return $result;
    }

    public function entityGetIndexOptions($demIndex)
    {
        $result = [];
        if (
            isset($demIndex[Cfg::TYPE]) &&
            ($demIndex[Cfg::TYPE] == DemType::INDEX_UNIQUE)
        ) {
            $result[MageType::OPT_TYPE] = MageType::INDEX_UNIQUE;
        }
        return $result;
    }

    public function entityGetIndexType($demIndex)
    {
        $result = MageType::INDEX_INDEX;
        if (isset($demIndex[Cfg::TYPE])) {
            $type = $demIndex[Cfg::TYPE];
            switch ($type) {
                case DemType::INDEX_PRIMARY:
                    $result = MageType::INDEX_PRIMARY;
                    break;
                case DemType::INDEX_UNIQUE:
                    $result = MageType::INDEX_UNIQUE;
                    break;
                case DemType::INDEX_TEXT:
                    $result = MageType::INDEX_FULLTEXT;
                    break;
            }
        }
        return $result;
    }

    /**
     * Map DEM foreign keys actions to Varien values.
     *
     * @param $demAction
     *
     * @return string
     */
    public function referenceGetAction($demAction)
    {
        $result = MageType::REF_ACTION_NO_ACTION;
        if ($demAction == DemType::REF_ACTION_RESTRICT) {
            $result = MageType::REF_ACTION_RESTRICT;
        } else {
            if ($demAction == DemType::REF_ACTION_CASCADE) {
                $result = MageType::REF_ACTION_CASCADE;
            }
        }
        return $result;
    }
}