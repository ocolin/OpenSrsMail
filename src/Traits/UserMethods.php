<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Traits;

use GuzzleHttp\Exception\GuzzleException;

/**
 * This section contains the following methods:
 * change_user — Creates a new user or changes the attributes of an existing user.
 * delete_user — Soft deletes a user's account.
 * generate token — Generates a temporary login token for a user.
 * get_deleted_contacts — Retrieves a list of deleted restorable contacts
 * from a user's Webmail address book.
 * get_deleted_messages — Retrieves a list of recoverable deleted email messages belonging to a user.
 * get user — Retrieves the settings and values for a specified user.
 * get_user_attribute_history — Retrieves the historical values for an attribute for a specified user.
 * get_user_changes — Retrieves a summary of the changes made to a user account.
 * get_user_folders — Retrieves a list of a user's current and deleted folders.
 * get_user_messages — Returns a list of user messages in a specified folder.
 * logout_user — Terminates all IMAP and POP sessions that the specified user has active.
 * move_user_messages — Moves the specified user messages to a different folder.
 * reindex — Regenerates the specified mailbox index file.
 * rename_user — Changes a user's mailbox name.
 * restore_deleted_contacts — Restores deleted contacts for a specified user.
 * restore_deleted_messages — Restores specific deleted messages.
 * restore_user — Restores specified user accounts.
 * search_users — Searches for users in a specified domain.
 * set_role — Assigns a role to the specified user.
 * user_notify — Checks to see if the specified user has any unseen mail.
 */
trait UserMethods
{

/* CHANGE USER
----------------------------------------------------------------------------- */

    /**
     * The change_user method creates a new user or changes the attributes
     * of an existing user.
     *
     * @param string $user The user's mailbox name.
     * @param string[] $attributes The list of fields that you want to define
     * or modify and their new values.
     * @param bool $createOnly Used to prevent changes to existing accounts.
     * If set to true and the specified user exists, the account will not
     * be modified and an error will be returned.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function changeUser(
        string $user,
         array $attributes,
          bool $createOnly = false,

    ) : object
    {
        $params = [
            'user'       => $user,
            'attributes' => $attributes,
        ];

        if( $createOnly ) { $params['create_only'] = true; }

        return $this->request( method: 'change_user', params: $params );
    }



/* DELETE USER
----------------------------------------------------------------------------- */

    /**
     * The delete_user method soft deletes a user's account. Once a user is
     * deleted they will not be able to receive mail or access the system
     * in any way.
     *
     * @param string $user The user's mailbox name.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function deleteUser( string $user ) : object
    {
        return $this->request( method: 'delete_user', params: [ 'user' => $user ] );
    }



/* GENERATE TOKEN
----------------------------------------------------------------------------- */

    /**
     * The generate_token method generates a temporary login token for a user.
     * Tokens can be used in place of a password.
     *
     * @param string $user The user's mailbox name.
     * @param string $reason The reason that the token was generated.
     * @param ?int $duration The number of hours for which the token is
     * valid. If this value is not specified, the token is valid for 24 hours.
     * @param ?bool $oma If set to true, a session or sso type token may
     * also be used with the OMA. If type = oma, this parameter is ignored
     * @param ?string $token The token to add. If this value is not
     * submitted, a random token is generated.
     * @param string $type The type of token to generate.
     *      oma — A token that is good for OMA logins only.
     *      session — Valid until the duration of the token expires. Can be
     * used for mail services and as credentials for the OMA (if oma is true
     * in the request).
     *      sso - A single use token that becomes invalid after it is used
     * once. Can be used to log in to mail services (Webmail, IMAP, SMPT,
     * etc) and the oma (if oma is true in the request).
     * @return object API response object.
     * @throws GuzzleException
     */
    public function generateToken(
         string $user,
         string $reason,
           ?int $duration = null,
          ?bool $oma = null,
        ?string $token = null,
         string $type = 'session',
    ) : object
    {
        $params = [  'user' => $user, 'reason' => $reason ];
        $types = [ 'oma', 'session', 'sso' ];

        if( $duration !== null ) { $params['duration'] = $duration; }
        if( $oma !== null ) { $params['oma'] = $oma; }
        if( $token !== null ) { $params['token'] = $token; }
        if( in_array( needle: $type, haystack: $types ) ) {
            $params['type'] = $type;
        }


        return $this->request( method: 'generate_token', params: $params );
    }



/* GET DELETED CONTACTS
----------------------------------------------------------------------------- */

    /**
     * The get_deleted_contacts method retrieves a list of deleted restorable
     * contacts from a user's Webmail address book.
     *
     * @param string $user The user's mailbox name.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function getDeletedContacts( string $user ) : object
    {
        return $this->request(
            method: 'get_deleted_contacts', params: [ 'user' => $user ]
        );
    }



/* GET DELETED MESSAGES
----------------------------------------------------------------------------- */

    /**
     * The get_deleted_messages method retrieves a list of recoverable
     * deleted email messages belonging to a user.
     *
     * After they are deleted, email messages are retained for a period of time
     * and may be recovered. This method returns a list of recoverable messages,
     * including the message headers, so that a user can select which messages
     * they want recovered.
     *
     * @param string $user The user whose deleted messages you want to list.
     * @param ?string[] $headers Specify the headers that you want returned.
     * For a list of the available headers, see Response fields for
     * get_deleted_messages below. If not specified, all headers are returned.
     * @param ?string $folder Name of the folder to search. You can specify
     * deleted as well as current folders. If not specified, messages in all
     * folders will be returned.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function getDeletedMessages(
         string $user,
         ?array $headers = null,
        ?string $folder  = null
    ) : object
    {
        $params = [ 'user' => $user ];
        if( $folder !== null ) { $params['folder'] = $folder; }
        if( $headers !== null ) { $params['headers'] = $headers; }

        return $this->request( method: 'get_deleted_messages', params: $params );
    }



/* GET USER
----------------------------------------------------------------------------- */

    /**
     * The get_user method retrieves the settings and values for a specified user.
     *
     * If the specified user does not exist, you can create that user, and so
     * the response contains the settable_attributes and metadata options that
     * allow a UI client to populate a page of attributes and drop-down options.
     * The response also contains metadata defaults that would be assigned to
     * attributes that are not specified in the user creation request.
     *
     * @param string $user The user's email address.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function getUser( string $user ) : object
    {
        return $this->request( method: 'get_user', params: [ 'user' => $user ] );
    }



/* GET USER ATTRIBUTE HISTORY
----------------------------------------------------------------------------- */

    /**
     * The get_user_attribute_history method retrieves the historical
     * values for an attribute for a specified user.
     *
     * @param string $user The user's email address.
     * @param string $attribute The name of the attribute to query.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function getUserAttributeHistory(
        string $user,
        string $attribute
    ) : object
    {
        $params = [ 'user' => $user, 'attribute' => $attribute ];

        return $this->request(
            method: 'get_user_attribute_history', params: $params
        );
    }



/* GET USER CHANGES
----------------------------------------------------------------------------- */

    /**
     * The get_user_changes method retrieves a summary of the changes made
     * to a user account.
     *
     * @param string $user The name of the user's account.
     * @param ?int $rangeFirst The 0-based index of the first result to return.
     * @param ?int $rangeLimit The maximum number of results to return.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function getUserChanges(
        string $user,
          ?int $rangeFirst = null,
          ?int $rangeLimit = null,
    ) : object
    {
        $params = [ 'user' => $user ];
        if( $rangeFirst !== null ) { $params['range']['first'] = $rangeFirst; }
        if( $rangeLimit !== null ) { $params['range']['limit'] = $rangeLimit; }

        return $this->request( method: 'get_user_changes', params: $params );
    }



/* GET USER FOLDERS
----------------------------------------------------------------------------- */

    /**
     * The get_user_folders method retrieves a list of a user's current
     * and deleted folders.
     *
     * @param string $user The user's account name.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function getUserFolders( string $user ) : object
    {
        $params = [ 'user' => $user ];

        return $this->request( method: 'get_user_folders', params: $params );
    }



/* GET USER MESSAGES
----------------------------------------------------------------------------- */

    /**
     * The get_user_messages method returns a list of user messages in a
     * specified folder.
     *
     * @param string $user The user's account name.
     * @param ?string $folder The folder to search for messages. If not
     * specified, the INBOX folder is searched.
     * @param ?int $limit Specify the number of messages to return.
     * @param ?bool $recent Orders the results with the most recent listed
     * first. This setting only applies if limit is also specified.
     * @param ?bool $unseen Return a list of only those messages that have
     * been delivered to the user, but have not yet been displayed in the
     * user's mail client or through Webmail.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function getUserMessages(
         string $user,
        ?string $folder = null,
           ?int $limit  = null,
          ?bool $recent = null,
          ?bool $unseen = null,
    ) : object
    {
        $params = [ 'user' => $user ];
        if( $folder !== null ) { $params['folder'] = $folder; }
        if( $limit !== null ) { $params['limit'] = $limit; }
        if( $recent !== null ) { $params['recent'] = $recent; }
        if( $unseen !== null ) { $params['unseen'] = $unseen; }

        return $this->request( method: 'get_user_messages', params: $params );
    }



/* LOGOUT USER
----------------------------------------------------------------------------- */

    /**
     * The logout_user method terminates all IMAP and POP sessions that
     * the specified user has active.
     *
     * @param string $user The user's email address.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function logoutUser( string $user ) : object
    {
        $params = [ 'user' => $user ];

        return $this->request( method: 'logout_user', params: $params );
    }



/* MOVE USER MESSAGE
----------------------------------------------------------------------------- */

    /**
     * The move_user_messages method moves the specified user messages
     * to a different folder.
     *
     * @param string $user The user's account name.
     * @param array<string> $ids The list of ids that you want to move.
     * @param string|null $folder The folder to search for messages. If
     * not specified, the INBOX folder is searched.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function moveUserMessages(
        string $user,
         array $ids,
        ?string $folder = null,
    ) : object
    {
        $params = [ 'user' => $user, 'ids' => $ids ];
        if( $folder !== null ) { $params['folder'] = $folder; }

        return $this->request( method: 'move_user_messages', params: $params );
    }


/* REINDEX
----------------------------------------------------------------------------- */

    /**
     * The reindex method regenerates the specified mailbox index file.
     *
     * @param string $user The user whose account you want to reindex.
     * @param ?string $folder The folder that you want to reindex. If not
     * specified, all folders belonging to the user will be reindexed.
     * @param ?string $id The job to check status of. If id is present,
     * folder is ignored.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function reindex(
         string $user,
        ?string $folder = null,
        ?string $id     = null,
    ) : object
    {
        $params = [ 'user' => $user ];
        if( $folder !== null ) { $params['folder'] = $folder; }
        if( $id !== null ) { $params['id'] = $id; }

        return $this->request( method: 'reindex', params: $params );
    }



/* RENAME USER
----------------------------------------------------------------------------- */

    /**
     * The rename_user method changes a user's mailbox name; does not
     * affect any of the existing email or settings. The mailboxes must be
     * in the same domain.
     *
     * @param string $user The current name of the mailbox.
     * @param string $newName The new name for the mailbox.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function renameUser( string $user, string $newName ) : object
    {
        $params = [ 'user' => $user, 'new_name' => $newName ];

        return $this->request( method: 'rename_user', params: $params );
    }



/* RESTORE DELETED CONTACTS
----------------------------------------------------------------------------- */

    /**
     * The restore_deleted_contacts method restores deleted contacts for
     * a specified user.
     *
     * @param string $user The name of the mailbox.
     * @param array<string> $ids The IDs for the messages that you want to restore.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function restoreDeletedContacts( string $user, array $ids ) : object
    {
        $params = [ 'user' => $user, 'ids' => $ids ];

        return $this->request( method: 'restore_deleted_contacts', params: $params );
    }



/* RESTORE DELETED MESSAGES
----------------------------------------------------------------------------- */

    /**
     * The restore_deleted_messages method restores specific deleted messages.
     *
     * @param string $user The name of the mailbox.
     * @param array<string> $ids The IDs for the messages that you want to restore.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function restoreDeletedMessages( string $user, array $ids ) : object
    {
        $params = [ 'user' => $user, 'ids' => $ids ];

        return $this->request( method: 'restore_deleted_messages', params: $params );
    }



/* RESTORE USER
----------------------------------------------------------------------------- */

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
     * @param string $newName Rename the restored account.
     * @param string $id A unique ID that identifies the user. To get a
     * list of the accounts that can be restored.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function restoreUser(
        string $user,
        string $newName,
        string $id
    ) : object
    {
        $params = [ 'user' => $user, 'new_name' => $newName, 'id' => $id ];

        return $this->request( method: 'restore_user', params: $params );
    }



/* SEARCH USERS
----------------------------------------------------------------------------- */

    /**
     * The search_users method searches for users in a specified domain.
     * You must specify the domain to search, and you can submit other
     * criteria to narrow your search. Each additional criteria field
     * that you specify further narrows the search, and wildcard characters
     * are allowed.
     *
     * @param array<string, mixed> $criteria Narrows the results by restricting
     * the search to the specified fields and their values.
     * @param ?string[] $fields Additional fields to return. Allowed values
     * are: createtime, forward, lastlogin, status, type, and workgroup.
     * @param ?int $rangeFirst Specify the first user to return; the default is
     * the first result.
     * @param ?int $rangeLimit Specify the maximum number of users to return.
     * @param ?array<string, string> $sort Determines the way in which to sort
     * and display results.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function searchUsers(
         array $criteria,
        ?array $fields = null,
          ?int $rangeFirst = null,
          ?int $rangeLimit = null,
        ?array $sort = null,
    ) : object
    {
        $params = [ 'criteria' => $criteria ];
        if( $fields !== null ) { $params['fields'] = $fields; }
        if( $rangeFirst !== null ) { $params['range']['first'] = $rangeFirst; }
        if( $rangeLimit !== null ) { $params['range']['limit'] = $rangeLimit; }
        if( $sort !== null ) { $params['sort'] = $sort; }

        return $this->request( method: 'search_users', params: $params );
    }


/* SET ROLE
----------------------------------------------------------------------------- */

    /**
     * The set_role method assigns a role to the specified user, removing
     * any previous role. Roles give users administration rights over users,
     * domains, and so on.
     *
     * @param string $user The user to whom you are assigning a role.
     * @param string $role The name of the role. company, company_mail,
     * company_ro, company_token_only, company_view, domain, mail, workgroup
     * To remove a role, pass null or "" (empty string) for the role name.
     * @param string[]|string $object The object over which the user will
     * have administration rights. Companies, and domains are given by name.
     * Workgroups are given as "domain/workgroup". A user must be a member of
     * the object given. For example, a workgroup admin must be in the specified
     * workgroup; a domain admin must be in the specified domain.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function setRole(
              string $user,
              string $role,
        array|string $object
    ) : object
    {
        $params = [ 'user' => $user, 'role' => $role ];
        if( is_string($object) ) { $params['object'] = $object; }
        else { $params['objects'] = $object; }

        return $this->request( method: 'set_role', params: $params );
    }



/* UER NOTIFY
----------------------------------------------------------------------------- */

    /**
     * The user_notify method checks to see if the specified user has any
     * unseen mail in their Inbox.
     *
     * @param string $user The user's email address.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function userNotify( string $user ) : object
    {
        $params = [ 'user' => $user ];

        return $this->request( method: 'user_notify', params: $params );
    }


/* USER DISABLE 2 FACTOR AUTHENTICATION
----------------------------------------------------------------------------- */

    /**
     * The user_disable_2fa method turns off Two-Factor Authentication
     * for the given user.
     *
     * @param string $user The user's email address.
     * @return object
     * @throws GuzzleException
     */
    public function userDisable2fa( string $user ) : object
    {
        $params = [ 'user' => $user ];

        return $this->request( method: 'user_disable_2fa', params: $params );
    }


/* APP PASSWORD ADD/REMOVE
----------------------------------------------------------------------------- */

    /**
     * Adds or removes "App Passwords" for the given end user.
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
     * An App Password may be removed at any time (in the case of a lost
     * device); the other App Passwords will continue to function. If the
     * final App Password is removed, then the user's password will once
     * again work to authenticate with non-2FA services.
     *
     * Existing App Password tags are returned in the get_user response.
     * Once an App Password is set, there is no way to retrieve the value
     * of the password associated with it, only the tag.
     *
     * @param string $user The user the API call is acting upon.
     * @param string $mode A string, either "add" or "remove"
     * @param ?string $tag A string. for "add", the tag name desired
     * for this new App Password. tags are constrained to regular ASCII
     * characters only. for "remove", the tag name of the App Password
     * to remove.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function appPassword(
        string $user,
        string $mode,
        ?string $tag = null
    ) : object
    {
        $params = [ 'user' => $user, 'mode' => $mode ];
        if( $tag !== null ) { $params['tag'] = $tag; }

        return $this->request( method: 'app_password', params: $params );
    }


/* RESET EMAIL TEST
----------------------------------------------------------------------------- */

    /**
     * The reset_email_test method sends password reset notification emails
     * to the provided email address(es) for a specified brand. This allows
     * the reseller to experience the emails as they will be received by
     * the end user.
     *
     * @param string $testEmailRcpt Email ID where test emails will be sent.
     * @param string $brand Brand id or brand name. If brand name, then
     * company info must be specified
     * @param ?string $company Either a company id or company name. Must be
     * provided if using brand name
     * @return object API response object.
     * @throws GuzzleException
     */
    public function resetEmailTest(
         string $testEmailRcpt,
         string $brand,
        ?string $company = null,
    ) : object
    {
        $params = [ 'testEmailRcpt' => $testEmailRcpt, 'brand' => $brand ];
        if( $company !== null ) { $params['company'] = $company; }

        return $this->request( method: 'reset_email_test', params: $params );
    }



/* GET SIEVE
----------------------------------------------------------------------------- */

    /**
     * The get_sieve call retrieves all sieve rulesets for the user.
     *
     * Note that multiple rulesets can be defined but only one ruleset is
     * active at any given time. The default active sieve ruleset
     * name is "managesieve".
     *
     * @param string $user The user the API call is acting upon.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function getSieve( string $user ) : object
    {
        $params = [ 'user' => $user ];

        return $this->request( method: 'get_sieve', params: $params );
    }



/* SET SIEVE
----------------------------------------------------------------------------- */

    /**
     * Sets sieve rulesets and changes the active ruleset.
     *
     * WARNING: this API call is an experimental work in progress. Sieve
     * ruleset validation may not work correctly; it can be possible to set
     * rules that will not function properly.
     *
     * WARNING: this API call is able to set sieve rulesets that may be
     * incompatible with webmail. If you need the rules to be viewable and
     * editable inside webmail, set a few rules in webmail to understand
     * the syntax it uses, then copy that syntax and use only actions and
     * filters that webmail is able to support.
     *
     * @param string $user The user the API call is acting upon.
     * @param string $ruleset Ruleset to set.
     * @param ?string $data
     * @param ?string $activeRule
     * @return object API response object.
     * @throws GuzzleException
     */
    public function setSieve(
         string $user,
         string $ruleset,
        ?string $data = null,
        ?string $activeRule = null
    ) : object
    {
        $params = [ 'user' => $user, 'ruleset' => $ruleset ];
        if( $data !== null ) { $params['data'] = $data; }
        if( $activeRule !== null ) { $params['active_rule'] = $activeRule; }

        return $this->request( method: 'set_sieve', params: $params );
    }
}