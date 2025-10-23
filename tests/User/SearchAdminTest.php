<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\User;

use Ocolin\OpenSrsMail\Client;

class SearchAdminTest extends UserTest
{

    public function testSearchAdminAll() : void
    {
        $output = self::$client->searchAdmins();
        self::assertIsObject( $output );
        self::assertObjectHasProperty( 'success', $output );
        self::assertTrue( $output->success );
        self::assertObjectHasProperty( 'admins', $output );
        self::assertIsArray( $output->admins );
    }

    public function testSearchAdminLimit() : void
    {
        $output = self::$client->searchAdmins(
            attributes: [ 'range' => [ 'limit' => 1 ] ]
        );
        self::assertIsObject( $output );
        self::assertObjectHasProperty( 'success', $output );
        self::assertTrue( $output->success );
        self::assertObjectHasProperty( 'count', $output );
        self::assertEquals( 1, $output->count );
        //print_r( $output );
    }

    public function testSearchAdminMatch() : void
    {
        $output = self::$client->searchAdmins(
            attributes: [ 'criteria' => [ 'match' => '*.adm' ] ]
        );
        self::assertIsObject( $output );
        self::assertObjectHasProperty( 'success', $output );
        self::assertTrue( $output->success );
        //print_r( $output );
    }

    public function testSearchAdminType() : void
    {
        $output = self::$client->searchAdmins(
            attributes: [ 'criteria' => [ 'type' => ['mail'] ]]
        );
        self::assertIsObject( $output );
        self::assertObjectHasProperty( 'success', $output );
        self::assertTrue( $output->success );
        //print_r( $output );
    }


    public function testSearchAdminsObject() : void
    {
        $search = Client::search_Admin_Object();
        $search->range->limit = 2;
        $search->criteria->type = ['company'];
        $output = self::$client->searchAdmins( attributes: $search );
        self::assertIsObject( $output );
        self::assertObjectHasProperty( 'success', $output );
        self::assertTrue( $output->success );
        self::assertObjectHasProperty( 'count', $output );
        self::assertEquals( 2, $output->count );
    }


}