<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\Unit;

use Ocolin\EasyEnv\Env;
use Ocolin\OpenSrsMail\Client;
use Ocolin\OpenSrsMail\Config;
use Ocolin\OpenSrsMail\HTTP;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Response;

class ClientTest extends TestCase
{

/* SETUP
----------------------------------------------------------------------------- */

    public static function setUpBeforeClass() : void
    {
        Env::load( files: __DIR__ . '/../../.env.testing' );
    }



/* CONSTRUCTOR TESTS
----------------------------------------------------------------------------- */

    public function test_client_can_be_instantiated_with_no_arguments() : void
    {
        $client = new Client();
        $this->assertInstanceOf( Client::class, $client );
    }

    public function test_client_can_be_instantiated_with_config() : void
    {
        $config = new Config(
            host:     'A',
            username: 'user@example.com',
            password: 'secret'
        );
        $client = new Client( config: $config );
        $this->assertInstanceOf( Client::class, $client );
    }

    public function test_client_can_be_instantiated_with_mock_http() : void
    {
        $http = $this->makeHttp();
        $client = new Client( http: $http );
        $this->assertInstanceOf( Client::class, $client );
    }



/* REQUEST TESTS
----------------------------------------------------------------------------- */

    public function test_request_returns_object() : void
    {
        $client = new Client( http: $this->makeHttp() );
        $result = $client->request( method: 'get_user', params: [ 'user' => 'john@example.com' ] );
        $this->assertIsObject( $result );
    }

    public function test_request_accepts_array_params() : void
    {
        $client = new Client( http: $this->makeHttp() );
        $result = $client->request( method: 'get_user', params: [ 'user' => 'john@example.com' ] );
        $this->assertTrue( $result->success );
    }

    public function test_request_accepts_object_params() : void
    {
        $client = new Client( http: $this->makeHttp() );
        $result = $client->request(
            method: 'get_user',
            params: (object)[ 'user' => 'john@example.com' ]
        );
        $this->assertTrue( $result->success );
    }

    public function test_request_accepts_no_params() : void
    {
        $client = new Client( http: $this->makeHttp() );
        $result = $client->request( method: 'stats_list' );
        $this->assertTrue( $result->success );
    }



/* TRAIT COVERAGE - ONE METHOD PER TRAIT
----------------------------------------------------------------------------- */

    // AuthenticationMethods
    public function test_echo_calls_correct_endpoint() : void
    {
        $http = $this->makeHttpExpecting( endpoint: 'echo' );
        $client = new Client( http: $http );
        $client->echo( [] );
    }

    // BrandMethods
    public function test_search_brands_calls_correct_endpoint() : void
    {
        $http = $this->makeHttpExpecting( endpoint: 'search_brands' );
        $client = new Client( http: $http );
        $client->searchBrands();
    }

    // CompanyMethods
    public function test_get_company_calls_correct_endpoint() : void
    {
        $http = $this->makeHttpExpecting( endpoint: 'get_company' );
        $client = new Client( http: $http );
        $client->getCompany( company: 'test' );
    }

    // DomainMethods
    public function test_get_domain_calls_correct_endpoint() : void
    {
        $http = $this->makeHttpExpecting( endpoint: 'get_domain' );
        $client = new Client( http: $http );
        $client->getDomain( domain: 'example.com' );
    }

    // MigrationMethods
    public function test_migration_jobs_calls_correct_endpoint() : void
    {
        $http = $this->makeHttpExpecting( endpoint: 'migration_jobs' );
        $client = new Client( http: $http );
        $client->migrationJobs();
    }

    // StatsMethods
    public function test_stats_list_calls_correct_endpoint() : void
    {
        $http = $this->makeHttpExpecting( endpoint: 'stats_list' );
        $client = new Client( http: $http );
        $client->statsList();
    }

    // UserMethods
    public function test_get_user_calls_correct_endpoint() : void
    {
        $http = $this->makeHttpExpecting( endpoint: 'get_user' );
        $client = new Client( http: $http );
        $client->getUser( user: 'john@example.com' );
    }

    // WorkgroupMethods
    public function test_search_workgroups_calls_correct_endpoint() : void
    {
        $http = $this->makeHttpExpecting( endpoint: 'search_workgroups' );
        $client = new Client( http: $http );
        $client->searchWorkgroups();
    }



/* INTERESTING PARAMETER TESTS
----------------------------------------------------------------------------- */

    public function test_change_user_passes_attributes_array() : void
    {
        $guzzle = $this->createMock( \GuzzleHttp\ClientInterface::class );
        $guzzle->expects( $this->once() )
            ->method( 'request' )
            ->with(
                'POST',
                'change_user',
                $this->callback( function( $options ) {
                    return isset( $options['json']['attributes'] )
                        && is_array( $options['json']['attributes'] );
                })
            )
            ->willReturn( new Response( 200, [], json_encode([ 'success' => true ]) ));

        $config = new Config(
            host:       'A',
            username:   'user@example.com',
            password:   'secret',
            mode:       'PASSWORD',
            cache_path: sys_get_temp_dir()
        );
        $http   = new HTTP( config: $config, guzzle: $guzzle );
        $client = new Client( http: $http );

        $client->changeUser(
            user:       'john@example.com',
            attributes: [ 'name' => 'John Doe' ]
        );
    }

    public function test_set_role_uses_object_key_for_string() : void
    {
        $guzzle = $this->createMock( \GuzzleHttp\ClientInterface::class );
        $guzzle->expects( $this->once() )
            ->method( 'request' )
            ->with(
                'POST',
                'set_role',
                $this->callback( function( $options ) {
                    return isset( $options['json']['object'] )
                        && !isset( $options['json']['objects'] );
                })
            )
            ->willReturn( new Response( 200, [], json_encode([ 'success' => true ]) ));

        $config = new Config(
            host:       'A',
            username:   'user@example.com',
            password:   'secret',
            mode:       'PASSWORD',
            cache_path: sys_get_temp_dir()
        );
        $http   = new HTTP( config: $config, guzzle: $guzzle );
        $client = new Client( http: $http );

        $client->setRole(
            user:   'john@example.com',
            role:   'domain',
            object: 'example.com'
        );
    }

    public function test_set_role_uses_objects_key_for_array() : void
    {
        $guzzle = $this->createMock( \GuzzleHttp\ClientInterface::class );
        $guzzle->expects( $this->once() )
            ->method( 'request' )
            ->with(
                'POST',
                'set_role',
                $this->callback( function( $options ) {
                    return isset( $options['json']['objects'] )
                        && !isset( $options['json']['object'] );
                })
            )
            ->willReturn( new Response( 200, [], json_encode([ 'success' => true ]) ));

        $config = new Config(
            host:       'A',
            username:   'user@example.com',
            password:   'secret',
            mode:       'PASSWORD',
            cache_path: sys_get_temp_dir()
        );
        $http   = new HTTP( config: $config, guzzle: $guzzle );
        $client = new Client( http: $http );

        $client->setRole(
            user:   'john@example.com',
            role:   'domain',
            object: [ 'example.com', 'example2.com' ]
        );
    }



    /* HELPERS
    ----------------------------------------------------------------------------- */

    private function makeHttp() : HTTP
    {
        $guzzle = $this->createStub( \GuzzleHttp\ClientInterface::class );
        $guzzle->method( 'request' )
            ->willReturn( new Response( 200, [], json_encode([ 'success' => true ]) ));

        $config = new Config(
            host:       'A',
            username:   'user@example.com',
            password:   'secret',
            mode:       'PASSWORD',
            cache_path: sys_get_temp_dir()
        );

        return new HTTP( config: $config, guzzle: $guzzle );
    }

    private function makeHttpExpecting( string $endpoint ) : HTTP
    {
        $guzzle = $this->createMock( \GuzzleHttp\ClientInterface::class );
        $guzzle->expects( $this->once() )
            ->method( 'request' )
            ->with( 'POST', $endpoint, $this->anything() )
            ->willReturn( new Response( 200, [], json_encode([ 'success' => true ]) ));

        $config = new Config(
            host:       'A',
            username:   'user@example.com',
            password:   'secret',
            mode:       'PASSWORD',
            cache_path: sys_get_temp_dir()
        );

        return new HTTP( config: $config, guzzle: $guzzle );
    }
}