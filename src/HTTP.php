<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail;

use GuzzleHttp\Exception\GuzzleException;
use Ocolin\OpenSrsMail\Exceptions\HttpException;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client AS GuzzleClient;


class HTTP
{
    /**
     * @var TokenCache Handles token caching.
     */
    private readonly TokenCache $tokenCache;

    /**
     * @var ClientInterface Guzzle handler.
     */
    private ClientInterface $guzzle;


/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param Config $config Data configuration object.
     * @param ?ClientInterface $guzzle Guzzle handler for mocking.
     */
    public function __construct(
        private readonly Config $config,
        ?ClientInterface $guzzle = null
    )
    {
        $this->tokenCache = new TokenCache( config: $this->config );
        $this->guzzle = $guzzle ?? new GuzzleClient(
            array_merge(
                [ 'timeout' => 20, 'verify' => true, ],
                $this->config->options,
                [
                    'base_uri' => $this->config->host,
                    'http_errors' => false,
                    'headers'  => [
                        'Content-type' => 'application/json; charset=utf-8',
                        'Accept'       => 'application/json',
                        'User-Agent'   => 'ocolin/opensrs-mail 2.0',
                    ]
                ]
            )
        );
    }



/* MAKE API REQUEST
----------------------------------------------------------------------------- */

    /**
     * @param string $method OpenSRS API method to call.
     * @param array<string, mixed> $params Method parameters.
     * @return object Response object.
     * @throws GuzzleException
     */
    public function request(
        string $method,
         array $params = [],
    ) : object
    {
        $params['credentials'] = $this->getCredentials();

        $response = $this->guzzle->request(
             method: 'POST',
                uri: $method,
            options: [ 'json' => $params ]
        );

        $body = json_decode( $response->getBody()->getContents());
        if( !is_object( $body )) {
            return (object)[
                'success' => false,
                'error' => 'Unknown error while decoding HTTP response',
                'error_number' => null
            ];
        }

        return $body;
    }



/* GET CREDENTIALS
----------------------------------------------------------------------------- */

    /**
     * @return Credentials Credential object needed for authentication.
     * @throws GuzzleException
     */
    public function getCredentials() : Credentials
    {
        // Password mode
        if( $this->config->mode === 'PASSWORD' ) {
            return new Credentials(
                    user: $this->config->username,
                password: $this->config->password,
            );
        }

        // Missign or expired token
        $token = $this->tokenCache->getToken();
        if( $token === null ) {
            $auth = $this->authenticate();
            $this->tokenCache->write( $auth );
            return new Credentials(
                 user: $this->config->username,
                token: $auth->session_token // @phpstan-ignore property.notFound
            );
        }

        // Have valid token
        return new Credentials(
             user: $this->config->username,
            token: $token
        );
    }



/* AUTHENTICATE WITH SERVER
----------------------------------------------------------------------------- */

    /**
     * @return object Authentication response object.
     * @throws GuzzleException
     */
    private function authenticate() : object
    {
        $response = $this->guzzle->request(
             method: 'POST',
                uri: 'authenticate',
            options: [
                'json' => [
                    'credentials' => [
                        'user' => $this->config->username,
                        'password' => $this->config->password,
                    ],
                    'generate_session_token' => true,
                    'session_token_duration' => $this->config->token_expiration
                ]
            ]
        );

        $body = json_decode( $response->getBody()->getContents() );
        if( !is_object( $body ) OR empty( $body->success ) ) {
            $error = 'Unknown error';
            if( is_object( $body ) AND !empty( $body->error ) ) {
                $error = $body->error;
            }

            throw new HttpException(  message: $error );
        }

        return $body;
    }
}