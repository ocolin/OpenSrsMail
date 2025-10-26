<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

class BrandMember extends Payload
{
    /**
     * @var BrandMemberCriteria Narrows the search for brands.
     */
    public BrandMemberCriteria $criteria;

    /**
     * @var SearchRange Restricts the number of results that are returned.
     */
    public SearchRange $range;
}