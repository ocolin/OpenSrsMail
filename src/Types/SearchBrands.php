<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

class SearchBrands extends Payload
{
    /**
     * @var BrandCriteria Narrows the search for brands.
     */
    public BrandCriteria $criteria;

    /**
     * @var SearchRange Restricts the number of results that are
     * returned.
     */
    public SearchRange $range;
}