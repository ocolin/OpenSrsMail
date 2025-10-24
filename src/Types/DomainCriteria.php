<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

class DomainCriteria
{
    /**
     * @var string The company to search for domains. If not
     * specified, the requestor's company is used.
     */
    public string $company;

    /**
     * @var bool If set to true, only deleted domains are
     * returned; if set to false or not specified, only existing
     * domains are returned.
     */
    public bool $deleted;

    /**
     * @var string Returns only those domains that match the specified
     * pattern. You can use the following wildcards:
     *
     * ? ― Match a single character
     * * ― Match a string of characters.
     */
    public string $match;

    /**
     * @var string Returns only domains of the specified
     * type. Allowed values are:
     *
     * domain — Regular domains
     * alias — Alias domains
     */
    public string $type;
}