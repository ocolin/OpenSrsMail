<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

class CompanyAttributes
{
    /**
     * @var string[] A list of senders whose messages are not scanned for
     * spam; may include wildcards. For example joe_goodguy@bigmail.com
     * and *@example.com. Maximum 1000 addresses.
     */
    public array $allow;

    /**
     * @var string[] A list of email addresses whose messages will always
     * be identified as spam; may include wildcards. For example,
     * bob_thejerk@othermail.com and *@spammers- inc.com. Messages from
     * these addresses will always be considered to be spam. Maximum is
     * 1000 addresses.
     */
    public array $block;

    /**
     * @var string The default brand that is used for domains that do not have
     * a brand assigned.
     */
    public string $brand;

    /**
     * @var EmailContact[] A list of up to 100 company contacts.
     */
    public array $contacts;

    /**
     * @var string The type of password hashing/encoding to be performed when
     * OpenSRS receives an unencrypted password to store for a user. We
     * recommend BCRYPT encoding.
     */
    public string $default_password_encoding;

    /**
     * @var string[] A list of company_ids for resellers that are allowed to
     * push domains to this company (see push_domain method)
     */
    public array $domain_push_allowed;

    /**
     * @var bool An email address notifications are sent to whenever a domain
     * is pushed to this company (see push_domain method)
     */
    public bool $domain_push_notify;

    /**
     * @var string The value that is used for domains in the company that do have
     * this attribute set. Allowed values are:
     *
     * quarantine — Spam messages are stored locally in the user's spam folder.
     * passthrough — Spam messages are delivered with the specified
     * spamtag and spamheader.
     */
    public string $filterdelivery;

    /**
     * @var string The default Webmail UI language for new domains in the company.
     *
     * A list of valid languages is displayed in the metadata ->options field in
     * the get_company response.
     */
    public string $language;

    /**
     * @var int The maximum number of aliases that can be created for domains in
     * this company.
     */
    public int $limit_aliases;

    /**
     * @var int The maximum number of users that can be created in domains in
     * the company.
     */
    public int $limit_users;

    /**
     * @var string Any notes you want to add to the company. Maximum is
     * 4096 characters.
     */
    public string $notes_external;

    /**
     * @var int The default quota assigned to new domains created in this company,
     * in megabytes (MB).
     */
    public int $quota;

    /**
     * @var int The maximum quota (in megabytes) that can be set for domains in
     * this company.
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
     * If set to null, the value will be inherited from the cluster default.
     */
    public string $password_strength;

    /**
     * @var string The default setting for new domains for the IMAP4 service (enabled,
     * disabled, or suspended). If enabled, new users can log in via IMAP4.
     */
    public string $service_imap4;

    /**
     * @var string The default setting for new domains for the POP3 service (enabled,
     * disabled, or suspended). If enabled, new users can log in via POP3.
     */
    public string $service_pop3;

    /**
     * @var string The current default setting for new users for the SMTPIN service (enabled,
     * disabled, or suspended). If enabled, new users can send email.
    */
    public string $service_smtpin;

    /**
     * @var string The default setting for new users for the SMTPRELAY service (enabled,
     * disabled, or suspended).
     */
    public string $service_smtprelay;

    /**
     * @var string The default setting for new users for the SMTPRELAY Webmail service (enabled,
     * disabled, or suspended). If enabled, new users can send email via Webmail.
     */
    public string $service_smtprelay_webmail;

    /**
     * @var string The default setting for new users for the Webmail service (enabled,
     * disabled, or suspended). If enabled, new users can log in via Webmail.
     */
    public string $service_webmail;

    /**
     * @var int The default maximum number of messages that users in the company can
     * send in a 24 hour period if this value is not set at the user or domain level.
     * Maximum value is 10,000.
     *
     * Note**: If the same message is sent to two recipients, it counts as two
     * messages against this limit.
     */
    public int $smtp_sent_limit;

    /**
     * @var string The folder to which messages that have been identified as spam are
     * delivered if this value is not set at the user or domain level. Maximum 128
     * characters.
     */
    public string $spamfolder;

    /**
     * @var string The tag that will be assigned to the header of spam messages if not
     * set at the user or domain level. The format for the header must be [Capital
     * letter]anything[:] anything. For example, XSpam: Spam detected. Maximum
     * 512 characters.
     */
    public string $spamheader;

    /**
     * @var string The level of aggressiveness for spam filtering if not set at the
     * user or domain level. Allowed values are: Normal, High, and Very High
    */
    public string $spamlevel;

    /**
     * @var string The tag that is appended to an email message to identify spam
     * if this value is not set at the user or domain level. Maximum 30 characters.
    */
    public string $spamtag;

    /**
     * @var string[] The addresses to which company snapshots emails are sent for the
     * company. Maximum 100 email addresses.
     */
    public array $stats_mailout;

    /**
     * @var bool The default value assigned to new domains in the company. If set to
     * true, Webmail will offer users different From addresses based on domain aliases.
    */
    public bool $wm_domainalias;
}