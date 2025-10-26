<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\User;

class GetUserAttributeHistoryTest extends UserTest
{
    public function testGetUserAttributeHistoryGood() : void
    {
        $output = self::$client->getUserAttributeHistory(
            user: $_ENV['TEST_USER2'],
            attribute: 'name'
        );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        //print_r( $output );
    }

    public function testGetUserAttributeHistoryBad() : void
    {
        $output = self::$client->getUserAttributeHistory(
            user: 'kjshdfs' . $_ENV['TEST_USER2'],
            attribute: 'name'
        );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertFalse( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'error_number', object: $output );
        self::assertEquals( expected: 2, actual: $output->error_number );
        //print_r( $output );
    }
}