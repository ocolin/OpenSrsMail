<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

/**
 * Limits the range of user accounts to display.
 */
class SearchRange
{
    /**
     * @var int Specify the first user to return; the default is the first result.
     */
    public int $first;

    /**
     * @var int Specify the maximum number of users to return.
     */
    public int $limit;
}