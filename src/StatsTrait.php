<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail;

use GuzzleHttp\Exception\GuzzleException;

trait StatsTrait
{

/* LIST STATS
------------------------------------------------------------------------------ */

    /**
     * The stats_list method retrieves a list of available stats periods
     * for use with the stats_snapshot method.
     *
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function statsList() : object | null
    {
        $output = $this->call( call: 'stats_list' );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* GET STATS URL
------------------------------------------------------------------------------ */

    /**
     * The stats_snapshot method generates a URL from which a stats
     * snapshot can be downloaded.
     *
     * @param string $type The type of entity for which you want to see statistics.
     *  Allowed values are company or domain.
     * @param string $object The name of the company or domain.
     * @param string $date The date for which you want to see statistics, in the format
     *  YYYY-MM. Available periods can be retrieved by using the stats_list method.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function statsSnapshot(
        string $type,
        string $object,
        string $date
    ) : object | null
    {
        $payload = [ 'type' => $type, 'object' => $object, 'date' => $date ];
        $output = $this->call( call: 'stats_snapshot', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* STATS SUMMARY
------------------------------------------------------------------------------ */

    /**
     * The stats_summary method displays summary statistics for a user,
     * domain, or company.
     *
     * @param string $type The type of object. Allowed values are company,
     *  domain, or user.
     * @param string $object The name of the company, domain, or user for
     *  which you want to view statistics.
     * @param string $by The interval that you want displayed in the response
     *  for the statistics summary. Allowed values are day, week, and month.
     *
     *  day—Display information for each of the most recent 30 days.
     *  week—Display information for each of the most recent 52 weeks;; each week begins on a Monday.
     *  month—Display information for the most recent 48 months.
     *
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function statsSummary(
        string $type,
        string $object,
        string $by
    ) : object | null
    {
        $payload = [ 'type' => $type, 'object' => $object, 'by' => $by ];
        $output = $this->call( call: 'stats_summary', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }
}