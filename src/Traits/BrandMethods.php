<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Traits;

use GuzzleHttp\Exception\GuzzleException;

/**
 * This section contains the following method:
 *
 * searchBrandsRetrieves a list of brands in a company.
 * The _change_brand, _get_brand, and _delete_brand calls are expected to
 * be called from within the Mail Administration Centre and documentation
 * may be unavailable or out of date on.
 */
trait BrandMethods
{

/* SEARCH BRANDS
----------------------------------------------------------------------------- */

    /**
     * The following fields can be used in the search_brands method:
     *
     * @param array<string, mixed>|object|null $criteria Narrows the search for brands.
     * @param ?int $rangeFirst The 0-based index of the first result to return.
     * @param ?int $rangeLimit The maximum number of results to return.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function searchBrands(
        array|object|null $criteria = null,
                     ?int $rangeFirst = null,
                     ?int $rangeLimit = null,
    ) : object
    {
        $params = [];
        if( $criteria !== null ) { $params['criteria'] = $criteria; }
        if( $rangeFirst !== null ) { $params['range']['first'] = $rangeFirst; }
        if( $rangeLimit !== null ) { $params['range']['limit'] = $rangeLimit; }

        return $this->request( method: 'search_brands', params: $params );
    }



/* SEARCH BRAND MEMBERS
----------------------------------------------------------------------------- */

    /**
     * @param array<string, mixed>|object|null $criteria Narrows the search for brands.
     * @param ?int $rangeFirst The 0-based index of the first result to return.
     * @param ?int $rangeLimit The maximum number of results to return.
     * of results that are returned. Allowed values are:
     *       first — The 0-based index of the first result to return.
     *       limit — The maximum number of results to return.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function searchBrandMembers(
        array|object|null $criteria   = null,
                     ?int $rangeFirst = null,
                     ?int $rangeLimit = null
    ) : object
    {
        $params = [];
        if( $criteria !== null )   { $params['criteria'] = $criteria; }
        if( $rangeFirst !== null ) { $params['range']['first'] = $rangeFirst; }
        if( $rangeLimit !== null ) { $params['range']['limit'] = $rangeLimit; }

        return $this->request(method: 'search_brand_members', params: $params);
    }
}