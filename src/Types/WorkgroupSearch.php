<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

class WorkgroupSearch extends Payload
{
    /**
     * @var WorkgroupCriteria Narrows the results by restricting the search.
     */
    public WorkgroupCriteria $criteria;

    /**
     * @var SearchRange Limits the results to a subset of those selected by
     * the criteria values.
     */
    public SearchRange $range;

    /**
     * @var SearchSort Determines the way in which to sort and display results.
     */
    public SearchSort  $sort;
}