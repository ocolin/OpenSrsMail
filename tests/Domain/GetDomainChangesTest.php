<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\Domain;

use Ocolin\OpenSrsMail\Tests\CoreTest;

class GetDomainChangesTest extends DomainTest
{
    public function testGetDomainChanges() : void
    {
        $output = self::$client->getDomainChanges(
            domain: $_ENV['TEST_DOMAIN'],
            limit: 2
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
