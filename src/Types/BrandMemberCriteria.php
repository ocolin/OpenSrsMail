<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

class BrandMemberCriteria
{
    /**
     * @var string The company in which the brand resides. If
     * not specified, the requester's company is used.
     */
    public string $company;

    /**
     * @var string The brand name you are searching for members within
     */
    public string $brand;

    /**
     * @var string[] An array, with two possible values. Include
     * "domain" to have domains included in results, include "user"
     * to have users included in results
     */
    public array $type;
}