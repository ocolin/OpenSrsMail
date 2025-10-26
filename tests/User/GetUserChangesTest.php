<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\User;

class GetUserChangesTest extends UserTest
{
    public function testGetUserChanges() : void
    {
        $output = self::$client->getUserChanges(
            user: $_ENV['TEST_USER2'],
            limit: 2
        );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'changes', object: $output );
        self::assertIsArray( actual: $output->changes );
        self::assertCount( expectedCount: 2, haystack: $output->changes );
        //print_r( $output );
    }

    public function testGetUserChangesError() : void
    {
        $output = self::$client->getUserChanges(
            user: 'sdfsd' . $_ENV['TEST_USER2'],
            limit: 2
        );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertFalse( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'error_number', object: $output );
        self::assertEquals( expected: 2, actual: $output->error_number );
        //print_r( $output );
    }
}