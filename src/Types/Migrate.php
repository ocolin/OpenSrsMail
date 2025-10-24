<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Types;

class Migrate extends Payload
{
    /**
     * @var string A job ID. This ID can be used in other migration
     * requests. If an ID is not supplied in the request, it will
     * be created and returned in the response.
     */
    public string $id;

    /**
     * @var MigrateUsers Defines the source and destination of the
     * email that you want to migrate.
     */
    public MigrateUsers $users;
}