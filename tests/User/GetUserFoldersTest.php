<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\User;

class GetUserFoldersTest extends UserTest
{
    public function testGetUserFolders() : void
    {
        $output = self::$client->getUserFolders(
            user: $_ENV['TEST_USER2']
        );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'folders', object: $output );
        self::assertIsArray( actual: $output->folders );
        self::assertObjectHasProperty( propertyName: 'deleted_folders', object: $output );
        self::assertIsArray( actual: $output->deleted_folders );
        //print_r( $output );
    }

    public function testGetUserFoldersNoUser() : void
    {
        $output = self::$client->getUserFolders(
            user: 'kjhsdjf' . $_ENV['TEST_USER2']
        );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertFalse( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'error_number', object: $output );
        self::assertEquals( expected: 2, actual: $output->error_number );
        //print_r( $output );
    }
}