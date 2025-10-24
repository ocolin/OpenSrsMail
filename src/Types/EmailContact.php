<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

class EmailContact
{
    /**
     * @var string The contact's email address.
     */
    public string $email;

    /**
     * @var string The contact's name; maximum 128 characters.
     */
    public string $name;

    /**
     * @var string Optional notes; maximum 1024 characters.
     */
    public string $notes;

    /**
     * @var string The contact's phone number; maximum 64 characters.
     */
    public string $phone;

    /**
     * @var string The type of contact. Allowed values are
     * business, technical, emergency, abuse, and billing.
     */
    public string $type;
}