<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\User;

class GetUserTest extends UserTest
{
    public function testGetUserGood() : void
    {
        $output = self::$client->getUser( user: $_ENV['TEST_GET_USER'] );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'attributes', object: $output );
        self::assertEquals( expected: $_ENV['TEST_GET_USER'], actual: $output->attributes->account );
        //print_r( $output );
    }

    public function testGetUserBad() : void
    {
        $output = self::$client->getUser( user: 'asdfdfasd@jkhsdfsd.com' );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertFalse( condition: $output->success );
        self::assertEquals( expected: 9, actual: $output->error_number );
        //print_r( $output );
    }
}