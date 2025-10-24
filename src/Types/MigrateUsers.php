<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

class MigrateUsers
{
    /**
     * @var string Specifies the email account to which mail will
     * be copied;; must be an existing account
     */
    public string $local;

    /**
     * @var string Specifies the protocol that will be used to transfer
     * email: imap4 or pop3, with or without SSL. Allowed values are
     * imap4, imap4s, pop3, pop3s.
     *
     * Note**: For pop3 migrations, only email in the remote address'
     * INBOX will be transferred (for imap4 migrations, mail in all
     * remote folders will be copied to local folders with the same name.
     *
     */
    public string $method;

    /**
     * @var string Specifies the email account from which mail will be copied.
     */
    public string $remote;

    /**
     * @var string Specifies the remote email server address including
     * the port, in the format address:port.
     */
    public string $server;

    /**
     * @var string[] A list of folder names on the remote server to ignore.
     * Sub folders should be separated with the IMAP server's path delimiter.
     *
     * Note:** This value is optional and is valid for imap migrations only.
     */
    public array $skip;

    /**
     * @var string A hash giving a mapping of remote folder names to local
     * folder names. Messages on the remote server, in the remote folders
     * will be copied to the local account to folders with the local name.
     * Sub folders should be separated with the IMAP server's path delimiter.
     * For local folders, the path separator is the dot character. For example,
     * to specify the folder /Sales/2009/June use ".Sales.2009.June"
     *
     * Note:** This value is optional, and is valid for imap migrations only.
     */
    public string $translate;
}