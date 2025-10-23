<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

class SearchAdmins extends Payload
{
    /**
     * @var SearchAdminCriteria Narrows the results by restricting
     * the search to the specified fields and their values.
     */
    public SearchAdminCriteria $criteria;

    /**
     * @var SearchRange Limits the range of admins to display.
     * Allowed values are:
     *
     * first — The 0-based index of the first admin to return;
     * the default is the first result.
     * limit — The maximum number of results to return.
     */
    public SearchRange $range;
}