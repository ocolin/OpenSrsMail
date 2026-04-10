<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail;

class Credentials
{
    /**
     * @var string Username to log in with.
     */
    public string $user;

    /**
     * @var string Password for authentication.
     */
    public string $password;

    /**
     * @var string Token for token based authentication.
     */
    public string $token;

    /**
     * @var string Optional client information.
     */
    public string $client;

    /**
     * Default client name.
     */
    private const string CLIENT = 'ocolin/opensrs-mail';


/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param string $user Username to log with.
     * @param ?string $password Password for user.
     * @param ?string $token Token for user.
     * @param string $client REST client.
     */
    public function __construct(
         string $user,
        ?string $password = null,
        ?string $token    = null,
         string $client   = self::CLIENT
    )
    {
        $this->user = $user;
        if( $password !== null ) { $this->password = $password; }
        if( $token    !== null ) { $this->token    = $token;    }
        $this->client = $client;
    }
}