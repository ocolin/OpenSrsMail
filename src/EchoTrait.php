<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail;

use GuzzleHttp\Exception\GuzzleException;
use Ocolin\OpenSrsMail\Types\Payload;

trait EchoTrait
{

/* ECHO
------------------------------------------------------------- */

    /**
     * The echo method returns the JSON request and has no
     * other effect. It is used for testing and debugging.
     *
     * @param array<string,mixed> $payload
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function echo( array $payload ) : object | null
    {
        $output = $this->call( call: 'echo', payload: $payload );

        if( empty( $output->body )) { return null; }

        return (object)$output->body;
    }
}