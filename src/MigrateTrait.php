<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail;

use GuzzleHttp\Exception\GuzzleException;
use Ocolin\OpenSrsMail\Types\Migrate;
use Ocolin\OpenSrsMail\Types\MigrateUsers;

trait MigrateTrait
{


/* ADD MIGRATION
------------------------------------------------------------------------------ */

    /**
     * The migration_add job creates a bulk migration job that copies email
     * from multiple remote accounts to multiple local accounts.
     *
     * @param Migrate|array<string,mixed> $migrate Migration data.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function migrateAdd(
        Migrate|array $migrate = []
    ) : object | null
    {
        $output = $this->call( call: 'migrate_add', payload: $migrate );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* CHECK MIGRATION JOBS
------------------------------------------------------------------------------ */

    /**
     * The migration_jobs method retrieves a list of current and historical
     * migration jobs submitted by the requester.
     *
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function migrateJobs() : object | null
    {
        $output = $this->call( call: 'migrate_jobs' );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* TRACE MIGRATION
------------------------------------------------------------------------------ */

    /**
     * The migration_trace method retrieves detailed information about a
     * single user in a current or historical migration job.
     *
     * Migration trace files are not available until the migration has started.
     *
     * @param string $job The ID of the job you are querying. The ID is
     *  returned in the migration_add response.
     * @param string $user The username of the person whose migration job is
     *  being retrieved.
     * @return object|null
     * @throws GuzzleException
     */
    public function migrateTrace(
        string $job,
        string $user
    ) : object | null
    {
        $output = $this->call(
            call: 'migrate_trace',
            payload: [ 'job' => $job, 'user' => $user ]
        );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* GET MIGRATION STATUS
------------------------------------------------------------------------------ */

    /**
     * The migration_status method provides detailed information about the
     * progress and results of a migration job.
     *
     * @param string $id The ID of the job you are querying.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function migrationStatus( string $id ) : object | null
    {
        $output = $this->call( call: 'migration_status', payload: [ 'id' => $id ] );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* CREATE MIGRATION DTO
------------------------------------------------------------------------------ */

    /**
     * Generate Migrate DTO.
     * @return Migrate
     */
    public static function createMigrate() : Migrate
    {
        $migrate = new Migrate();
        $migrate->users = new MigrateUsers();

        return $migrate;
    }
}