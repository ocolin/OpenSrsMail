<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Tests\Unit;

use Ocolin\EasyEnv\Env;
use Ocolin\OpenSrsMail\Config;
use Ocolin\OpenSrsMail\Exceptions\ConfigException;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{

/* SETUP
----------------------------------------------------------------------------- */

    public static function setUpBeforeClass() : void
    {
        Env::load( files: __DIR__ . '/../../.env.testing' );
    }



/* HOST ALIAS TESTS
----------------------------------------------------------------------------- */

    public function test_host_alias_A_resolves_to_cluster_a() : void
    {
        $config = new Config(
            host:     'A',
            username: 'user@example.com',
            password: 'secret'
        );
        $this->assertSame(
            'https://admin.a.hostedemail.com/api',
            $config->host
        );
    }

    public function test_host_alias_B_resolves_to_cluster_b() : void
    {
        $config = new Config(
            host:     'B',
            username: 'user@example.com',
            password: 'secret'
        );
        $this->assertSame(
            'https://admin.b.hostedemail.com/api',
            $config->host
        );
    }

    public function test_host_alias_TEST_resolves_to_test_cluster() : void
    {
        $config = new Config(
            host:     'TEST',
            username: 'user@example.com',
            password: 'secret'
        );
        $this->assertSame(
            'https://admin.test.hostedemail.com/api',
            $config->host
        );
    }

    public function test_host_alias_is_case_insensitive() : void
    {
        $config = new Config(
            host:     'a',
            username: 'user@example.com',
            password: 'secret'
        );
        $this->assertSame(
            'https://admin.a.hostedemail.com/api',
            $config->host
        );
    }

    public function test_host_alias_lowercase_b_resolves() : void
    {
        $config = new Config(
            host:     'b',
            username: 'user@example.com',
            password: 'secret'
        );
        $this->assertSame(
            'https://admin.b.hostedemail.com/api',
            $config->host
        );
    }

    public function test_host_alias_lowercase_test_resolves() : void
    {
        $config = new Config(
            host:     'test',
            username: 'user@example.com',
            password: 'secret'
        );
        $this->assertSame(
            'https://admin.test.hostedemail.com/api',
            $config->host
        );
    }

    public function test_host_custom_url_passes_through() : void
    {
        $config = new Config(
            host:     'https://custom.example.com/api',
            username: 'user@example.com',
            password: 'secret'
        );
        $this->assertSame(
            'https://custom.example.com/api',
            $config->host
        );
    }

    public function test_host_invalid_url_throws_config_exception() : void
    {
        $this->expectException( ConfigException::class );
        new Config(
            host:     'not-a-valid-url',
            username: 'user@example.com',
            password: 'secret'
        );
    }

    public function test_host_missing_throws_config_exception() : void
    {
        $original = $_ENV['OPENSRS_MAIL_HOST'];
        unset( $_ENV['OPENSRS_MAIL_HOST'] );  // unset

        try {
            $this->expectException( ConfigException::class );
            new Config(
                username: 'user@example.com',
                password: 'secret'
            );
        } finally {
            $_ENV['OPENSRS_MAIL_HOST'] = $original;  // restore
        }
    }



/* USERNAME TESTS
----------------------------------------------------------------------------- */

    public function test_username_is_set_from_constructor() : void
    {
        $config = new Config(
            host:     'A',
            username: 'user@example.com',
            password: 'secret'
        );
        $this->assertSame( 'user@example.com', $config->username );
    }

    public function test_username_missing_throws_config_exception() : void
    {
        $original = $_ENV['OPENSRS_MAIL_USERNAME'];
        unset( $_ENV['OPENSRS_MAIL_USERNAME'] );
        try {
            $this->expectException(ConfigException::class);
            new Config(
                host: 'A',
                password: 'secret'
            );
        }
        finally {
            $_ENV['OPENSRS_MAIL_USERNAME'] = $original;
        }
    }



/* PASSWORD TESTS
----------------------------------------------------------------------------- */

    public function test_password_is_set_from_constructor() : void
    {
        $config = new Config(
            host:     'A',
            username: 'user@example.com',
            password: 'secret'
        );
        $this->assertSame( 'secret', $config->password );
    }

    public function test_password_missing_throws_config_exception() : void
    {
        $original = $_ENV['OPENSRS_MAIL_PASSWORD'];
        unset( $_ENV['OPENSRS_MAIL_PASSWORD'] );

        try {
            $this->expectException(ConfigException::class);
            new Config(
                host: 'A',
                username: 'user@example.com'
            );
        } finally {
            $_ENV['OPENSRS_MAIL_PASSWORD'] = $original;
        }
    }



/* MODE TESTS
----------------------------------------------------------------------------- */

    public function test_mode_token_is_set() : void
    {
        $config = new Config(
            host:     'A',
            username: 'user@example.com',
            password: 'secret',
            mode:     'token'
        );
        $this->assertSame( 'TOKEN', $config->mode );
    }

    public function test_mode_password_is_set() : void
    {
        $config = new Config(
            host:     'A',
            username: 'user@example.com',
            password: 'secret',
            mode:     'password'
        );
        $this->assertSame( 'PASSWORD', $config->mode );
    }

    public function test_mode_is_uppercased() : void
    {
        $config = new Config(
            host:     'A',
            username: 'user@example.com',
            password: 'secret',
            mode:     'Password'
        );
        $this->assertSame( 'PASSWORD', $config->mode );
    }

    public function test_mode_invalid_value_defaults_to_token() : void
    {
        $config = new Config(
            host:     'A',
            username: 'user@example.com',
            password: 'secret',
            mode:     'invalid'
        );
        $this->assertSame( 'TOKEN', $config->mode );
    }

    public function test_mode_missing_defaults_to_token() : void
    {
        $config = new Config(
            host:     'A',
            username: 'user@example.com',
            password: 'secret'
        );
        $this->assertSame( 'TOKEN', $config->mode );
    }



/* TOKEN EXPIRATION TESTS
----------------------------------------------------------------------------- */

    public function test_token_expiration_valid_value_is_set() : void
    {
        $config = new Config(
            host:             'A',
            username:         'user@example.com',
            password:         'secret',
            token_expiration: 3600
        );
        $this->assertSame( 3600, $config->token_expiration );
    }

    public function test_token_expiration_below_minimum_clamps_to_300() : void
    {
        $config = new Config(
            host:             'A',
            username:         'user@example.com',
            password:         'secret',
            token_expiration: 100
        );
        $this->assertSame( 300, $config->token_expiration );
    }

    public function test_token_expiration_above_maximum_clamps_to_86400() : void
    {
        $config = new Config(
            host:             'A',
            username:         'user@example.com',
            password:         'secret',
            token_expiration: 99999
        );
        $this->assertSame( 86400, $config->token_expiration );
    }

    public function test_token_expiration_at_minimum_boundary() : void
    {
        $config = new Config(
            host:             'A',
            username:         'user@example.com',
            password:         'secret',
            token_expiration: 300
        );
        $this->assertSame( 300, $config->token_expiration );
    }

    public function test_token_expiration_at_maximum_boundary() : void
    {
        $config = new Config(
            host:             'A',
            username:         'user@example.com',
            password:         'secret',
            token_expiration: 86400
        );
        $this->assertSame( 86400, $config->token_expiration );
    }

    public function test_token_expiration_missing_defaults_to_600() : void
    {
        $config = new Config(
            host:     'A',
            username: 'user@example.com',
            password: 'secret'
        );
        $this->assertSame( 600, $config->token_expiration );
    }



/* CACHE PATH TESTS
----------------------------------------------------------------------------- */

    public function test_cache_path_is_set_from_constructor() : void
    {
        $config = new Config(
            host:       'A',
            username:   'user@example.com',
            password:   'secret',
            cache_path: '/tmp/custom'
        );
        $this->assertSame( '/tmp/custom', $config->cache_path );
    }

    public function test_cache_path_missing_defaults_to_sys_temp_dir() : void
    {
        $config = new Config(
            host:     'A',
            username: 'user@example.com',
            password: 'secret'
        );
        $this->assertSame( sys_get_temp_dir(), $config->cache_path );
    }



/* OPTIONS TESTS
----------------------------------------------------------------------------- */

    public function test_options_are_set_from_constructor() : void
    {
        $config = new Config(
            host:     'A',
            username: 'user@example.com',
            password: 'secret',
            options:  [ 'timeout' => 30 ]
        );
        $this->assertSame( [ 'timeout' => 30 ], $config->options );
    }

    public function test_options_headers_key_is_removed() : void
    {
        $config = new Config(
            host:     'A',
            username: 'user@example.com',
            password: 'secret',
            options:  [ 'timeout' => 30, 'headers' => [ 'X-Custom' => 'value' ] ]
        );
        $this->assertArrayNotHasKey( 'headers', $config->options );
    }

    public function test_options_defaults_to_empty_array() : void
    {
        $config = new Config(
            host:     'A',
            username: 'user@example.com',
            password: 'secret'
        );
        $this->assertSame( [], $config->options );
    }



/* FROM ENV TESTS
----------------------------------------------------------------------------- */

    public function test_from_env_returns_config_instance() : void
    {
        $config = Config::fromEnv();
        $this->assertInstanceOf( Config::class, $config );
    }

    public function test_from_env_reads_host_from_env() : void
    {
        $config = Config::fromEnv();
        $this->assertNotEmpty( $config->host );
    }

    public function test_from_env_reads_username_from_env() : void
    {
        $config = Config::fromEnv();
        $this->assertNotEmpty( $config->username );
    }

    public function test_from_env_reads_password_from_env() : void
    {
        $config = Config::fromEnv();
        $this->assertNotEmpty( $config->password );
    }
}