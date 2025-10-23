<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\User;

use Ocolin\OpenSrsMail\Client;

class SearchUserTest extends UserTest
{
    public function testSearchRange() : void
    {
        $count = 5;
        $search = Client::search_Object();
        $search->range->limit = $count;
        $output = self::$client->searchUsers(
                domain: $_ENV['TEST_DOMAIN'],
            attributes: $search
        );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'count', object: $output );
        self::assertEquals( expected: $count, actual: $output->count );
        //print_r( $output );
    }

    public function testSearchCriteria() : void
    {
        $search = Client::search_Object();
        $search->criteria->status = ['deleted'];
        $search->criteria->match = $_ENV['TEST_USERNAME'];
        $output = self::$client->searchUsers(
                domain: $_ENV['TEST_DOMAIN'],
            attributes: $search
        );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        print_r( $output );
    }
}