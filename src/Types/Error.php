<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

class Error
{
    /**
     * @param bool $success Whether the request was successful.
     * @param int $error_number Error code number.
     * @param string $error Error message.
     */
    public function __construct(
          public bool $success = false,
           public int $error_number = 0,
        public string $error = 'General error'
    ) {}
}