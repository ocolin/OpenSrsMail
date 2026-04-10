<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Traits;

use GuzzleHttp\Exception\GuzzleException;

/**
 * The authenticate method verifies a set of user credentials.
 */

trait AuthenticationMethods
{

/* AUTHENTICATE
----------------------------------------------------------------------------- */

    /**
     * The authenticate method verifies a set of user credentials.
     *
     * @param ?string $token Specify the token that you want to use.
     * If generate_session_token is true, this string will be used as the token.
     * @param ?bool $fetchExtraInfo Returns additional information about the
     * user. If set to true, the response will contain the extra_info field.
     * @param ?bool $generateSessionToken If set to true, returns a session
     * token and the duration of the token.
     * @param ?int $sessionTokenDuration The duration of the session token,
     * in seconds. Value can be a number between 1 and 86400 (24 hours).
     * If not specified, the default is 10800 (3 hours).
     * @return object API response object.
     * @throws GuzzleException
     */
    public function authenticate(
        ?string $token = null,
          ?bool $fetchExtraInfo = null,
          ?bool $generateSessionToken = null,
           ?int $sessionTokenDuration = null
    ) : object
    {
        $params = [];
        if( $token !== null ) { $params['token'] = $token; }
        if( $fetchExtraInfo !== null ) { $params['fetch_extra_info'] = $fetchExtraInfo; }
        if( $generateSessionToken !== null ) {
            $params['generate_session_token'] = $generateSessionToken;
        }
        if( $sessionTokenDuration !== null ) {
            $params['session_token_duration'] = $sessionTokenDuration;
        }

        return $this->request( method: 'authenticate', params: $params );
    }



/* ECHO
----------------------------------------------------------------------------- */

    /**
     * The echo method returns the JSON request and has no other effect.
     * It is used for testing and debugging.
     *
     * @param array<string, mixed>|object $data Payload to echo back.
     * @return object Echo of payload.
     * @throws GuzzleException
     */
    public function echo( array|object $data ) : object
    {
        return $this->request( method: 'echo', params: $data );
    }

}