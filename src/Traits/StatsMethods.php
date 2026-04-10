<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Traits;

use GuzzleHttp\Exception\GuzzleException;

/**
 * This section contains the following methods:
 * stats_list—Retrieves a list of available stats periods for use with the stats_snapshot method.
 * stats_snapshot—Generates a URL from which a stats snapshot can be downloaded.
 * stats_summary—Displays summary statistics for a user, domain, or company.
 */

trait StatsMethods
{

/* STATS LIST
----------------------------------------------------------------------------- */

    /**
     * The stats_list method retrieves a list of available stats periods for
     * use with the stats_snapshot method.
     *
     * @return object
     * @throws GuzzleException
     */
    public function statsList() : object
    {
        return $this->request( method: 'stats_list' );
    }


/* STATS SNAPSHOT
----------------------------------------------------------------------------- */

    /**
     * The stats_snapshot method generates a URL from which a stats snapshot
     * can be downloaded.
     *
     * @param string $type The type of entity for which you want to see statistics.
     * Allowed values are company or domain.
     * @param string $object The name of the company or domain.
     * @param string $date The date for which you want to see statistics, in
     * the format YYYY-MM. Available periods can be retrieved by using
     * the stats_list method.
     * @return object
     * @throws GuzzleException
     */
    public function statsSnapshot(
        string $type,
        string $object,
        string $date
    ) : object
    {
        $params = [ 'type' => $type, 'object' => $object, 'date' => $date ];

        return $this->request( method: 'stats_snapshot', params: $params );
    }


/* STATS SUMMARY
----------------------------------------------------------------------------- */

    /**
     * The stats_summary method displays summary statistics for a user,
     * domain, or company.
     *
     * @param ?string $by The interval that you want displayed in the response
     * for the statistics summary. Allowed values are day, week, and month.
     * day—Display information for each of the most recent 30 days.
     * week—Display information for each of the most recent 52 weeks;; each
     * week begins on a Monday.
     * month—Display information for the most recent 48 months.
     * @param ?string $type The type of object. Allowed values are company,
     * domain, or user.
     * @param ?string $object The name of the company, domain, or user for
     * which you want to view statistics.
     * @return object
     * @throws GuzzleException
     */
    public function statsSummary(
        ?string $by     = null,
        ?string $type   = null,
        ?string $object = null,
    ) : object
    {
        $params = [];
        if( $by !== null ) { $params['by'] = $by ; }
        if( $type !== null ) { $params['type'] = $type; }
        if( $object !== null ) { $params['object'] = $object; }

        return $this->request( method: 'stats_summary', params: $params );
    }
}