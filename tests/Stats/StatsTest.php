<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\Stats;

use Ocolin\OpenSrsMail\Tests\CoreTest;

class StatsTest extends CoreTest
{
    public function testStatsList() : void
    {
        $output = self::$client->statsList();
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'snapshots', object: $output );
        self::assertObjectHasProperty( propertyName: 'week', object: $output ->snapshots );
        self::assertIsArray( actual: $output->snapshots->week );
        self::assertObjectHasProperty( propertyName: 'day', object: $output ->snapshots );
        self::assertIsArray( actual: $output->snapshots->day );
        self::assertObjectHasProperty( propertyName: 'month', object: $output ->snapshots );
        self::assertIsArray( actual: $output->snapshots->month );
        //print_r($output);
    }

    public function testStatsSnapshot() : void
    {
        $date = date( format: 'Y-m-d' );
        $output = self::$client->statsSnapshot(
            type: 'company',
            object: $_ENV['TEST_COMPANY'],
            date: $date
        );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        self::assertObjectHasProperty( propertyName: 'snapshot', object: $output );
        //print_r( $output );
    }

    public function testStatsSummary() : void
    {
        $output = self::$client->statsSummary(
            type: 'user',
            object: $_ENV['TEST_GET_USER'],
            by: 'day'
        );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'success', object: $output );
        self::assertTrue( condition: $output->success );
        //print_r( $output );
    }
}