<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class Payload
{
    public Credentials $credentials;

    public function __set( string $name, mixed $value )
    {
        $this->$name = $value;
    }
}