<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\User;

class LogoutUserTest extends UserTest
{
    public function testLogout() : void
    {
        $output = self::$client->logoutUser( user: $_ENV['TEST_USER2'] );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        //print_r( $output );
    }

    public function testLogoutFail() : void
    {
        $output = self::$client->logoutUser( user: 'kjd' . $_ENV['TEST_USER2'] );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertFalse( condition: $output->success );
        //print_r( $output );
    }
}