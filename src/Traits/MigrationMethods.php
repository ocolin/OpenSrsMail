<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Traits;

use GuzzleHttp\Exception\GuzzleException;

/**
 * This section contains the following methods:
 * migrationAdd - Copies email from multiple remote accounts to multiple local accounts.
 * migrationJobs — Retrieve a list of current and historical migration jobs submitted by the requester.
 * migrationStatus — Provides detailed information about the progress and results of a migration job
 * migrationTrace — Retrieves detailed information about a single user in a current or historical migration job.
 */

trait MigrationMethods
{

/* MIGRATION ADD
----------------------------------------------------------------------------- */

    /**
     * The migration_add job creates a bulk migration job that copies
     * email from multiple remote accounts to multiple local accounts.
     *
     * @param object[]|object $users Defines the source and destination of the
     * email that you want to migrate.
     * @param string|int|null $job A job ID. This ID can be used in other
     * migration requests. If an ID is not supplied in the request, it will
     * be created and returned in the response.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function migrationAdd(
           array|object $users,
        string|int|null $job = null
    ) : object
    {
        $params = [ 'users' => $users ];
        if( $job !== null ) { $params['job'] = $job; }

        return $this->request( method: 'migration_add', params: $params );
    }



/* MIGRATION JOBS
----------------------------------------------------------------------------- */

    /**
     * The migration_jobs method retrieves a list of current and historical
     * migration jobs submitted by the requester.
     *
     * @return object API response object.
     * @throws GuzzleException
     */
    public function migrationJobs() : object
    {
        return $this->request( method: 'migration_jobs' );
    }



/* MIGRATION STATUS
----------------------------------------------------------------------------- */

    /**
     * The migration_status method provides detailed information about the
     * progress and results of a migration job.
     *
     * @param string|int $id The ID of the job you are querying.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function migrationStatus( string|int $id ) : object
    {
        $params = [ 'id' => $id ];
        return $this->request( method: 'migration_status', params: $params );
    }



/* MIGRATION TRACE
----------------------------------------------------------------------------- */

    /**
     * The migration_trace method retrieves detailed information about a
     * single user in a current or historical migration job.
     *
     * Migration trace files are not available until the migration has started.
     *
     * @param string|int $job The ID of the job you are querying. The ID is
     * returned in the migration_add response.
     * @param string $user The username of the person whose migration job
     * is being retrieved.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function migrationTrace( string|int $job, string $user ) : object
    {
        $params = [ 'job' => $job, 'user' => $user ];

        return $this->request( method: 'migration_trace', params: $params );
    }
}