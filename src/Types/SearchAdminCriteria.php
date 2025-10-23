<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

/**
 *  Narrows the results by restricting the search to the specified
 * fields and their values.
 *
 * Note**: The criteria values work together, so if both a type and
 * match are specified, the response includes only admins of the
 * specified type that also match the pattern.
 */
class SearchAdminCriteria
{
    /**
     * @var string The company to search for admins. If not specified,
     * the requester's company is used.
     */
    public string $company;

    /**
     * @var string Returns only those admins whose user names matches
     * the specified pattern. You can use the following wildcards:
     *
     * ? ― Match a single character
     * * ― Match a string of characters.
     */
    public string $match;

    /**
     * @var string[] Returns only admins of the specified type. Allowed
     * values are:company, domain, mail, and workgroup. If not specified,
     * admins of all types are returned.
     */
    public array $type;

}