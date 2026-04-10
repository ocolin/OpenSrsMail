<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail;

use Ocolin\OpenSrsMail\Exceptions\CacheException;


class TokenCache
{
    /**
     * Buffer to avoid token expiring soon issues.
     */
    private const int TOKEN_EXPIRY_BUFFER = 60;

/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param Config $config Data configuration object.
     */
    public function __construct( private readonly Config $config ) {}


/* WRITE TOKEN CACHE
----------------------------------------------------------------------------- */

    /**
     * @param object $auth Authentication object from API server.
     * @return void
     */
    public function write( object $auth ) : void
    {
        if(
            empty( $auth->success ) OR
            empty( $auth->session_token_duration ) OR
            empty( $auth->session_token )
        ) {
            throw new CacheException( message: 'Failed to authenticate token' );
        }

        $filename = $this->getCachePath();

        if( !is_writable( (string)$this->config->cache_path )) {
            throw new CacheException(
                message: "Unable to write token to directory: {$this->config->cache_path}"
            );
        }

        $handle = fopen( filename: $filename, mode: 'w' );
        if( $handle === false ) {
            throw new CacheException(
                message: "Unable to open cache file for writing: {$filename}"
            );
        }
        flock( stream: $handle, operation: LOCK_EX );

        $payload = json_encode([
            'token' => $auth->session_token,
            'session_token_duration' => $auth->session_token_duration,
            'expires_at' => time()
                + $auth->session_token_duration
                - self::TOKEN_EXPIRY_BUFFER
        ]);

        if( $payload === false ) {
            throw new CacheException(
                message: "Unable to write to cache file: {$filename}"
            );
        }

        try {
            $result = fwrite( stream: $handle, data: $payload );
            if( $result === false ) {
                throw new CacheException(
                    message: "Failed to write to cache file: {$filename}"
                );
            }
        }
        finally {
            flock( stream: $handle, operation: LOCK_UN );
            fclose( stream: $handle );
        }
    }



/* GET CACHED TOKEN
----------------------------------------------------------------------------- */

    /**
     * @return ?string Cached token.
     */
    public function getToken() : ?string
    {
        $filename = $this->getCachePath();

        if( !file_exists( filename: $filename )) { return null; }

        $data = json_decode( (string)file_get_contents( filename: $filename ));
        if( !is_object( $data ) ) { return null; }
        if( empty( $data->expires_at ))   { return null; }
        if( time() >= $data->expires_at ) { return null; }

        return $data->token ?? null;
    }



/* CLEAR CACHE
----------------------------------------------------------------------------- */

    public function clear() : void
    {
        $filename = $this->getCachePath();
        if( !file_exists( filename: $filename )) { return; }

        $result = unlink( $filename );
        if( $result === false ) {
            throw new CacheException(
                message: "Unable to delete cache file: {$filename}"
            );
        }
    }



/* GET PATH TO CACHE FILE
----------------------------------------------------------------------------- */

    /**
     * @return string Full file path and name.
     */
    private function getCachePath() : string
    {
        return $this->config->cache_path
            . DIRECTORY_SEPARATOR
            . 'opensrs_mail_'
            . md5( $this->config->username )
            . '.json';
    }
}