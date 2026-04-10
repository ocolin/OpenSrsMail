<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Traits;

use GuzzleHttp\Exception\GuzzleException;

/**
 * This section contains the following methods:
 * createWorkgroup — Creates a new workgroup in a specified domain.
 * deleteWorkgroup — Deletes a specified workgroup.
 * searchWorkgroups — Retrieves a list of workgroups in a domain.
 */
trait WorkgroupMethods
{

/* CREATE WORKGROUP
----------------------------------------------------------------------------- */

    /**
     * The create_workgroup method creates a new workgroup in a specified domain.
     *
     * @param string $domain The domain under which you want to create the workgroup.
     * @param string $workgroup The name of the new workgroup.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function createWorkgroup( string $domain, string $workgroup ) : object
    {
        $params = [ 'domain' => $domain, 'workgroup' => $workgroup ];

        return $this->request( method: 'create_workgroup', params: $params );
    }



/* DELETE WORKGROUP
----------------------------------------------------------------------------- */

    /**
     * The delete_workgroup method deletes a specified workgroup.
     *
     * @param string $domain The name of the domain to which the workgroup belongs.
     * @param string $workgroup The name of the workgroup that you want to delete.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function deleteWorkgroup( string $domain, string $workgroup ) : object
    {
        $params = [ 'domain' => $domain, 'workgroup' => $workgroup ];

        return $this->request( method: 'delete_workgroup', params: $params );
    }



/* SEARCH WORKGROUP
----------------------------------------------------------------------------- */

    /**
     * The search_workgroups method retrieves a list of workgroups in a domain.
     *
     * @param array<string, mixed>|object|null $criteria Narrows the results
     *  by restricting the search to the specified fields and their values.
     * @param array<string, mixed>|object|null $sort Determines the way in
     *  which to sort and display results. Allowed values are:
     *      by — Specify the attribute to use to sort results. Allowed values are:
     *      user — The number of users in the workgroup.
     *      workgroup — The workgroup name (this is the default).
     *      direction — Specify the sort order. Allowed values are ascending
     * (this is the default) or descending.
     *  specify the number of users of a certain mailbox type by using one of
     *  the following: users/alias, users/deleted, users/filter, users/forward,
     *  or users/mailbox.
     *       direction — Specify the sort order. Allowed values are ascending
     *  (this is the default) or descending.
     * @param ?int $rangeFirst Specify the first workgroup to return; the default
     * is the first result.
     * @param ?int $rangeLimit Specify the maximum number of results to return.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function searchWorkgroups(
        array|object|null $criteria = null,
        array|object|null $sort     = null,
                     ?int $rangeFirst = null,
                     ?int $rangeLimit = null,
    ) : object
    {
        $params = [];
        if( $criteria !== null )   { $params['criteria'] = $criteria ; }
        if( $sort !== null )       { $params['sort'] = $sort ; }
        if( $rangeFirst !== null ) { $params['range']['first'] = $rangeFirst; }
        if( $rangeLimit !== null ) { $params['range']['limit'] = $rangeLimit; }

        return $this->request( method: 'search_workgroups', params: $params );
    }
}