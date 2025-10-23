<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail;

trait CompanyTrait
{
    public function getCompany( string $company ) : object | null
    {
        $output = $this->call(
               call: 'get_company',
            payload: ['company' => $company ]
        );

        if( empty( $output->body )) { return null; }

        return (object)$output->body;
    }

}