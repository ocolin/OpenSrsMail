<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\Unit;

use Ocolin\EasyEnv\Env;
use Ocolin\OpenSrsMail\Config;
use Ocolin\OpenSrsMail\Credentials;
use Ocolin\OpenSrsMail\Exceptions\HttpException;
use Ocolin\OpenSrsMail\HTTP;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;

class HttpTest extends TestCase
{

    private Config $passwordConfig;
    private Config $tokenConfig;
    private string $cacheFile;


/* SETUP
----------------------------------------------------------------------------- */

    public static function setUpBeforeClass() : void
    {
        Env::load( files: __DIR__ . '/../../.env.testing' );
    }

    protected function setUp() : void
    {
        $this->passwordConfig = new Config(
            host:       'A',
            username:   'user@example.com',
            password:   'secret',
            mode:       'PASSWORD',
            cache_path: sys_get_temp_dir()
        );

        $this->tokenConfig = new Config(
            host:       'A',
            username:   'user@example.com',
            password:   'secret',
            mode:       'TOKEN',
            cache_path: sys_get_temp_dir()
        );

        $this->cacheFile = sys_get_temp_dir()
            . DIRECTORY_SEPARATOR
            . 'opensrs_mail_'
            . md5( 'user@example.com' )
            . '.json';
    }

    protected function tearDown() : void
    {
        if( file_exists( $this->cacheFile )) {
            unlink( $this->cacheFile );
        }
    }



/* REQUEST TESTS
----------------------------------------------------------------------------- */

    public function test_request_returns_object_on_success() : void
    {
        $http = new HTTP(
            config: $this->passwordConfig,
            guzzle: $this->mockGuzzle( body: [ 'success' => true ] )
        );

        $result = $http->request( method: 'get_user', params: [ 'user' => 'john@example.com' ] );
        $this->assertIsObject( $result );
    }

    public function test_request_returns_success_true() : void
    {
        $http = new HTTP(
            config: $this->passwordConfig,
            guzzle: $this->mockGuzzle( body: [ 'success' => true ] )
        );

        $result = $http->request( method: 'get_user', params: [ 'user' => 'john@example.com' ] );
        $this->assertTrue( $result->success );
    }

    public function test_request_returns_error_object_on_invalid_json() : void
    {
        $http = new HTTP(
            config: $this->passwordConfig,
            guzzle: $this->mockGuzzleRaw( body: 'not valid json' )
        );

        $result = $http->request( method: 'get_user' );
        $this->assertFalse( $result->success );
        $this->assertSame( 'Unknown error while decoding HTTP response', $result->error );
    }

    public function test_request_error_object_has_null_error_number_on_invalid_json() : void
    {
        $http = new HTTP(
            config: $this->passwordConfig,
            guzzle: $this->mockGuzzleRaw( body: 'not valid json' )
        );

        $result = $http->request( method: 'get_user' );
        $this->assertNull( $result->error_number );
    }

    public function test_request_passes_params_to_guzzle() : void
    {
        $guzzle = $this->createMock( ClientInterface::class );
        $guzzle->expects( $this->once() )
            ->method( 'request' )
            ->with(
                'POST',
                'get_user',
                $this->callback( function( $options ) {
                    return isset( $options['json']['user'] )
                        && $options['json']['user'] === 'john@example.com';
                })
            )
            ->willReturn( new Response( 200, [], json_encode([ 'success' => true ]) ));

        $http = new HTTP( config: $this->passwordConfig, guzzle: $guzzle );
        $http->request( method: 'get_user', params: [ 'user' => 'john@example.com' ] );
    }

    public function test_request_always_includes_credentials() : void
    {
        $guzzle = $this->createMock( ClientInterface::class );
        $guzzle->expects( $this->once() )
            ->method( 'request' )
            ->with(
                'POST',
                'get_user',
                $this->callback( function( $options ) {
                    return isset( $options['json']['credentials'] );
                })
            )
            ->willReturn( new Response( 200, [], json_encode([ 'success' => true ]) ));

        $http = new HTTP( config: $this->passwordConfig, guzzle: $guzzle );
        $http->request( method: 'get_user' );
    }



/* GET CREDENTIALS - PASSWORD MODE TESTS
----------------------------------------------------------------------------- */

    public function test_get_credentials_returns_credentials_object() : void
    {
        $http = new HTTP(
            config: $this->passwordConfig,
            guzzle: $this->mockGuzzle( body: [ 'success' => true ] )
        );

        $this->assertInstanceOf( Credentials::class, $http->getCredentials() );
    }

    public function test_get_credentials_password_mode_sets_password() : void
    {
        $http = new HTTP(
            config: $this->passwordConfig,
            guzzle: $this->mockGuzzle( body: [ 'success' => true ] )
        );

        $credentials = $http->getCredentials();
        $this->assertSame( 'secret', $credentials->password );
    }

    public function test_get_credentials_password_mode_sets_username() : void
    {
        $http = new HTTP(
            config: $this->passwordConfig,
            guzzle: $this->mockGuzzle( body: [ 'success' => true ] )
        );

        $credentials = $http->getCredentials();
        $this->assertSame( 'user@example.com', $credentials->user );
    }

    public function test_get_credentials_password_mode_does_not_set_token() : void
    {
        $http = new HTTP(
            config: $this->passwordConfig,
            guzzle: $this->mockGuzzle( body: [ 'success' => true ] )
        );

        $credentials = $http->getCredentials();
        $this->assertFalse( isset( $credentials->token ) );
    }



/* GET CREDENTIALS - TOKEN MODE TESTS
----------------------------------------------------------------------------- */

    public function test_get_credentials_token_mode_calls_authenticate_when_no_cache() : void
    {
        $guzzle = $this->createMock( ClientInterface::class );
        $guzzle->expects( $this->once() )
            ->method( 'request' )
            ->willReturn( new Response( 200, [], json_encode(
                $this->makeAuthResponse()
            )));

        $http = new HTTP( config: $this->tokenConfig, guzzle: $guzzle );
        $http->getCredentials();
    }

    public function test_get_credentials_token_mode_sets_token() : void
    {
        $http = new HTTP(
            config: $this->tokenConfig,
            guzzle: $this->mockGuzzle( body: $this->makeAuthResponse() )
        );

        $credentials = $http->getCredentials();
        $this->assertSame( 'test_session_token', $credentials->token );
    }

    public function test_get_credentials_token_mode_does_not_set_password() : void
    {
        $http = new HTTP(
            config: $this->tokenConfig,
            guzzle: $this->mockGuzzle( body: $this->makeAuthResponse() )
        );

        $credentials = $http->getCredentials();
        $this->assertFalse( isset( $credentials->password ) );
    }

    public function test_get_credentials_token_mode_reuses_cached_token() : void
    {
        // Write a valid token to cache
        file_put_contents( $this->cacheFile, json_encode([
            'token'      => 'cached_token_xyz',
            'expires_at' => time() + 3600,
        ]));

        // Guzzle should never be called since we have a valid cached token
        $guzzle = $this->createMock( ClientInterface::class );
        $guzzle->expects( $this->never() )->method( 'request' );

        $http = new HTTP( config: $this->tokenConfig, guzzle: $guzzle );
        $credentials = $http->getCredentials();
        $this->assertSame( 'cached_token_xyz', $credentials->token );
    }

    public function test_get_credentials_token_mode_reauthenticates_on_expired_cache() : void
    {
        // Write an expired token to cache
        file_put_contents( $this->cacheFile, json_encode([
            'token'      => 'expired_token',
            'expires_at' => time() - 3600,
        ]));

        $guzzle = $this->createMock( ClientInterface::class );
        $guzzle->expects( $this->once() )
            ->method( 'request' )
            ->willReturn( new Response( 200, [], json_encode(
                $this->makeAuthResponse( token: 'fresh_token' )
            )));

        $http = new HTTP( config: $this->tokenConfig, guzzle: $guzzle );
        $credentials = $http->getCredentials();
        $this->assertSame( 'fresh_token', $credentials->token );
    }



/* AUTHENTICATE TESTS
----------------------------------------------------------------------------- */

    public function test_authenticate_throws_http_exception_on_failed_success() : void
    {
        $http = new HTTP(
            config: $this->tokenConfig,
            guzzle: $this->mockGuzzle( body: [
                'success'      => false,
                'error'        => 'Invalid credentials supplied in request',
                'error_number' => 1,
            ])
        );

        $this->expectException( HttpException::class );
        $this->expectExceptionMessage( 'Invalid credentials supplied in request' );
        $http->getCredentials();
    }

    public function test_authenticate_throws_http_exception_on_invalid_json() : void
    {
        $http = new HTTP(
            config: $this->tokenConfig,
            guzzle: $this->mockGuzzleRaw( body: 'not valid json' )
        );

        $this->expectException( HttpException::class );
        $http->getCredentials();
    }

    public function test_authenticate_throws_with_unknown_error_when_no_error_field() : void
    {
        $http = new HTTP(
            config: $this->tokenConfig,
            guzzle: $this->mockGuzzle( body: [ 'success' => false ] )
        );

        $this->expectException( HttpException::class );
        $this->expectExceptionMessage( 'Unknown error' );
        $http->getCredentials();
    }



/* HELPERS
----------------------------------------------------------------------------- */

    /**
     * @param array<string, mixed> $body
     */
    private function mockGuzzle( array $body ) : ClientInterface
    {
        return $this->mockGuzzleRaw( body: json_encode( $body ) ?: '' );
    }

    private function mockGuzzleRaw( string $body ) : ClientInterface
    {
        $guzzle = $this->createStub( ClientInterface::class );
        $guzzle->method( 'request' )
            ->willReturn( new Response( 200, [], $body ));
        return $guzzle;
    }

    /**
     * @param string $token
     * @param int $duration
     * @return array<string, mixed>
     */
    private function makeAuthResponse(
        string $token    = 'test_session_token',
        int    $duration = 3600
    ) : array
    {
        return [
            'success'                => true,
            'session_token'          => $token,
            'session_token_duration' => $duration,
        ];
    }
}