<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests;

use Ocolin\OpenSrsMail\Client;

class EchoTest extends CoreTest
{
    public static Client $client;

    public function testEchoGood() : void
    {
        $output = self::$client->echo( payload: [ 'param' => 'value' ] );
        self::assertIsObject( actual: $output );
        self::assertObjectHasProperty( propertyName: 'param', object: $output );
        self::assertEquals( expected: 'value', actual: $output->param );
    }

}