<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\User;

class GetSieveTest extends UserTest
{
    public function testGetSieve() : void
    {
        $output = self::$client->getSieve(
            user: $_ENV['TEST_USER2']
        );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        print_r( $output );
    }
}