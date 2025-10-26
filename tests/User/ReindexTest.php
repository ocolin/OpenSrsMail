<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\User;

class ReindexTest extends UserTest
{
    public function testReindex() : void
    {
        $output = self::$client->reindex( user: $_ENV['TEST_USER2']);
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'id', object: $output );
        self::assertIsString( $output->id );
        self::assertObjectHasProperty( propertyName: 'status', object: $output );
        self::assertIsString( $output->status );
        //print_r( $output );
    }

}