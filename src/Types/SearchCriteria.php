<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

/**
 * Narrows the results by restricting the search to the specified fields and their values.
 * Note**: You must specify the domain; all other criteria are optional.
 *
 * The criteria fields work together to restrict the results, for example, if both
 * workgroup and match are specified, the method returns only those users that are in
 * that workgroup and match the pattern.
 */

class SearchCriteria
{
    /**
     * @var bool Specifies whether to return only deleted user accounts.
     * Allowed values are true and false.
     */
    public bool $deleted;

    /**
     * @var string Specifies the domain to search. This is required.
     */
    public string $domain;

    /**
     * @var string Returns only those user accounts that match the
     * specified pattern. You can use the following wildcards:
     *
     * ? ― Match a single character
     * * ― Match multiple characters.
     */
    public string $match;

    /**
     * @var string[] Returns only those user account with the specified
     * status. If not specified, returns all statuses except deleted.
     * Allowed values are:
     *
     * active — Mailbox is currently active.
     * deleted — Mailbox has been deleted.
     * quota — Mailbox is over quota and cannot receive any more mail.
     * smtplimit — Mailbox is at the smtp limit and user cannot send any more mail.
     * suspended — Mailbox has been suspended and user cannot send or receive mail.
     */
    public array $status;

    /**
     * @var string Returns only user accounts of the specified type. Allowed values are:
     *
     * alias — Alias mailboxes
     * filter — Filter-only mailboxes
     * forward — Forward-only mailboxes
     * mailbox — Regular mailboxes
     */
    public string $type;

    /**
     * @var string Returns only user accounts in the specified workgroup.
     */
    public string $workgroup;
}