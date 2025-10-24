<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

class DomainAttributes
{
    /**
     * @var string[] A list of alternate names for the domain. Users in the
     * domain can receive mail that is sent to an alias domain. For example,
     * if example- corporation.com is an alias for example.com, so mail that
     * is sent to joe_user@example- corporation.com will be delivered to
     * joe_user@example.com The maximum number of aliases is 2000.
     */
    public array $aliases;

    /**
     * @var string[] A list of senders whose messages are not scanned for spam;
     * may include wildcards. For example joe_goodguy@bigmail.com and
     * *@example.com. Maximum is 1000 addresses.
     */
    public array $allow;

    /**
     * @var string[] A list of email addresses whose messages will always be identified
     * as spam; may include wildcards. For example, bob_thejerk@othermail.com and
     * *@spammers- inc.com. Messages from these addresses will always be considered to
     * be spam. Maximum is 1000 addresses.
    */
    public array $block;

    /**
     * @var string The default brand used for mailboxes in the domain. If undefined,
     * the company brand is used.
     */
    public string $brand;

    /**
     * @var bool If set, any mail sent to a mailbox in the domain that does not exist
     * will be sent to the specified mailbox.
     *
     * Note**: This feature cannot be enabled for new domains.
     */
    public bool $catchall;

    /**
     * @var string Used to reassign the domain to another company the admin controls.
     * Should be a string; the name of the receiving company.
     */
    public string $company;

    /**
     * @var string The type of password hashing/encoding to be performed when OpenSRS
     * receives an unencrypted password to store for a user. We recommend BCRYPT encoding.
     */
    public string $default_password_encoding;

    /**
     * @var bool If set to true, mailboxes in the domain will not function.
    */
    public bool $disabled;

    /**
     * @var string The way in which spam messages are handled by the OpenSRS email filter.
     *
     * Allowed values are:
     * quarantine — Spam messages are stored locally in the user's spam folder.
     * passthrough — Spam messages are delivered with the specified spamtag and spamheader.
     *
     * If undefined, the company's value is used.
     */
    public string $filterdelivery;

    /**
     * @var string The mail server (and optionally, SMTP port) to which
     * messages received by filter users in this domain are sent after
     * spam and virus scanning.
     */
    public string $filtermx;

    /**
     * @var string The default Webmail UI language for new users in the
     * domain. May be overridden by the user.
     *
     * A list of valid languages is displayed in the metadata ->options
     * field in the get_domain response.
     */
    public string $language;

    /**
     * @var int The maximum number of aliases that can be created for mailboxes
     * in the domain. If this number is less than the number of aliases currently
     * in the domain, no new aliases can be created. If not defined, any number
     * of aliases can be created.
     */
    public int $limit_aliases;

    /**
     * @var int The maximum number of users that can be created in the domain. If
     * this number is less than the number of users currently in the domain, no
     * new users can be created. If undefined, any number of users can be created.
     */
    public int $limit_users;

    /**
     * @var string Any notes you want to add to the domain. Maximum is 4096 characters.
     */
    public string $notes_external;

    /**
     * @var int The default maximum amount of storage (in bytes) that new mailboxes
     * may use, including mail and file storage.
     */
    public int $quota;

    /**
     * @var int The default maximum quota (in Megabytes) that can be assigned
     * to any mailbox in the domain.
     */
    public int $quota_maximum;

    /**
     * @var bool If set to true, the next time a user logs in, their passwords
     * will be converted to the encoding specified in default_password_encoding
     * (if their current encoding differs from the one specified in
     * default_password_encoding).
     */
    public bool $regen_passwords;

    /**
     * @var string The minimum level at which the password strength checks must
     * pass (see change_user).
     *
     * Valid values are null, "weak", "medium", "good", and "strong".
     * If set to null, the value will be inherited from the company level.
     */
    public string $password_strength;

    /**
     * @var string The default setting for new users for the IMAP4 service
     * (enabled, disabled, or suspended). If enabled, new users can log in via IMAP4.
     */
    public string $service_imap4;

    /**
     * @var string The default setting for new users for the POP3 service (enabled,
     * disabled, or suspended). If enabled, new users can log in via POP3.
     */
    public string $service_pop3;

    /**
     * @var string The default setting for new users for the SMTPIN service (enabled,
     * disabled, or suspended). If enabled, new users can send email.
     */
    public string $service_smtpin;

    /**
     * @var string The default setting for new users for the SMTPRELAY service
     * (enabled, disabled, or suspended).
     */
    public string $smtprelay;

    /**
     * @var string The default setting for new users for the SMTPRELAY Webmail
     * service (enabled, disabled, or suspended). If enabled, new users can
     * send email via Webmail.
     */
    public string $service_smtprelay_webmail;

    /**
     * @var string The default setting for new users for the Webmail
     * service (enabled, disabled, or suspended). If enabled, new users
     * can log in via Webmail.
     */
    public string $service_webmail;

    /**
     * @var int The default maximum number of messages that the user can
     * send in a 24 hour period. Maximum number is 10,000. If not defined,
     * the company's smtp_sent_limit is used.
     */
    public int $smtp_sent_limit;

    /**
     * @var string The folder to which messages that have been identified
     * as spam are delivered. Maximum 128 characters.
     */
    public string $spamfolder;

    /**
     * @var string The tag that will be assigned to the header of spam messages.
     * The format for the header must be [Capital letter]anything[:] anything.
     * For example, XSpam: Spam detected. Maximum 512 characters.
     */
    public string $spamheader;

    /**
     * @var string The level of aggressiveness for spam filtering. Allowed
     * values are: Normal, High, and Very High.
     */
    public string $spamlevel;

    /**
     * @var string The tag that is appended to an email message to identify
     * it as spam. Maximum 30 characters.
     */
    public string $spamtag;

    /**
     * @var string The default Webmail UI timezone for users in this domain.
     *
     * A list of valid timezones is displayed in the metadata ->options
     * field in the get_domain response.
     */
    public string $timezone;

    /**
     * @var bool If set to true, Webmail will offer users different From
     * addresses based on domain aliases.
     */
    public bool $wm_domainalias;

    /**
     * @var string The default workgroup to which new accounts in the
     * domain will belong.
     */
    public string $workgroup;
}