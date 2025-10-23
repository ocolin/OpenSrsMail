<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests;

use phpunit\Framework\TestCase;
use Ocolin\OpenSrsMail\Client;


class UserTest extends TestCase
{
    public static Client $client;

    public static object $mailbox;


    public function testCreateUser(): void
    {
        self::$mailbox = self::$client->changeUser(
                  user: $_ENV['TEST_USER'],
            attributes: [ 'password' => $_ENV['TEST_PASS'] ]
        );
        self::assertIsObject( self::$mailbox );
        self::assertObjectHasProperty( 'success', self::$mailbox );
        self::assertObjectHasProperty( 'audit', self::$mailbox );
        self::assertTrue( self::$mailbox->success );

        //print_r($output);
    }

    public function testGetUserGood() : void
    {
        $output = self::$client->getUser( user: $_ENV['TEST_USER'] );
        self::assertIsObject( $output );
        self::assertObjectHasProperty( 'success', $output );
        self::assertTrue( $output->success );
        self::assertObjectHasProperty( 'attributes', $output );
        self::assertEquals( $_ENV['TEST_USER'], $output->attributes->account );
        //print_r( $output );
    }

    public function testGetUserBad() : void
    {
        $output = self::$client->getUser( user: 'bob@noaddress.con' );
        self::assertIsObject( $output );
        self::assertObjectHasProperty( 'success', $output );
        self::assertFalse( $output->success );
        //print_r( $output );
    }



    public function testDeleteUser() : void
    {
        $output = self::$client->deleteUser( user: $_ENV['TEST_USER'] );
        self::assertIsObject( $output) ;
        self::assertObjectHasProperty( 'success', $output );
        self::assertObjectHasProperty( 'audit', $output );
        self::assertTrue( $output->success );
        //print_r( $output );
    }

    public static function setUpBeforeClass() : void
    {
        self::$client = new Client();
    }
}