<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

class BrandCriteria
{
    /**
     * @var string The company in which to search for brands. If
     * not specified, the requester's company is used.
     */
    public string $company;

    /**
     * @var bool Specify whether the brand has been deleted or not.
     * Allowed values are true and false.
     */
    public bool $deleted;

    /**
     * @var string Specify a wildcard pattern to search for brand
     * names. The ? wildcard matches a single character and the
     * *wildcard matches a string of characters
     */
    public string $match;
}