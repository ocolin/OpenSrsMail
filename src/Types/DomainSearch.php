<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

class DomainSearch extends Payload
{
    /**
     * @var DomainCriteria Narrows the results by restricting the
     * search to the specified fields and their values.
     */
    public DomainCriteria $criteria;

    /**
     * @var SearchRange Limits the range of domains to display.
     */
    public SearchRange $range;

    /**
     * @var SearchSort Determines the way in which to sort and display results.
     */
    public SearchSort $sort;
}