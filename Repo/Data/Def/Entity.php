<?php
/**
 * Base class for persistence entities (match to tables/views in DB).
 *
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Flancer32\Lib\Repo\Repo\Data\Def;

abstract class Entity
    extends \Flancer32\Lib\Data
    implements \Flancer32\Lib\Repo\Repo\Data\IEntity
{

    public function getEntityName()
    {
        return static::ENTITY_NAME; // "static::" will use child attribute value
    }
}