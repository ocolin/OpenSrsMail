<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\Domain;


class SearchDomainsTest extends DomainTest
{
    public function testSearchDomains() : void
    {
        $search = self::$client::createSearchObject();
        $search->criteria->company = $_ENV['TEST_COMPANY'];
        $search->range->limit = 2;

        $output = self::$client->searchDomain( search: $search );
        //print_r( $output );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'count', object: $output );
        self::assertEquals( expected: 2, actual: $output->count );
        self::assertCount( expectedCount: 2, haystack: $output->domains );
    }
}
