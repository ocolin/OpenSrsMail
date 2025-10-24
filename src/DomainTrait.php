<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail;

use GuzzleHttp\Exception\GuzzleException;
use Ocolin\OpenSrsMail\Types\DomainAttributes;
use Ocolin\OpenSrsMail\Types\DomainSearch;
use Ocolin\OpenSrsMail\Types\SearchRange;
use Ocolin\OpenSrsMail\Types\DomainCriteria;
use Ocolin\OpenSrsMail\Types\SearchSort;

trait DomainTrait
{

/*
------------------------------------------------------------------------------ */

    /**
     * The change_domain method creates a new domain or modifies the
     * attributes of an existing domain.
     *
     * @param string $domain The domain that you want to create or change.
     * @param DomainAttributes|array<string, mixed> $attributes The list of fields
     * that you want to configure and their values.
     * @param bool $create_only Used to prevent changes to existing domains. If
     * set to true and the specified domain exists, the domain will not be modified
     * and an error will be returned.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function changeDomain(
                        string $domain,
        DomainAttributes|array $attributes = [],
                          bool $create_only = false
    ) : object | null
    {
        $payload = [
            'domain'      => $domain,
            'attributes'  => $attributes,
            'create_only' => $create_only
        ];

        $output = $this->call( call: 'change_domain', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/*
------------------------------------------------------------------------------ */

    /**
     * The change_domain_bulletin method allows you to create, edit, or
     * delete a domain bulletin.
     *
     * If a bulletin with the specified name and type does not exist, that
     * bulletin is created. If a bulletin with the specified name and type exists
     * and bulletin_text is submitted in the method, the existing bulletin is
     * changed. If a bulletin with the specified name and type exists and
     * bulletin_text is not submitted, the existing bulletin is deleted.
     *
     * @param string $domain The domain to which the bulletin applies.
     * @param string $bulletin The name of the bulletin you want to create, edit, or delete.
     *
     * Note: All bulletins in a domain must have a unique type and name. That is,
     * it is possible for an autobulletin and a manual** bulletin to have the same
     * name, but not for two auto bulletins to hav the same name.
     * @param string $type The bulletin delivery method. Allowed values are:
     * auto — Bulletin is automatically sent to newly created users
     * manual — Bulletins are only sent via the post_domain_bulletin method.
     * @param string|null $bulletin_text The text of the bulletin. Bulletins must
     * be formatted as a raw email. If the bulletin_text does not contain a
     * Date header, a header will be appended, with the date that the bulletin
     * was posted.
     *
     * The text can include the following variables:
     * {'account'} — The recipient's mailbox address (joe_user@example.com).
     * {'domain'} — The domain to which the user belongs (example.com).
     * {'name'} — The recipient's name.
     * {'title'} — The recipient's title.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function changeDomainBulletin(
         string $domain,
         string $bulletin,
         string $type,
        ?string $bulletin_text = null
    ) : object | null
    {
        $payload = [
            'bulletin' => $bulletin, 'type' => $type, 'bulletin_text' => $bulletin_text
        ];
        if( $bulletin_text === null ) { $payload['bulletin_text'] = $bulletin; }

        $output = $this->call( call: 'change_domain_bulletin', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/*
------------------------------------------------------------------------------ */

    /**
     * The delete_domain method deletes a domain. The domain must not have
     * any users in it.
     *
     * @param string $domain The name of the domain that you want to delete.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function deleteDomain( string $domain ) : object | null
    {
        $output = $this->call(
            call: 'delete_domain', payload: [ 'domain' => $domain ]
        );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/*
------------------------------------------------------------------------------ */

    /**
     * The get_domain method retrieves the settings and information for a
     * specified domain.
     *
     * @param string $domain The domain whose settings you want to view.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function getDomain( string $domain ) : object | null
    {
        $output = $this->call( call: 'get_domain', payload: [ 'domain' => $domain ] );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/*
------------------------------------------------------------------------------ */

    /**
     * The get_domain_bulletin method returns the text of a specified bulletin.
     *
     * @param string $domain The name of the domain the bulletin is in.
     * @param string $bulletin The name of the bulletin you want to view.
     * @param string $type The type of bulletin. Allowed values are:
     * auto — Bulletin is automatically sent to newly created users
     * manual — Bulletins are only sent via the post_domain_bulletin method.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function getDomainBulletin(
        string $domain,
        string $bulletin,
        string $type,
    ) : object | null
    {
        $payload = [ 'bulletin' => $bulletin, 'type' => $type, 'bulletin_text' => $bulletin ];
        $output = $this->call( call: 'get_domain_bulletin', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/*
------------------------------------------------------------------------------ */

    /**
     * The get_domain_changes method retrieves a summary of the changes that
     * have been made to a domain.
     *
     * @param string $domain The name of the domain.
     * @param int|null $first The 0-based index of the first result to return.
     * @param int|null $limit The maximum number of results to return.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function getDomainChanges(
        string $domain,
        ?int $first = null,
        ?int $limit = null,
    ) : object | null
    {
        $payload = [ 'domain' => $domain, 'range' => new SearchRange() ];
        if( $first !== null ) { $payload['range']->first = $first; }
        if( $limit !== null ) { $payload['range']->limit = $limit; }

        $output = $this->call( call: 'get_domain_changes', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/*
------------------------------------------------------------------------------ */

    /**
     * The post_domain_bulletin method sends a specified bulletin to all
     * users in a domain.
     *
     * @param string $domain The name of the domain.
     * @param string $bulletin The name of the bulletin you want to view.
     * @param string $type The bulletin type. Allowed values are:
     * auto — The bulletin is automatically sent to new users when their accounts are created.
     * manual — The bulletin is sent only when the post_domain_bulletin method is run.
     * @param string|null $test_email Send the bulletin to only the specified
     * email address. If not specified, the bulletin is sent to all mailboxes
     * in the domain.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function postDomainBulletin(
         string $domain,
         string $bulletin,
         string $type,
        ?string $test_email = null,
    ) : object | null
    {
        $payload = [ 'domain' => $domain, 'bulletin' => $bulletin, 'type' => $type ];
        if( $test_email !== null ) { $payload['test_email'] = $test_email; }
        $output = $this->call( call: 'post_domain_bulletin', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/*
------------------------------------------------------------------------------ */

    /**
     * The restore_domain method restores a deleted domain.
     *
     * @param string $domain The current name of the domain you want to restore.
     * @param string $id The domain id. This value can be retrieved by using
     * the search_domains method with the deleted field set to true.
     * @param string $new_name The new name for the domain. This value must be
     * submitted, but can be the same as domain (the original domain name).
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function restoreDomain(
        string $domain,
        string $id,
        string $new_name
    ) : object | null
    {
        $payload = [ 'domain' => $domain, 'id' => $id, 'new_name' => $new_name ];
        $output = $this->call( call: 'restore_domain', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/*
------------------------------------------------------------------------------ */

    /**
     * The search_domains method retrieves a list of domains in a company.
     *
     * @param DomainSearch|array<string, mixed> $search Search parameters.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function searchDomain(
        DomainSearch|array $search = []
    ) : object | null
    {
        $output = $this->call( call: 'search_domain', payload: $search );
        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/*
------------------------------------------------------------------------------ */

    /**
     * The push_domain method transfers a domain and all its mailboxes and mail
     * to another company. The receiving company must first allow domains to be
     * pushed from this company by adding this company's company id to that
     * company's domain_push_allowed attribute.
     *
     * @param string $domain Domain to transfer to the new company.
     * @param string $new_company company_id of the company that will receive the
     * domain and mailboxes.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function pushDomain( string $domain, string $new_company ) : object | null
    {
        $payload = [ 'domain' => $domain, 'new_company' => $new_company ];
        $output = $this->call( call: 'push_domain', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/*
------------------------------------------------------------------------------ */

    /**
     * Use this to create an empty search object to populate with
     * custom search parameters.
     *
     * @return DomainSearch Empty Domain search object.
     */
    public static function createSearchObject() : DomainSearch
    {
        $search = new DomainSearch();
        $search->criteria = new DomainCriteria();
        $search->range = new SearchRange();
        $search->sort = new SearchSort();

        return $search;
    }
}