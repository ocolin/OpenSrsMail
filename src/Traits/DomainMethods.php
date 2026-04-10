<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Traits;

use GuzzleHttp\Exception\GuzzleException;

/**
 * This section contains the following methods:
 * changeDomain — Creates a new domain or modifies the attributes of an existing domain.
 * changeDomainBulletin — Creates, edits, or deletes a domain bulletin.
 * deleteDomain — Deletes a domain.
 * getDomain — Retrieves the settings and information for a specified domain.
 * getDomainBulletin — Returns the text of a specified bulletin.
 * getDomainChanges — Retrieves a summary of the changes that have been made to a domain.
 * postDomainBulletin — Sends a specified bulletin to all users in a domain.
 * restoreDomain — Restores a deleted domain.
 * searchDomains — Retrieves a list of domains in a company.
 */

trait DomainMethods
{

/* CHANGE DOMAIN
----------------------------------------------------------------------------- */

    /**
     * The change_domain method creates a new domain or modifies the
     * attributes of an existing domain.
     *
     * @param string $domain The domain that you want to create or change.
     * @param array<string, mixed>|object $attributes The list of fields
     * that you want to configure and their values.
     * @param ?bool $createOnly Used to prevent changes to existing domains.
     * If set to true and the specified domain exists, the domain will
     * not be modified and an error will be returned.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function changeDomain(
              string $domain,
        array|object $attributes,
               ?bool $createOnly = null
    ) : object
    {
        $params = [ 'domain' => $domain, 'attributes' => $attributes ];
        if( $createOnly !== null ) { $params['create_only'] = $createOnly ; }

        return $this->request( method: 'change_domain', params: $params );
    }



/* CHANGE DOMAIN BULLETIN
----------------------------------------------------------------------------- */

    /**
     * The change_domain_bulletin method allows you to create, edit, or
     * delete a domain bulletin.
     *
     * If a bulletin with the specified name and type does not exist,
     * that bulletin is created. If a bulletin with the specified name and type
     * exists and bulletin_text is submitted in the method, the existing
     * bulletin is changed. If a bulletin with the specified name and type exists
     * and bulletin_text is not submitted, the existing bulletin is deleted.
     *
     * @param string $domain The domain to which the bulletin applies.
     * @param string $bulletin The name of the bulletin you want to create,
     * edit, or delete.
     * @param string $type The bulletin delivery method. Allowed values are:
     *      auto — Bulletin is automatically sent to newly created users
     *      manual — Bulletins are only sent via the post_domain_bulletin method.
     * @param ?string $bulletinText The text of the bulletin. Bulletins must be
     * formatted as a raw email. If the bulletin_text does not contain a Date
     * header, a header will be appended, with the date that the bulletin was posted.
     *      {'account'} — The recipient's mailbox address (joe_user@example.com).
     *      {'domain'} — The domain to which the user belongs (example.com).
     *      {'name'} — The recipient's name.
     *      {'title'} — The recipient's title.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function changeDomainBulletin(
         string $domain,
         string $bulletin,
         string $type,
        ?string $bulletinText = null,
    ) : object
    {
        $params = [ 'domain' => $domain, 'bulletin' => $bulletin , 'type' => $type ];
        if( $bulletinText !== null ) { $params['bulletin_text'] = $bulletinText; }

        return $this->request( method: 'change_domain_bulletin', params: $params );
    }



/* DELETE DOMAIN
----------------------------------------------------------------------------- */

    /**
     * The delete_domain method deletes a domain. The domain must not
     * have any users in it.
     *
     * @param string $domain The name of the domain that you want to delete.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function deleteDomain( string $domain ) : object
    {
        $params = [ 'domain' => $domain ];

        return $this->request( method: 'delete_domain', params: $params );
    }



/* GET DOMAIN
----------------------------------------------------------------------------- */

    /**
     * The get_domain method retrieves the settings and information for
     * a specified domain.
     *
     * @param string $domain The domain whose settings you want to view.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function getDomain( string $domain ) : object
    {
        $params = [ 'domain' => $domain ];

        return $this->request( method: 'get_domain', params: $params );
    }



/* GET DOMAIN BULLETIN
----------------------------------------------------------------------------- */

    /**
     * The get_domain_bulletin method returns the text of a specified bulletin.
     *
     * @param string $domain The name of the bulletin you want to view.
     * @param string $bulletin The name of the bulletin you want to view.
     * @param string $type The type of bulletin. Allowed values are:
     *      auto — Bulletin is automatically sent to newly created users
     *      manual — Bulletins are only sent via the post_domain_bulletin method.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function getDomainBulletin(
        string $domain,
        string $bulletin,
        string $type,
    ) : object
    {
        $params = [ 'domain' => $domain, 'bulletin' => $bulletin , 'type' => $type ];

        return $this->request( method: 'get_domain_bulletin', params: $params );
    }



/* GET DOMAIN CHANGES
----------------------------------------------------------------------------- */

    /**
     * @param string $domain The name of the domain.
     * @param ?int $rangeFirst The 0-based index of the first result to return.
     * @param ?int $rangeLimit The maximum number of results to return.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function getDomainChanges(
        string $domain,
          ?int $rangeFirst = null,
          ?int $rangeLimit = null,
    ) : object
    {
        $params = [ 'domain' => $domain ];
        if( $rangeFirst !== null ) { $params['range']['first'] = $rangeFirst; }
        if( $rangeLimit !== null ) { $params['range']['limit'] = $rangeLimit; }

        return $this->request( method: 'get_domain_changes', params: $params );
    }



/* POST DOMAIN BULLETIN
----------------------------------------------------------------------------- */

    /**
     * The post_domain_bulletin method sends a specified bulletin to all
     * users in a domain.
     *
     * @param string $domain The name of the domain.
     * @param string $bulletin The name of the bulletin you want to view.
     * @param string $type The bulletin type. Allowed values are:
     *      auto — The bulletin is automatically sent to new users when their
     * accounts are created.
     *      manual — The bulletin is sent only when the post_domain_bulletin
     * method is run.
     * @param ?string $testEmail Send the bulletin to only the specified
     * email address. If not specified, the bulletin is sent to all
     * mailboxes in the domain.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function postDomainBulletin(
         string $domain,
         string $bulletin,
         string $type,
        ?string $testEmail = null,
    ) : object
    {
        $params = [ 'domain' => $domain, 'bulletin' => $bulletin , 'type' => $type ];
        if( $testEmail !== null ) { $params['test_email'] = $testEmail; }

        return $this->request( method: 'post_domain_bulletin', params: $params );
    }



/* RESTORE DOMAIN
----------------------------------------------------------------------------- */

    /**
     *  The restore_domain method restores a deleted domain.
     *
     * @param string $domain The current name of the domain you want to restore.
     * @param string $newName The new name for the domain. This value must be
     * submitted, but can be the same as domain (the original domain name).
     * @param string|int $id The domain id. This value can be retrieved by using
     * the search_domains method with the deleted field set to true.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function restoreDomain(
            string $domain,
            string $newName,
        string|int $id
    ) : object
    {
        $params = [ 'domain' => $domain, 'id' => $id, 'new_name' => $newName ];

        return $this->request( method: 'restore_domain', params: $params );
    }



/* SEARCH DOMAINS
----------------------------------------------------------------------------- */

    /**
     * The search_domains method retrieves a list of domains in a company.
     *
     * @param array<string, mixed>|object|null $criteria Narrows the results
     * by restricting the search to the specified fields and their values.
     * @param array<string, mixed>|object|null $sort Determines the way in
     * which to sort and display results.
     *      by — Specify the attribute to use to sort results. Allowed values are:
     *      delete_time — The time the domain was deleted. Can be used only if
     * criteria = deleted.
     *      domain — The domain name (this is the default).
     *      id — The identification number of the domain.
     *      type — The domain type: domain or alias.
     *      users — The number of users in the domain. You can refine this to
     * specify the number of users of a certain mailbox type by using one of
     * the following: users/alias, users/deleted, users/filter, users/forward,
     * or users/mailbox.
     *      direction — Specify the sort order. Allowed values are ascending
     * (this is the default) or descending.
     * @param ?int $rangeFirst Specify the first domain to return; the
     * default is the first result.
     * @param ?int $rangeLimit Specify the maximum number of results to return.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function searchDomains(
        array|object|null $criteria   = null,
        array|object|null $sort       = null,
                     ?int $rangeFirst = null,
                     ?int $rangeLimit = null,
    ) : object
    {
        $params = [];
        if( $criteria !== null )   { $params['criteria'] = $criteria ; }
        if( $sort !== null )       { $params['sort'] = $sort ; }
        if( $rangeFirst !== null ) { $params['range']['first'] = $rangeFirst; }
        if( $rangeLimit !== null ) { $params['range']['limit'] = $rangeLimit; }

        return $this->request( method: 'search_domains', params: $params );
    }



/* PUSH DOMAIN
----------------------------------------------------------------------------- */

    /**
     * The push_domain method transfers a domain and all its mailboxes and
     * mail to another company. The receiving company must first allow
     * domains to be pushed from this company by adding this company's
     * company id to that company's domain_push_allowed attribute.
     *
     * @param string $domain Domain to transfer to the new company
     * @param string $newCompany company_id of the company that will
     * receive the domain and mailboxes.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function pushDomain( string $domain, string $newCompany ) : object
    {
        $params = [ 'domain' => $domain, 'new_company' => $newCompany ];

        return $this->request( method: 'push_domain', params: $params );
    }
}