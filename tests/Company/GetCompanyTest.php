<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\Company;


class GetCompanyTest extends CompanyTest
{
    public function testGetCompanyBad() : void
    {
        $output = self::$client->getCompany( company: 'kjshdfjsd' );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertFalse( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'error_number', object: $output );
        self::assertEquals( expected: 9, actual: $output->error_number );
        //print_r( $output );
    }

    public function testGetCompanyGood() : void
    {
        $output = self::$client->getCompany( company: $_ENV['TEST_COMPANY'] );
        //print_r( $output );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'attributes', object: $output );
        self::assertEquals( expected: $_ENV['TEST_COMPANY'], actual: $output->attributes->account );

    }
}