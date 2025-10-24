<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail;

use GuzzleHttp\Exception\GuzzleException;
use Ocolin\OpenSrsMail\Types\BrandCriteria;
use Ocolin\OpenSrsMail\Types\SearchBrands;
use Ocolin\OpenSrsMail\Types\SearchRange;
use Ocolin\OpenSrsMail\Types\BrandMember;

trait BrandTrait
{

/*
------------------------------------------------------------------------------ */

    /**
     * The search_brands method retrieves a list of brands in a company.
     *
     * @param SearchBrands|array<string,mixed> $search Narrows the search for brands.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function searchBrands( SearchBrands|array $search = [] ) : object | null
    {
        $output = $this->call( call: 'search_brands', payload: $search );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/*
------------------------------------------------------------------------------ */

    /**
     * The search_brand_members method retrieves a list of domains or users
     * that have the requested brand applied to them.
     *
     * @param SearchBrands|array<string,mixed> $search Narrows the search for brands.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function searchBrandMembers(
        SearchBrands|array $search = []
    ) : object | null
    {
        $output = $this->call( call: 'search_brand_members', payload: $search );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/*
------------------------------------------------------------------------------ */

    /**
     * Create an empty Brand search DTO.
     * @return SearchBrands
     */
    public static function createSearchBrand() : SearchBrands
    {
        $search = new SearchBrands();
        $search->criteria = new BrandCriteria();
        $search->range = new SearchRange();

        return $search;
    }



/*
------------------------------------------------------------------------------ */

    /**
     * Create an empty Brand Members DTO.
     * @return BrandMember
     */
    public static function createBrandMember() : BrandMember
    {
        $search = new BrandMember();
        $search->criteria = new BrandCriteria();
        $search->range = new SearchRange();

        return $search;
    }
}