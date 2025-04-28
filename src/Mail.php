<?php

/**
 * This API client lets you make quick API calls to
 * OpenSRS Email API. You can keep variables in an
 * environment, or provide them when instantiating
 * a class object. You can also submit your own Guzzle
 * HTTP client if more specific configuration is needed.
 */

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Ocolin\OpenSrsMail\Types\Credentials;
use Ocolin\OpenSrsMail\Types\Error;

class Mail
{
    /**
     * @var HTTP HTTP Client.
     */
    private HTTP $http;

    /**
     * @var Credentials Object with login credentials.
     */
    private Credentials $credentials;


/* Constructor
------------------------------------------------------------- */

    /**
     * @param HTTP|null $http Guzzle HTTP handler.
     * @param Credentials|null $credentials Object with login credentials.
     * @param string|null $base_uri Base URI of the OpenSRS API.
     * @param string|null $user API username.
     * @param string|null $pass API password.
     * @throws Exception
     */
    public function __construct(
               ?HTTP $http = null,
        ?Credentials $credentials = null,
             ?string $base_uri = null,
             ?string $user = null,
             ?string $pass = null,
    )
    {
        $this->http = $http ?? new HTTP( base_uri: $base_uri );
        $this->credentials = $credentials ?? new Credentials(
                user: self::get_User( user: $user ),
            password: self::get_Pass( pass: $pass ),
        );
    }


/* CALL API METHOD
------------------------------------------------------------- */

    /**
     * @param string $method API method to call.
     * @param object|array<string,mixed> $payload Data to send to API
     * @return object Response object from API
     * @throws GuzzleException
     */
    public function call(
              string $method,
        object|array $payload= []
    ) : object
    {
        if(
            empty( $this->credentials->user ) OR
            empty( $this->credentials->password )
        ) {
            return new Error( error: "Client: Invalid login credentials" );
        }

        if( gettype($payload) === 'array' ) {
            $payload = (object)$payload; //
        }

        $payload->credentials = $this->credentials; // @phpstan-ignore property.notFound

        return $this->http->post( path: $method, payload: $payload );
    }



/* GET API USERNAME
------------------------------------------------------------- */

    /**
     * If no username is provided, check for environment variable.
     * If neither is found, return empty string.
     *
     * @param string|null $user API username.
     * @return string API username.
     */
    private static function get_User( ?string $user ) : string
    {
        if( $user !== null ) { return $user; }
        if( gettype( $_ENV['OPENSRS_USER'] ) === 'string' ) {
            return $_ENV['OPENSRS_USER'];
        }

        return "";
    }


/* GET API PASSWORD
------------------------------------------------------------- */

    /**
     * If a password is not provided, check for environment
     * variable. Otherwise, return empty string.
     *
     * @param string|null $pass API password.
     * @return string API Password.
     */
    private static function get_Pass( ?string $pass ) : string
    {
        if( $pass !== null ) { return $pass; }
        if( gettype($_ENV['OPENSRS_PASS']) === 'string' ) {
            return $_ENV['OPENSRS_PASS'];
        }

        return "";
    }
}