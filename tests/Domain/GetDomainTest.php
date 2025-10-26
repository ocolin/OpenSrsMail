<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\Domain;

class GetDomainTest extends DomainTest
{
    public function testGetDomainBad() : void
    {
        $output = self::$client->getDomain( domain: 'asdfasdfs' );
        //print_r( $output );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertFalse( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'error_number', object: $output );
        self::assertEquals( expected: 2, actual: $output->error_number );
    }

    public function testGetDomainGood() : void
    {
        $output = self::$client->getDomain( domain: $_ENV['TEST_DOMAIN'] );
        //print_r( $output );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'attributes', object: $output );
        self::assertEquals( expected: $_ENV['TEST_DOMAIN'], actual: $output->attributes->account );
    }
}