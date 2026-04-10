<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail;

use Ocolin\GlobalType\ENV;
use Ocolin\OpenSrsMail\Exceptions\ConfigException;

readonly class Config
{
    /**
     * @var string Hostname of OpenSRS API server. 3 current options are:
     * https://admin.test.hostedemail.com/api
     * https://admin.a.hostedemail.com/api
     * https://admin.b.hostedemail.com/api
     */
    public string $host;

    /**
     * @var string Username to log in with.
     */
    public string $username;

    /**
     * @var string Password to log in with.
     */
    public string $password;

    /**
     * @var string Authentication mode.
     * TOKEN - Login once and get a limited token that automatically updates as needed.
     * PASSWORD - Send username/password with every request.
     *
     * Default is TOKEN.
     */
    public string $mode;

    /**
     * @var int How long a token lasts for in Token mode. 5min to 24 hours.
     * Specified in seconds. Any value under 300 will default to 300. Does not apply
     * in PASSWORD mode.
     */
    public int $token_expiration;

    /**
     * @var ?string Optional path to store token cache when using TOKEN mode. Defaults to
     * system temporary directory.
     */
    public ?string $cache_path;

    /**
     * @var array<string, mixed> Additional Guzzle HTTP client options.
     */
    public array $options;

    /**
     * Allowed authentication modes.
     */
    private const array MODES = [ 'PASSWORD', 'TOKEN' ];

/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param ?string $host Open SRS API hostname and path.
     * @param ?string $username Open SRS username.
     * @param ?string $password Open SRS password.
     * @param ?string $mode Open SRS authentication mode.
     * @param ?int $token_expiration Expiration time of auth token.
     * @param ?string $cache_path Optional cache storage location.
     * @param array<string, mixed> $options Guzzle HTTP options.
     */
    public function __construct(
        ?string $host     = null,
        ?string $username = null,
        ?string $password = null,
        ?string $mode     = null,
           ?int $token_expiration = null,
        ?string $cache_path = null,
          array $options = []
    )
    {
        unset( $options['headers'] );
        $this->options = $options;
        $this->host = self::resolveHost( host: $host );

        $this->cache_path = $cache_path
            ?? ENV::getStringNull( name: 'OPENSRS_MAIL_CACHE_PATH' )
            ?? sys_get_temp_dir();

        $this->username = (string)self::resolve(
                   value: $username,
                  envKey: 'OPENSRS_MAIL_USERNAME',
            errorMessage: 'Missing OpenSRS Username'
        );

        $this->password = (string)self::resolve(
                   value: $password,
                  envKey: 'OPENSRS_MAIL_PASSWORD',
            errorMessage: 'Missing OpenSRS Password'
        );

        $this->mode = self::resolveMode( mode: $mode );

        $this->token_expiration = self::resolveTimeout( $token_expiration );
    }



/* RESOLVE SERVER HOST
----------------------------------------------------------------------------- */

    /**
     * @param ?string $host Host for Open SRS API server.
     * @return string Parsed host name.
     */
    private static function resolveHost( ?string $host = null ) : string
    {
        $host = $host ?? ENV::getStringNull( name: 'OPENSRS_MAIL_HOST' );
        if( $host === null ) {
            throw new ConfigException( message: 'Missing OpenSRS Mail Host' );
        }

        $hosts = [
            'A'    => 'https://admin.a.hostedemail.com/api',
            'B'    => 'https://admin.b.hostedemail.com/api',
            'TEST' => 'https://admin.test.hostedemail.com/api',
        ];

        $upper = strtoupper( $host );
        if( array_key_exists( key: $upper, array: $hosts )) {
            return $hosts[$upper];
        }

        if( !filter_var( value: $host, filter: FILTER_VALIDATE_URL )) {
            throw new ConfigException( message: 'Invalid Host: ' . $host );
        }

        return $host;
    }



/* RESOLVE TIMEOUT
----------------------------------------------------------------------------- */

    /**
     * @param ?int $timeout Timeout for temporary authentication token.
     * @return int Final timeout value.
     */
    private static function resolveTimeout( ?int $timeout = null ) : int
    {
        $timeout = $timeout ?? ENV::getIntNull( name: 'OPENSRS_MAIL_TOKEN_EXPIRATION' );
        if( $timeout === null ) { return 10800; }

        if( $timeout < 300 ) { return 300; }
        if( $timeout > 86400 ) { return 86400; }

        return $timeout;
    }



/* RESOLVE MODE
----------------------------------------------------------------------------- */

    /**
     * @param ?string $mode
     * @return string
     */
    private static function resolveMode( ?string $mode = null ) : string
    {
        $mode = $mode ?? ENV::getStringNull( name: 'OPENSRS_MAIL_MODE' );
        if( $mode === null ) { return 'TOKEN'; }
        $mode = strtoupper( $mode );
        if( !in_array( needle: $mode, haystack: self::MODES ) ) { return 'TOKEN'; }

        return $mode;
    }



/* RESOLVE ARGUMENTS
----------------------------------------------------------------------------- */

    /**
     * @param string|int|null $value Value to resolve.
     * @param string $envKey Name of environment variable to check.
     * @param string $errorMessage Error message to display.
     * @param string|int|null $default Default value to fall back to.
     * @return string|int
     */
    private static function resolve(
        string|int|null $value,
                 string $envKey,
                 string $errorMessage,
        string|int|null $default = null
    ) : string|int
    {
        return ( $value !== null && $value !== '' )
            ? $value
            : ( ENV::getStringNull( name: $envKey )
                ?? $default
                ?? throw new ConfigException( message: $errorMessage ));
    }



/* METHOD FOR ENV VAR ONLY USE
----------------------------------------------------------------------------- */

    public static function fromEnv() : self
    {
        return new self();
    }
}