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
use Ocolin\OpenSrsMail\Types\Payload;

class Client
{
    /**
     * @var HTTP HTTP Client.
     */
    private HTTP $http;

    /**
     * @var Credentials Object with login credentials.
     */
    private Credentials $credentials;


/* CONSTRUCTOR
------------------------------------------------------------------------------ */

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
                user: self::validate_User( user: $user ),
            password: self::validate_Pass( pass: $pass ),
        );
    }



/* CALL API
------------------------------------------------------------------------------ */

    /**
     * @param string $call API end-point path to call.
     * @param Payload|array<string,mixed> $payload Data to send to API
     * @throws GuzzleException
     */

    public function call(
               string $call,
        Payload|array $payload = []
    ) : object
    {
        if( is_array( $payload )) {
            $payload = self::convertToPayload( input: $payload );
        }

        if( empty( $payload->credentials )) {
            $payload->credentials = $this->credentials;
        }

        return $this->http->post( path: $call, payload: $payload );
    }


use EchoTrait;
use CompanyTrait;
use DomainTrait;
use WorkgroupTrait;
use UserTrait;
use BrandTrait;
use StatsTrait;
use MigrateTrait;


/* VALIDATE API USERNAME
------------------------------------------------------------------------------ */

    /**
     * If no username is provided, check for environment variable.
     * If neither is found, return empty string.
     *
     * @param string|null $user API username.
     * @return string API username.
     */
    private static function validate_User( ?string $user ) : string
    {
        if( $user !== null ) { return $user; }
        if( gettype( value: $_ENV['OPENSRS_MAIL_USER'] ) === 'string' ) {
            return $_ENV['OPENSRS_MAIL_USER'];
        }

        return "";
    }



/* VALIDATE API PASSWORD
------------------------------------------------------------------------------ */

    /**
     * If a password is not provided, check for environment
     * variable. Otherwise, return empty string.
     *
     * @param string|null $pass API password.
     * @return string API Password.
     */
    private static function validate_Pass( ?string $pass ) : string
    {
        if( $pass !== null ) { return $pass; }
        if( gettype( value: $_ENV['OPENSRS_MAIL_PASS'] ) === 'string' ) {
            return $_ENV['OPENSRS_MAIL_PASS'];
        }

        return "";
    }



/* CONVERT AN ARRAY OR OBJECT INTO A PAYLOAD DTO
------------------------------------------------------------------------------ */

    /**
     * @param array<string, mixed>|object $input List of payload parameters.
     * @return Payload Payload object.
     */
    public function convertToPayload( array|object $input ) : Payload
    {
        $payload = new Payload();
        if( is_object( $input )) {
            $input = get_object_vars( $input );
        }
        foreach( $input as $key => $value ) {
            $payload->{$key} = $value;
        }

        return $payload;
    }
}