<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\Brand;

use Ocolin\OpenSrsMail\Client;

class SearchBrandTest extends BrandTest
{
    public function testSearchBrand() : void
    {
        $search = Client::createSearchBrand();
        $search->criteria->company = $_ENV['TEST_COMPANY'];
        $search->range->limit = 2;

        $output = self::$client->searchBrands( search: $search );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'count', object: $output );
        self::assertEquals( expected: 2, actual: $output->count );
        self::assertCount( expectedCount: 2, haystack: $output->brands );
        //print_r( $output );
    }

    public function testSearchBrandMembers() : void
    {
        $search = Client::createBrandMember();
        $search->criteria->company = $_ENV['TEST_COMPANY'];
        $search->criteria->brand = 'Cruzio';
        $search->criteria->type = ['domain','user'];
        $search->range->limit = 1;

        $output = self::$client->searchBrandMembers( search: $search );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'count', object: $output );
        self::assertEquals( expected: 1, actual: $output->count );
        self::assertCount( expectedCount: 1, haystack: $output->brand_members );
        //print_r( $output );
    }
}