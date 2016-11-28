<?php
/**
 * Interface for the persistence entities (matched to tables & views in db).
 *
 * User: Alex Gusev <alex@flancer64.com>
 */
namespace Flancer32\Lib\Repo\Data;


interface IEntity
{
    /**
     * Get name of the entity (table name w/o common prefix).
     *
     * @return string
     */
    public function getEntityName();

    /**
     * Get array with names of the primary key attributes.
     *
     * @return array
     */
    public function getPrimaryKeyAttrs();
}