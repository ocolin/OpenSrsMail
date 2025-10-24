<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail;

use GuzzleHttp\Exception\GuzzleException;
use Ocolin\OpenSrsMail\Types\SearchAdminCriteria;
use Ocolin\OpenSrsMail\Types\SearchCriteria;
use Ocolin\OpenSrsMail\Types\SearchRange;
use Ocolin\OpenSrsMail\Types\SearchSort;
use Ocolin\OpenSrsMail\Types\SearchUser AS SearchType;
use Ocolin\OpenSrsMail\Types\SearchAdmins AS SearchAdminsType;
use Ocolin\OpenSrsMail\Types\TokenPayload;

trait UserTrait
{

/* SEARCH ADMINISTRATORS
------------------------------------------------------------------------------ */

    /**
     * The search_admins method retrieves a list of the admins in a specified company.
     *
     * @param SearchAdminsType|array<string, mixed> $attributes Attributes
     *  to configure search.
     * @return object|null Object with search results or errors.
     * @throws GuzzleException
     */
    public function searchAdmins( SearchAdminsType|array $attributes = [] ) : object | null
    {
        $output = $this->call( call: 'search_admins', payload: $attributes );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* CHANGE USER
------------------------------------------------------------------------------ */

    /**
     * Creates a new user or changes the attributes of an existing user.
     *
     * @param string $user Mailbox name to add.
     * @param object|array<string, mixed> $attributes List of settings to set,
     *  including password.
     * @param bool $create_only Used to prevent changes to existing accounts. If
     *  set to true and the specified user exists, the account will not be modified
     *  and an error will be returned.
     * @return object|null A response object showing if change was successful.
     * @throws GuzzleException
     */
    public function changeUser(
              string $user,
        object|array $attributes,
                bool $create_only = false
    ) : object | null
    {
        $output = $this->call(
            call: 'change_user',
            payload: [
                'user'          => $user,
                'create_only'   => $create_only,
                'attributes'    => $attributes
            ],
        );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* DELETE USER
------------------------------------------------------------------------------ */

    /**
     * The delete_user method soft deletes a user's account. Once a user is
     * deleted they will not be able to receive mail or access the system in any way.
     *
     * @param string $user Mailbox to delete
     * @return object|null Response object or null if none.
     * @throws GuzzleException
     */
    public function deleteUser( string $user ) : object | null
    {
        $output = $this->call( call: 'delete_user', payload: [ 'user' => $user ] );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* GENERATE TOKEN
------------------------------------------------------------------------------ */

    /**
     * The generate_token method generates a temporary login token for a user.
     * Tokens can be used in place of a password.
     *
     * @param string $user The user's mailbox name.
     * @param string $reason The reason that the token was generated.
     * @param int $duration The number of hours for which the token is valid.
     * @param bool $oma If set to true, a session or sso type token may also be
     *  used with the OMA.
     * @param string|null $token The token to add.
     * @param string $type The type of token to generate.
     * @return object|null Response object
     * @throws GuzzleException
     */
    public function generateToken(
         string $user,
         string $reason,
            int $duration = 24,
           bool $oma = false,
        ?string $token = null,
         string $type = 'session',
    ) : object | null
    {
        $payload = new TokenPayload();
        $payload->credentials = $this->credentials;
        $payload->user = $user;
        $payload->reason = $reason;
        $payload->duration = $duration;
        $payload->oma = $oma;
        if( $token !== null ) { $payload->token = $token; }
        $payload->type = $type;

        $output = $this->call( call: 'generate_token', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* GET DELETE CONTACTS
------------------------------------------------------------------------------ */

    /**
     * The get_deleted_contacts method retrieves a list of deleted restorable
     * contacts from a user's Webmail address book.
     *
     * @param string $user Mailbox to query.
     * @return object|null Query results.
     * @throws GuzzleException
     */
    public function getDeletedContacts( string $user ) : object | null
    {
        $output = $this->call( call: 'get_deleted_contacts', payload: [ 'user' => $user ] );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* GET DELETED MESSAGES
------------------------------------------------------------------------------ */

    /**
     * The get_deleted_messages method retrieves a list of recoverable deleted
     * email messages belonging to a user.
     *
     * After they are deleted, email messages are retained for a period of
     * time and may be recovered. This method returns a list of recoverable
     * messages, including the message headers, so that a user can select which
     * messages they want recovered.
     *
     * @param string $user The user whose deleted messages you want to list.
     * @param string|null $folder Name of the folder to search. You can specify
     *  deleted as well as current folders. If not specified, messages in all
     *  folders will be returned.
     * @param string[] $headers Specify the headers that you want returned.
     * @return object|null
     * @throws GuzzleException
     */
    public function getDeletedMessages(
         string $user,
        ?string $folder = null,
          array $headers = []
    ) : object | null
    {
        $output = $this->call(
               call: 'get_deleted_messages',
            payload: [
                'user'    => $user,
                'folder'  => $folder,
                'headers' => $headers
            ]
        );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* GET USER
------------------------------------------------------------------------------ */

    /**
     * The get_user method retrieves the settings and values for a specified user.
     *
     * If the specified user does not exist, you can create that user, and so
     * the response contains the settable_attributes and metadata options that
     * allow a UI client to populate a page of attributes and drop-down options.
     * The response also contains metadata defaults that would be assigned to
     * attributes that are not specified in the user creation request.
     *
     *
     * @param string $user User mailbox to query.
     * @return object|null Uer data or null if not found.
     * @throws GuzzleException
     */
    public function getUser( string $user ) : object | null
    {
        $output = $this->call( call: 'get_user', payload: [ 'user' => $user ] );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* GET USER ATTRIBUTE HISTORY
------------------------------------------------------------------------------ */

    /**
     * The get_user_attribute_history method retrieves the historical values
     * for an attribute for a specified user.
     *
     * @param string $user The user's email address.
     * @param string $attribute The name of the attribute to query.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function getUserAttributeHistory(
        string $user, string $attribute
    ) : object | null
    {
        $output = $this->call(
            call: 'get_user',
            payload: [ 'user' => $user, 'attribute' => $attribute ]
        );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* GET USER CHANGES
------------------------------------------------------------------------------ */

    /**
     * The get_user_changes method retrieves a summary of the changes made
     * to a user account.

     * @param string $user The name of the user's account.
     * @param int|null $first The 0-based index of the first result to return.
     * @param int|null $limit The maximum number of results to return.
     * @return object|null
     * @throws GuzzleException
     */
    public function getUserChanges(
        string $user,
          ?int $first = null,
          ?int $limit = null,
    ) : object | null
    {
        $payload = [ 'user' => $user ];
        $payload['range'] = new SearchRange();
        if( $first !== null ) { $payload['range']->first = $first; }
        if( $limit !== null ) { $payload['range']->limit = $limit; }

        $output = $this->call( call: 'get_user_changes', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* GET USER FOLDERS
------------------------------------------------------------------------------ */

    /**
     * The get_user_folders method retrieves a list of a user's current and
     * deleted folders.
     *
     * @param string $user The name of the user's account.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function getUserFolders( string $user ) : object | null
    {
        $output = $this->call( call: 'get_user_folders', payload: [ 'user' => $user ] );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* GET USER MESSAGES
------------------------------------------------------------------------------ */

    /**
     * The get_user_messages method returns a list of user messages in a
     * specified folder.
     *
     * @param string $user The user's account name.
     * @param string|null $folder The folder to search for messages. If not
     *  specified, the INBOX folder is searched.
     * @param int|null $limit Specify the number of messages to return.
     * @param bool $recent Orders the results with the most recent listed first.
     *  This setting only applies if limit is also specified.
     * @param bool $unseen Return a list of only those messages that have been
     *  delivered to the user, but have not yet been displayed in the user's mail
     *  client or through Webmail.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function getUserMessages(
         string $user,
        ?string $folder = null,
           ?int $limit  = null,
           bool $recent = false,
           bool $unseen = false,
    ) : object | null
    {
        $payload = [ 'user' => $user, 'folder' => $folder, 'limit' => $limit ];
        if( $folder !== null ) { $payload['folder'] = $folder; }
        if( $limit  !== null ) { $payload['limit'] = $limit; }

        $output = $this->call( call: 'get_user_messages', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* LOGOUT USER
------------------------------------------------------------------------------ */

    /**
     * The logout_user method terminates all IMAP and POP sessions that
     * the specified user has active.
     *
     * @param string $user The user's email address.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function logoutUser( string $user ) : object | null
    {
        $output = $this->call( call: 'logout_user', payload: [ 'user' => $user ] );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* MOVE USER MESSAGES
------------------------------------------------------------------------------ */

    /**
     * The move_user_messages method moves the specified user messages to
     * a different folder.
     *
     * @param string $user The user's account name.
     * @param int[] $ids The list of ids that you want to move. The ids are
     *  returned by the get_user_messages method.
     * @param string|null $folder The folder to search for messages. If
     *  not specified, the INBOX folder is searched.
     * @return object|null
     * @throws GuzzleException
     */
    public function moveUserMessages(
        string $user,
         array $ids,
        ?string $folder = null
    ) : object | null
    {
        $payload = [ 'user' => $user, 'ids' => $ids ];
        if( $folder !== null ) { $payload['folder'] = $folder; }

        $output = $this->call( call: 'move_user_messages', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* REINDEX
------------------------------------------------------------------------------ */

    /**
     * The reindex method regenerates the specified mailbox index file.
     *
     * @param string $user The user whose account you want to reindex.
     * @param string|null $folder The folder that you want to reindex. If not
     *  specified, all folders belonging to the user will be reindexed.
     * @param string|null $id The job to check status of. If id is present,
     *  folder is ignored.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function reindex(
         string $user,
        ?string $folder = null,
        ?string $id = null,
    ) : object | null
    {
        $payload = [ 'user' => $user ];
        if( $folder !== null ) { $payload['folder'] = $folder; }
        if( $id !== null ) { $payload['id'] = $id; }

        $output = $this->call( call: 'reindex', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* RENAME USER
------------------------------------------------------------------------------ */

    /**
     * The rename_user method changes a user's mailbox name; does not affect any
     * of the existing email or settings. The mailboxes must be in the same domain.
     *
     * @param string $user The current name of the mailbox.
     * @param string $new_name The new name for the mailbox.
     * @return object|null Response object
     * @throws GuzzleException
     */
    public function renameUser( string $user, string $new_name ) : object | null
    {
        $output = $this->call(
               call: 'rename_user',
            payload: [ 'user' => $user, 'new_name' => $new_name ]
        );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* RESTORE DELETED CONTACTS
------------------------------------------------------------------------------ */

    /**
     * The restore_deleted_contacts method restores deleted contacts for
     * a specified user.
     *
     * @param string $user The name of the mailbox.
     * @param string[] $ids The IDs for the contact that you want to restore. To
     *  get a list of the contact that can be restored, use the get_deleted_
     *  contacts method.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function restoreDeletedContacts(
        string $user,
         array $ids,
    ) : object | null
    {
        $output = $this->call(
            call: 'restore_deleted_contacts',
            payload: [ 'user' => $user, 'ids' => $ids ]
        );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* RESTORE DELETED MESSAGES
------------------------------------------------------------------------------ */

    /**
     * The restore_deleted_messages method restores specific deleted messages.
     *
     * @param string $user The name of the mailbox.
     * @param int[] $ids The IDs for the messages that you want to restore. To
     *  get a list of the messages that can be restored, use the
     *  get_deleted_messages method.
     * @return object|null
     * @throws GuzzleException
     */
    public function restoreDeletedMessages(
        string $user,
         array $ids
    ) : object | null
    {
        $output = $this->call(
            call: 'restore_deleted_messages',
            payload: [ 'user' => $user, 'ids' => $ids ]
        );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* RESTORE USER
------------------------------------------------------------------------------ */

    /**
     * The restore_user method restores specified user accounts that have
     * been deleted for 30 days or less.
     *
     * You can restore an account to its original name only if that account
     * name has not been reissued during the period in which the account was
     * deleted. Otherwise, you can restore the account to any name that is
     * available.
     *
     * @param string $user The deleted user's account name (email address).
     * @param int $id A unique ID that identifies the user. To get a list
     *  of the accounts that can be restored, use the search_users method.
     * @param string $new_name Rename the restored account.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function restoreUser(
        string $user,
           int $id,
        string $new_name,
    ) : object | null
    {
        $output = $this->call(
            call: 'restore_user',
            payload: [
                'user' => $user, 'id' => $id, 'new_name' => $new_name
            ]
        );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* SEARCH USERS
------------------------------------------------------------------------------ */

    /**
     * The search_users method searches for users in a specified domain. You
     * must specify the domain to search, and you can submit other criteria to
     * narrow your search. Each additional criteria field that you specify
     * further narrows the search, and wildcard characters are allowed.
     *
     * @param string $domain
     * @param SearchType|null $attributes
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function searchUsers(
             string $domain,
        ?SearchType $attributes = null
    ) : object | null
    {
        if( $attributes === null ) {
            $attributes = self::search_Object();
        }
        $attributes->criteria->domain = $domain;

        $output = $this->call( call: 'search_users', payload: $attributes );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* SET ROLE
------------------------------------------------------------------------------ */

    /**
     * The set_role method assigns a role to the specified user, removing
     * any previous role. Roles give users administration rights over users,
     * domains, and so on.
     *
     * @param string $user The user to whom you are assigning a role.
     * @param string $role The name of the role. Allowed values are:
     *  company, company_mail, company_ro, company_token_only, company_view,
     *  domain, mail, and workgroup.
     * @param string $object The object over which the user will have
     *  administration rights. Companies, and domains are given by name. Workgroups
     *  are given as "domain/workgroup". A user must be a member of the object
     *  given. For example, a workgroup admin must be in the specified workgroup;
     *  a domain admin must be in the specified domain.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function setRole(
        string $user,
        string $role,
        string $object
    ) : object | null
    {
        $payload = [ 'user' => $user, 'role' => $role, 'object' => $object ];
        $output = $this->call( call: 'set_role', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* NOTIFY USER
------------------------------------------------------------------------------ */

    /**
     * The user_notify method checks to see if the specified user has any
     * unseen mail in their Inbox.
     *
     * @param string $user Account name.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function notifyUser( string $user ) : object | null
    {
        $output = $this->call( call: 'notify_user', payload: [ 'user' => $user ] );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* USER DISABLE 2 FACTOR AUTH
------------------------------------------------------------------------------ */

    /**
     * The user_disable_2fa method turns off Two-Factor Authentication for
     * the given user.
     *
     * @param string $user
     * @return object|null
     * @throws GuzzleException
     */
    public function userDisable2fa( string $user ) : object | null
    {
        $output = $this->call( call: 'user_disable2fa', payload: [ 'user' => $user ] );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* APP PASSWORD
------------------------------------------------------------------------------ */

    /**
     *  Adds or removes "App Passwords" for the given end user.
     *
     * "App Passwords" are used in lieu of an account's regular password when
     * authenticating to services that cannot support Two-Factor Authentication
     * (POP3, IMAP4, SMTP, CalDAV, CardDAV). Once one has been set on a user
     * account, their password will be unable to authenticate against those
     * services; they must use one of the App Passwords instead.
     *
     * Each App Password has a tag (name) associated with it. Multiple
     * devices/clients may use the same App Password, or they may be configured
     * to each use a different one. Up to 4 App Passwords may be configured.
     *
     * An App Password may be removed at any time (in the case of a lost device);
     * the other App Passwords will continue to function. If the final App
     * Password is removed, then the user's password will once again work to
     * authenticate with non-2FA services.
     *
     * Existing App Password tags are returned in the get_user response. Once
     * an App Password is set, there is no way to retrieve the value of the
     * password associated with it, only the tag.
     *
     * @param string $user the user the API call is acting upon.
     * @param string $mode a string, either "add" or "remove".
     * @param string|null $tag a string. for "add", the tag name desired for
     *  this new App Password. tags are constrained to regular ASCII characters
     *  only. For "remove", the tag name of the App Password to remove.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function appPassword(
         string $user,
         string $mode,
        ?string $tag = null
    ) : object | null
    {
        $payload = [ 'user' => $user, 'mode' => $mode ];
        if( $tag !== null ) { $payload['tag'] = $tag; }

        $output = $this->call( call: 'app_password', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* RESET EMAIL TEST
------------------------------------------------------------------------------ */

    /**
     * The reset_email_test method sends password reset notification emails to
     * the provided email address(es) for a specified brand. This allows the
     * reseller to experience the emails as they will be received by the end user.
     *
     * @param string $test_email_rcpt Email ID where test emails will be sent
     * @param string $brand brand id or brand name. If brand name, then company
     *  info must be specified
     * @param string|null $company Either a company id or company name. Must be
     *  provided if using brand name
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function resetEmailTest(
         string $test_email_rcpt,
         string $brand,
        ?string $company = null,
    ) : object | null
    {
        $payload = [ 'test_email' => $test_email_rcpt, 'brand' => $brand ];
        if( $company !== null ) { $payload['company'] = $company; }
        $output = $this->call( call: 'reset_email', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* GET SIEVE
------------------------------------------------------------------------------ */

    /**
     * The get_sieve call retrieves all sieve rulesets for the user.
     *
     * Note that multiple rulesets can be defined but only one ruleset is active
     * at any given time. The default active sieve ruleset name is "managesieve".
     *
     * @param string $user
     * @return object|null
     * @throws GuzzleException
     */
    public function getSieve( string $user ) : object | null
    {
        $output = $this->call( call: 'get_sieve', payload: [ 'user' => $user ] );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* SET SIEVE
------------------------------------------------------------------------------ */

    /**
     * Sets sieve rulesets and changes the active ruleset.
     *
     * WARNING: this API call is an experimental work in progress. Sieve ruleset
     * validation may not work correctly; it can be possible to set rules that
     * will not function properly.
     *
     * WARNING: this API call is able to set sieve rulesets that may be incompatible
     * with webmail. If you need the rules to be viewable and editable inside webmail,
     * set a few rules in webmail to understand the syntax it uses, then copy that
     * syntax and use only actions and filters that webmail is able to support.
     *
     * If you are creating a new ruleset for a mailbox that has not previously been
     * using sieve, make sure to set "active_rule".
     *
     * If sieve ruleset validation fails, the response should contain a "hints"
     * field with additional details.
     *
     * @param string $user Mailbox to set rules on.
     * @param string $ruleset The ruleset name. Use only ASCII characters.
     * @param string|null $data The content of the sieve ruleset, which can include
     * multiple sieve rules.
     * @param string|null $active_rule Name of active rule.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function setSieve(
         string $user,
         string $ruleset,
        ?string $data = null,
        ?string $active_rule = null
    ) : object | null
    {
        $payload = [ 'user' => $user, 'ruleset' => $ruleset ];
        if( $data !== null ) { $payload['data'] = $data; }
        if( $active_rule !== null ) { $payload['active_rule'] = $active_rule; }
        $output = $this->call( call: 'set_sieve', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* CREATE SEARCH OBJECT
------------------------------------------------------------------------------ */

    /**
     * @return SearchType Return an empty object with search data.
     */
    public static function search_Object() : SearchType
    {
        $search = new SearchType();
        $search->criteria = new SearchCriteria();
        $search->range = new SearchRange();
        $search->sort = new SearchSort();

        return $search;
    }



/* CREATE SEARCH ADMIN OBJECT
------------------------------------------------------------------------------ */

    /**
     * @return SearchAdminsType A search admins object.
     */
    public static function search_Admin_Object() : SearchAdminsType
    {
        $search = new SearchAdminsType();
        $search->criteria = new SearchAdminCriteria();
        $search->range = new SearchRange();

        return $search;
    }
}
