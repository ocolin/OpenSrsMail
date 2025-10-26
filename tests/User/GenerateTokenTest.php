<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\User;

class GenerateTokenTest extends UserTest
{

    public function testGenerateTokenGood() : void
    {
        $output = self::$client->generateToken(
            user: $_ENV['TEST_USER2'],
            reason: 'PHPUnit test reason',
            duration: 1
        );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'token', object: $output );
        self::assertIsString( actual: $output->token );
        //print_r( $output );
    }


    public function testGenerateTokenBad() : void
    {
        $output = self::$client->generateToken(
            user: 'lkjsdfsd' . $_ENV['TEST_USER2'],
            reason: 'PHPUnit test reason',
            duration: 1
        );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertFalse( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'error_number', object: $output );
        self::assertEquals( expected: 2, actual: $output->error_number );
        //print_r( $output );
    }
}
