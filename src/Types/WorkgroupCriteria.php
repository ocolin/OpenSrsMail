<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

class WorkgroupCriteria
{
    /**
     * @var string Specifies the domain to search. This is required.
     */
    public string $domain;

    /**
     * @var string Returns only those workgroups that match the
     * specified pattern. You can use the following wildcards:
     *
     * ? ― Match a single character
     * * ― Match multiple characters.
     */
    public string $match;
}