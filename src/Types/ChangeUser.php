<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

class ChangeUser
{
    /**
     * @var string[] The list of alternate names for the account, for example, joe@example.com,
     * joey@example.com, juser@example.com. Mail sent to an alias address is delivered to the account.
     * The alias address can be used to log in to the account via IMAP4, POP3, Webmail and SMTP
     *
     * The maximum number of aliases is 2,000.
     */
    public array $aliases;

    /**
     * @var string[] A list of email addresses on the user's allow list; may include wildcards. For
     * example joe_goodguy@bigmail.com and *@example.com.
     *
     * Maximum is 1000 addresses.
     */
    public array $allow;

    /**
     * @var string The text of the message that is automatically sent back to senders if
     * delivery_autoresponder is set to true.
     *
     * Maximum size is 4,000 characters.
     */
    public string $autoresponder;

    /**
     * @var int The date that the autoresponder expires, expressed in UNIX Epoch
     * time. If not specified, the autoresponder never expires.
     */
    public int $autoresponder_option_enddate;

    /**
     * @var int The number of hours that must pass before the autoresponder message
     * is sent again to the same address. Must be less than 1,095 (45 days). If not
     * set, an interval of 24 hours is used
     */
    public int $autoresponder_option_interval;

    /**
     * @var string[] A list of email addresses on the user's block list; may include wildcards.
     * For example, bob_thejerk@othermail.com and *@spammers- inc.com. Messages from these
     * addresses will always be considered to be spam.
     *
     * Maximum is 1000 addresses.
     */
    public array $block;

    /**
     * @var string The Webmail brand for this mailbox. If not specified, the account uses
     * the domain setting.
     */
    public string $brand;

    /**
     * @var bool If set to true, the configured auto response message is sent to the sender.
     */
    public bool $delivery_autoresponder;

    /**
     * @var bool If set to true, messages are scanned and then passed to the domain's filtermx
     * host; the messages are not stored locally.
     *
     * Note: If delivery_filter = true, all other delivery attributes must be false**.
     */
    public bool $delivery_filter;

    /**
     * @var bool If set to true, the message is forwarded to the mailbox's forward_recipients list.
     */
    public bool $delivery_forward;

    /**
     * @var bool If set to true, the message is passed through the mailbox's sieve filters
     * and stored locally.
    */
    public bool $delivery_local;

    /**
     * @var string The fax number for the account owner; can be a maximum of 30 characters.
     */
    public string $fax;

    /**
     * @var string Determines what happens to spam messages:
     *
     * quarantine — Spam messages are stored locally in the user's spam folder.
     *
     * passthrough — Spam messages are delivered with the specified spamtag and spamheader.
     *
     * If not defined, the account uses the domain's filterdelivery setting.
     */
    public string $filterdelivery;

    /**
     * @var bool If set to true and delivery_forward is also set to true, only messages
     * from addresses on the forward recipients list are forwarded.
    */
    public bool $forward_option_restricted;

    /**
     * @var string If delivery_forward is set to true, this string is added to the beginning
     * of the Subject line of forwarded messages. String can be up to 128 characters
    */
    public string $forward_option_subject_prefix;

    /**
     * @var string If delivery_forward is set to true, this email address is added to the
     * Reply-To header of forwarded messages.
    */
    public string $forward_option_reply_to;

    /**
     * @var string[] If delivery_forward is set to true, incoming messages will be forwarded
     * to this list of addresses.
     *
     * Maximum number of addresses is 1,000.
    */
    public array $recipients;

    /**
     * @var string The default language in which the mailbox will be displayed. May be
     * overridden by the user.
     *
     * A list of valid language names is displayed in the metadata->options field in
     * the get_user response.
     */
    public string $language;

    /**
     * @var string A string that contains the user's MAC UI preferences. Only used
     * by the MAC; not recommended for use by other applications.
     *
     * Maximum 2048 characters.
    */
    public string $macsettings;

    /**
     * @var int The maximum number of entries (contacts and groups) that the user
     * can have in their address book.
    */
    public int $max_pab_entries;

    /**
     * @var string The name that is used in the From field of email messages.
     * The format is UTF-8 text and can be up to 512 characters.
    */
    public string $name;

    /**
     * @var string Any notes you want to add to the user. Maximum is 4096 characters.
     */
    public string $notes_external;

    /**
     * @var string The password used to log in to all services.
     */
    public string $password;

    /**
     * @var string The user's phone number; maximum 30 characters.
     */
    public string $phone;

    /**
     * @var int The maximum amount of storage (in bytes) that the mailbox
     * may use, including mail and file storage.
    */
    public int $quota;

    /**
     * @var bool Determines whether spam messages are rejected at the SMTP level.
     * Allowed values are true and false.
    */
    public bool $reject_spam;

    /**
     * @var string The setting for the IMAP4 service (enabled, disabled, or
     * suspended). If enabled, the user can log in via IMAP4.
    */
    public string $service_imap4;

    /**
     * @var string The setting for the POP3 service (enabled, disabled, or
     * suspended). If enabled, the user can log in via POP3.
    */
    public string $service_pop3;

    /**
     * @var string The setting for the SMTPIN service (enabled, disabled, or
     * suspended). If enabled, the user can send email.
    */
    public string $service_smtpin;

    /**
     * @var string The setting for the SMTPRELAY service (enabled, disabled, or suspended).
     */
    public string $service_smtprelay;

    /**
     * @var string The setting for the SMTPRELAY Webmail service (enabled, disabled,
     * or suspended). If enabled, the user can send email via Webmail.
    */
    public string $service_smtprelay_webmail;

    /**
     * @var string The setting for the Webmail service (enabled, webmail disabled,
     * or suspended). If enabled, the user can log in via Webmail.
     */
    public string $service_webmail;

    /**
     * @var string The user's sieve filters.
    */
    public string $sieve;

    /**
     * @var int The number of messages that the user is allowed to send in a
     * 24 hour period. Maximum number is 10,000. If not defined, the domain's
     * smtp_sent_limit is used.
     *
     * Note**: If the same message is sent to two recipients, it counts as two
     * messages against this limit.
     */
    public int $smtp_sent_limit;

    /**
     * @var string The folder into which messages identified as spam will be delivered.
     * Maximum 128 characters. Nested folders are separated by the '/' character,
     * for example, "Archive/Junk/Spam" If not defined, the mailbox uses the
     * domain's spamfolder setting.
 */
    public string $spamfolder;

    /**
     * @var string The tag that is added to messages that are identified as spam.
     *
     * Maximum 512 characters.
     */
    public string $spamheader;

    /**
     * @var string The level of aggressiveness set for the spam filter. Valid
     * values are Normal, High, and Very High. If not set, the mailbox uses the
     * domain's spamlevel setting.
     */
    public string $spamlevel;

    /**
     * @var string The value of this field is prepended to the subject of any message
     * that is identified as spam. Maximum 30 characters. If not defined, the mailbox
     * uses the domain's spamtag setting.
     *
     * Note**: This value is not supported for filteronly accounts.
     */
    public string $spamtag;

    /**
     * @var string The timezone that the mailbox will use.
     *
     * A list of valid timezones is displayed in the metadata ->options field in
     * the get_user response.
     */
    public string $timezone;

    /**
     * @var string The user's job title;maximum 60 characters.
    */
    public string $title;

    /**
     * @var string Determines the type of account that is associated with
     * this user. Allowed values are mailbox, forward, or filter. The default
     * type when creating a user is mailbox.
     *
     * The type value in turn restricts the delivery method that can be
     * specified. Mailbox accounts can have local and/or forward delivery,
     * forward accounts can have only forward delivery, and filter accounts
     * can have only filter delivery. Any incompatible delivery attributes
     * that are submitted will be ignored.
     */
    public string $type;

    /**
     * @var string The workgroup to which the user belongs.
    */
    public string $workgroup;
}