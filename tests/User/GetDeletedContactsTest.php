<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\User;

class GetDeletedContactsTest extends UserTest
{
    public function testGetDeletedContactsGood() : void
    {
        $output = self::$client->getDeletedContacts(
            user: $_ENV['TEST_USER2']
        );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'contacts', object: $output );
        self::assertIsArray( $output->contacts );
        //print_r( $output );
    }

    public function testGetDeletedContactsBad() : void
    {
        $output = self::$client->getDeletedContacts(
            user: 'dfghfgf' . $_ENV['TEST_USER2']
        );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertFalse( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'error_number', object: $output );
        self::assertEquals( expected: 2, actual: $output->error_number );
        //print_r( $output );
    }
}