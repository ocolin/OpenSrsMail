<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\User;

class GetDeletedMessagesTest extends UserTest
{
    public function testGetDeletedMessagesGood() : void
    {
        $output = self::$client->getDeletedMessages(
            user: $_ENV['TEST_USER2']
        );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'messages', object: $output );
        self::assertIsArray( $output->messages );
        //print_r( $output );
    }

    public function testGetDeletedMessagesBad() : void
    {
        $output = self::$client->getDeletedMessages(
            user: 'lksjdef' . $_ENV['TEST_USER2']
        );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertFalse( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'error_number', object: $output );
        self::assertEquals( expected: 2, actual: $output->error_number );
        //print_r( $output );
    }
}