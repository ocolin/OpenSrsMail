<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail;

use GuzzleHttp\Exception\GuzzleException;
use Ocolin\OpenSrsMail\Types\Payload;

trait EchoTrait
{

/*
------------------------------------------------------------- */

    /**
     * @param Payload|array<string,mixed> $payload
     * @return object|null
     * @throws GuzzleException
     */
    public function echo( Payload|array $payload ) : object | null
    {
        $output = $this->call( call: 'echo', payload: $payload );

        if( empty( $output->body )) { return null; }

        return (object)$output->body;
    }
}