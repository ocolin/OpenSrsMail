<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail;

use GuzzleHttp\Exception\GuzzleException;
use Ocolin\OpenSrsMail\Types\SearchRange;
use Ocolin\OpenSrsMail\Types\WorkgroupCriteria;
use Ocolin\OpenSrsMail\Types\WorkgroupSearch;

trait WorkgroupTrait
{

/*
------------------------------------------------------------------------------ */

    /**
     * The create_workgroup method creates a new workgroup in a specified domain.
     *
     * @param string $domain The domain under which you want to create the workgroup.
     * @param string $workgroup The name of the new workgroup.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function createWorkgroup(
        string $domain,
        string $workgroup
    ) : object | null
    {
        $payload = [ 'domain' => $domain, 'workgroup' => $workgroup ];
        $output = $this->call( call: 'create_workgroup', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/*
------------------------------------------------------------------------------ */

    /**
     * The delete_workgroup method deletes a specified workgroup.
     *
     * @param string $domain The name of the domain to which the workgroup belongs.
     * @param string $workgroup The name of the workgroup that you want to delete.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function deleteWorkgroup(
        string $domain,
        string $workgroup
    ) : object | null
    {
        $payload = [ 'domain' => $domain, 'workgroup' => $workgroup ];
        $output = $this->call( call: 'delete_workgroup', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/*
------------------------------------------------------------------------------ */

    /**
     * The search_workgroups method retrieves a list of workgroups in a domain.
     *
     * @param WorkgroupSearch|array<string, mixed> $search Narrows the results by restricting
     * the search.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function searchWorkgroups(
        WorkgroupSearch|array $search = []
    ) : object | null
    {
        $output = $this->call( call: 'search_workgroups', payload: $search );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/*
------------------------------------------------------------------------------ */

    /**
     * Create an empty Workgroup Search DTO.
     *
     * @return WorkgroupSearch
     */
    public static function createWorkgroupSearch() : WorkgroupSearch
    {
        $search = new WorkgroupSearch();
        $search->criteria = new WorkgroupCriteria();
        $search->range = new SearchRange();

        return $search;
    }
}