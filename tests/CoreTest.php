<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests;

use phpunit\Framework\TestCase;
use Ocolin\OpenSrsMail\Client;

class CoreTest extends TestCase
{
    public static Client $client;

/*
----------------------------------------------------------------------------- */

    public static function createUser(
        ?string $user = null,
        ?string $pass = null,
    ) : object
    {
        $user = $user ?? $_ENV['TEST_USER'];
        $pass = $pass ?? $_ENV['TEST_PASS'];
        return self::$client->changeUser(
                  user: $user,
            attributes: [ 'password' => $pass ]
        );
    }


/*
----------------------------------------------------------------------------- */

    public static function deleteUser( ?string $user = null ) : object
    {
        $user = $user ?? $_ENV['TEST_USER'];
        return self::$client->deleteUser( user: $user);
    }



/*
----------------------------------------------------------------------------- */

    public static function setUpBeforeClass(): void
    {
        self::$client = new Client();
    }
}