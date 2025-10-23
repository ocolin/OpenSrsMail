<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

class SearchSort
{
    /**
     * @var string Specify the attribute to use to sort results. Allowed values are:
     *
     * createtime — The date when the account was create, in UNIX Epoch time.
     * delete_time — The time the user account was deleted. Can be used only if criteria = deleted.
     * id — The identification number of the soft deleted account.
     * lastlogin — The last time (displayed in UNIX Epoch time) that the user successfully
     * logged in via pop3, imap4, webmail, or smtprelay.
     * status — The status of the account.
     * target — The alias target or forward target of the user.
     * type — The type of user: alias, filter, forward, or mailbox.
     * user — The user name (this is the default).
     * workgroup — The user's workgroup.
     */
    public string $by;

    /**
     * @var string Specify the sort order. Allowed values are ascending (default) or descending.
     */
    public string $direction;
}