<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\Unit;

use Ocolin\EasyEnv\Env;
use Ocolin\OpenSrsMail\Config;
use Ocolin\OpenSrsMail\Exceptions\CacheException;
use Ocolin\OpenSrsMail\TokenCache;
use PHPUnit\Framework\TestCase;

class TokenCacheTest extends TestCase
{

    private Config $config;
    private TokenCache $cache;
    private string $cacheFile;


/* SETUP
----------------------------------------------------------------------------- */

    public static function setUpBeforeClass() : void
    {
        Env::load( files: __DIR__ . '/../../.env.testing' );
    }

    protected function setUp() : void
    {
        $this->config = new Config(
            host:       'A',
            username:   'user@example.com',
            password:   'secret',
            cache_path: sys_get_temp_dir()
        );
        $this->cache = new TokenCache( config: $this->config );
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



/* WRITE TESTS
----------------------------------------------------------------------------- */

    public function test_write_creates_cache_file() : void
    {
        $this->cache->write( $this->makeAuthResponse() );
        $this->assertFileExists( $this->cacheFile );
    }

    public function test_write_stores_token_in_cache_file() : void
    {
        $this->cache->write( $this->makeAuthResponse( token: 'abc123' ));
        $data = json_decode( file_get_contents( $this->cacheFile ));
        $this->assertSame( 'abc123', $data->token );
    }

    public function test_write_stores_expires_at_in_cache_file() : void
    {
        $before = time();
        $this->cache->write( $this->makeAuthResponse( duration: 3600 ));
        $after = time();

        $data = json_decode( file_get_contents( $this->cacheFile ));
        $this->assertGreaterThanOrEqual( $before + 3600 - 60, $data->expires_at );
        $this->assertLessThanOrEqual( $after + 3600 - 60, $data->expires_at );
    }

    public function test_write_applies_expiry_buffer() : void
    {
        $before = time();
        $this->cache->write( $this->makeAuthResponse( duration: 3600 ));

        $data = json_decode( file_get_contents( $this->cacheFile ));
        $this->assertLessThan( $before + 3600, $data->expires_at );
    }

    public function test_write_throws_on_failed_success() : void
    {
        $this->expectException( CacheException::class );
        $this->cache->write( (object)[ 'success' => false ] );
    }

    public function test_write_throws_on_missing_session_token() : void
    {
        $this->expectException( CacheException::class );
        $this->cache->write( (object)[
            'success'                => true,
            'session_token_duration' => 3600,
        ]);
    }

    public function test_write_throws_on_missing_session_token_duration() : void
    {
        $this->expectException( CacheException::class );
        $this->cache->write( (object)[
            'success'       => true,
            'session_token' => 'abc123',
        ]);
    }

    public function test_write_throws_on_unwritable_directory() : void
    {
        $config = new Config(
            host:       'A',
            username:   'user@example.com',
            password:   'secret',
            cache_path: '/nonexistent/path'
        );
        $cache = new TokenCache( config: $config );

        $this->expectException( CacheException::class );
        $cache->write( $this->makeAuthResponse() );
    }



/* GET TOKEN TESTS
----------------------------------------------------------------------------- */

    public function test_get_token_returns_null_when_no_cache_file() : void
    {
        $this->assertNull( $this->cache->getToken() );
    }

    public function test_get_token_returns_token_when_valid() : void
    {
        $this->cache->write( $this->makeAuthResponse( token: 'mytoken123' ));
        $this->assertSame( 'mytoken123', $this->cache->getToken() );
    }

    public function test_get_token_returns_null_when_expired() : void
    {
        $this->writeExpiredCache( token: 'oldtoken' );
        $this->assertNull( $this->cache->getToken() );
    }

    public function test_get_token_returns_null_for_corrupted_cache_file() : void
    {
        file_put_contents( $this->cacheFile, 'not valid json' );
        $this->assertNull( $this->cache->getToken() );
    }

    public function test_get_token_returns_null_for_empty_cache_file() : void
    {
        file_put_contents( $this->cacheFile, '' );
        $this->assertNull( $this->cache->getToken() );
    }

    public function test_get_token_returns_null_when_expires_at_missing() : void
    {
        file_put_contents( $this->cacheFile, json_encode([
            'token' => 'abc123',
        ]));
        $this->assertNull( $this->cache->getToken() );
    }

    public function test_get_token_returns_null_when_exactly_at_expiry() : void
    {
        file_put_contents( $this->cacheFile, json_encode([
            'token'      => 'abc123',
            'expires_at' => time(),
        ]));
        $this->assertNull( $this->cache->getToken() );
    }

    public function test_get_token_returns_token_when_not_yet_expired() : void
    {
        file_put_contents( $this->cacheFile, json_encode([
            'token'      => 'abc123',
            'expires_at' => time() + 3600,
        ]));
        $this->assertSame( 'abc123', $this->cache->getToken() );
    }



/* CLEAR TESTS
----------------------------------------------------------------------------- */

    public function test_clear_deletes_cache_file() : void
    {
        $this->cache->write( $this->makeAuthResponse() );
        $this->assertFileExists( $this->cacheFile );

        $this->cache->clear();
        $this->assertFileDoesNotExist( $this->cacheFile );
    }

    public function test_clear_does_not_throw_when_no_cache_file() : void
    {
        $this->assertFileDoesNotExist( $this->cacheFile );
        $this->cache->clear();
        $this->assertFileDoesNotExist( $this->cacheFile );
    }



/* HELPERS
----------------------------------------------------------------------------- */

    private function makeAuthResponse(
        string $token    = 'test_token_abc123',
        int    $duration = 3600
    ) : object
    {
        return (object)[
            'success'                => true,
            'session_token'          => $token,
            'session_token_duration' => $duration,
        ];
    }

    private function writeExpiredCache( string $token = 'expired_token' ) : void
    {
        file_put_contents( $this->cacheFile, json_encode([
            'token'      => $token,
            'expires_at' => time() - 3600,
        ]));
    }
}