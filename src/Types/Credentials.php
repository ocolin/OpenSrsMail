<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

class Credentials
{
    /**
     * @param string $user Username of OpenSRS account.
     * @param string $password Password of OpenSRS account.
     * @param string $client Optional client name.
     */
    public function __construct(
        public string $user,
        public string $password,
        public string $client = "OpenSRS Email Client 2.0"
    ){}
}