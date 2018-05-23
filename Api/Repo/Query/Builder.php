<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Flancer32\Lib\Repo\Api\Repo\Query;

/**
 * Interface for DB query builders. Queries can be based on other queries.
 */
interface  Builder
{
    /**
     * Build query optionally based on other query. Builder modifies $source query.
     *
     * @param \Flancer32\Lib\Repo\Api\App\Repo\Select $source
     * @return \Flancer32\Lib\Repo\Api\App\Repo\Select
     */
    public function build($source = null);

}