<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\Company;

class GetCompanyChangesTest extends CompanyTest
{
    public function testGetCompanyChanges() : void
    {
        $output = self::$client->getCompanyChanges(
            company: $_ENV['TEST_COMPANY'], limit: 2
        );
        //print_r( $output );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'count', object: $output );
        self::assertEquals( expected: 2, actual: $output->count );
        self::assertCount( expectedCount: 2, haystack: $output->changes );
    }
}