<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail;

use GuzzleHttp\Exception\GuzzleException;

class Client
{
    private HTTP $http;

    use Traits\AuthenticationMethods;
    use Traits\BrandMethods;
    use Traits\CompanyMethods;
    use Traits\DomainMethods;
    use Traits\MigrationMethods;
    use Traits\StatsMethods;
    use Traits\UserMethods;
    use Traits\WorkgroupMethods;

/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    public function __construct( ?HTTP $http = null, ?Config $config = null )
    {
        $this->http = $http ?? new HTTP( config: $config ?? new Config()    );
    }



/*
----------------------------------------------------------------------------- */

    /**
     * @param string $method
     * @param array<string, mixed>|object $params
     * @return object
     * @throws GuzzleException
     */
    public function request( string $method, array|object $params = [] ) : object
    {
        $params = (array)$params;

        return $this->http->request( method: $method, params: $params );
    }
}