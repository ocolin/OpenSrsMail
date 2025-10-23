<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

class SearchUser extends Payload
{
    /**
     * @var SearchCriteria Narrows the results by restricting the search to the
     * specified fields and their values.
     */
    public SearchCriteria $criteria;

    /**
     * @var SearchRange Limits the range of user accounts to display.
     */
    public SearchRange $range;

    /**
     * @var SearchSort Determines the way in which to sort and display results.
     */
    public SearchSort $sort;

    /**
     * @var string[] Additional fields to return. Allowed values are: createtime,
     * forward, lastlogin, status, type, and workgroup.
     *
     * If not specified, defaults to workgroup and status.
     */
    public array $fields;
}