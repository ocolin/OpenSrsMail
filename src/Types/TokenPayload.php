<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

class TokenPayload extends Payload
{
    /**
     * @var string The user's mailbox name.
     */
    public string $user;

    /**
     * @var string The reason that the token was generated.
     */
    public string $reason;

    /**
     * @var string The token to add. If this value is not submitted,
     * a random token is generated.
    */
    public string $token;

    /**
     * @var bool If set to true, a session or sso type token may also be
     * used with the OMA. If type = oma, this parameter is ignored.
     *
     * Note: Only company admins can generate tokens with this argument
     * set totrue**.
     */
    public bool $oma;

    /**
     * @var int The number of hours for which the token is valid. If this
     * value is not specified, the token is valid for 24 hours.
    */
    public int $duration;

    /**
     * @var string The type of token to generate.
     *
     * Allowed values are:
     * oma — A token that is good for OMA logins only.
     * Note: This is the same type of token returned by theauthenticate** method,
     * but this method can generate a token for an arbitrary user.
     * session — Valid until the duration of the token expires. Can be used for
     * mail services and as credentials for the OMA (if oma is true in the request).
     * sso — A single use token that becomes invalid after it is used once. Can
     * be used to log in to mail services (Webmail, IMAP, SMPT, etc) and the
     * oma (if oma is true in the request).
     *
     * If type is not specified, session is used.
     */
    public string $type;
}