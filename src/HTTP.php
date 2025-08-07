<?php

/**
 * HTTP client wrapper to make HTTP request to REST server.
 */

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Ocolin\OpenSrsMail\Types\Error;
use Ocolin\OpenSrsMail\Types\Data;
use Psr\Http\Message\ResponseInterface;

class HTTP
{
    /**
     * @var Client Guzzle HTTP client.
     */
    private Client $client;

    /**
     * @var string OpenSRS API URL.
     */
    private string $base_uri;

    /**
     * @var string[] HTTP request headers.
     */
    private array $headers;

/*
------------------------------------------------------------- */

    /**
     * @param Client|null $client Guzzle HTTP client.
     * @param string|null $base_uri OpenSRS API URL.
     * @param bool $verify Verify SSL certificate.
     * @param bool $errors Show errors.
     * @param int $timeout Response timeout.
     * @throws Exception
     */
    public function __construct(
        ?Client $client = null,
        ?string $base_uri = null,
           bool $verify = false,
           bool $errors = false,
            int $timeout = 20
    )
    {

        $this->base_uri = self::load_URL( url: $base_uri );
        $this->headers = self::default_Headers();
        $this->client = $client ?? new Client(config: [
            'base_uri'        => $this->base_uri,
            'verify'          => $verify,
            'http_errors'     => $errors,
            'timeout'         => $timeout,
            'connect_timeout' => $timeout,
        ]);
    }

    /**
     * @param string $path Path of method.
     * @param object $payload Request data.
     * @param string[]|null $headers HTTP headers
     * @return object Object containing error message or results data
     * @throws GuzzleException
     */



/* HTTP POST
------------------------------------------------------------- */

    /**
     * @param string $path URI path of API method.
     * @param object $payload Data to send to API.
     * @param string[]|null $headers Optional HTTP headers.
     * @return object Response object from API.
     * @throws GuzzleException
     */
    public function post(
        string $path,
        object $payload,
        ?array $headers = null
    ) : object
    {
        if( empty( $this->base_uri ) ) {
            return new Error( error: "Client: Invalid API base URI." );
        }

        if( $headers !== null ) {
            $this->headers = array_merge( $this->headers, $headers );
        }
        $options = [
            'headers' => $this->headers,
            'body' => json_encode( (object)$payload )
        ];

        try {
            $request = $this->client->request(
                 method: 'POST',
                    uri: $path,
                options: $options
            );
        }
        catch ( Exception $e ) {
            return new Error( error: $e->getMessage());
        }

        return self::return_Results( request: $request );
    }



/* DEFAULT HEADERS
------------------------------------------------------------- */

    /**
     * Default required headers for API. These can be overridden
     * in the post call if needed.
     *
     * @return string[] Array of default headers.
     */
    private static function default_Headers(): array
    {
        return [
            'Content-type'    => 'application/json; charset=utf-8',
            'Accept'          => 'application/json',
            'User-Agent'      => 'Cruzio Client 2.0',
            'Accept-Charset'  => 'utf-8',
        ];
    }



/* LOAD API URL
------------------------------------------------------------- */

    /**
     * If no URL is specified, look for the environment
     * variable. If neither is found, respond with an
     * error object.
     *
     * @param string|null $url Optional URL to specify.
     * @return string URL to send to API call.
     * @throws Exception
     */
    public static function load_URL( ?string $url ) : string
    {
        if( $url !== null ) { return $url; }

        if(
            empty( $_ENV['OPENSRS_SERVER'] ) OR
            gettype( $_ENV['OPENSRS_SERVER'] ) !== 'string'
        )
        {
            throw new \Exception( message: "Client: Invalid API URL" );
        }

        return $_ENV['OPENSRS_SERVER'];
    }



/* RETURN HTTP RESPONSE RESULTS
---------------------------------------------------------------------------- */

    /**
     * @param ResponseInterface $request Guzzle Request object.
     * @return Data API data object
     */
    private static function return_Results( ResponseInterface $request ) : object
    {
        $output = new Data(
            status: $request->getStatusCode(),
            status_message: $request->getReasonPhrase(),
            headers: $request->getHeaders(),
            body: $request->getBody()->getContents()
        );

        if(
            isset( $output->headers['Content-Type'] ) AND
            str_contains(
                haystack: $output->headers['Content-Type'][0],
                needle: 'application/json'
            )
        ) {
            $output->body = json_decode( json: $output->body );
        }

        if( $output->body == null ) { $output->body = []; }

        return $output;
    }
}