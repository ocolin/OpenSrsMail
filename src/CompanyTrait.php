<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail;

use GuzzleHttp\Exception\GuzzleException;
use Ocolin\OpenSrsMail\Types\CompanyAttributes;
use Ocolin\OpenSrsMail\Types\SearchRange;

trait CompanyTrait
{

/* CHANGE COMPANY
------------------------------------------------------------------------------ */

    /**
     * The change_company method changes the attributes of an existing company.
     *
     * @param string $company The name of the company you want to change.
     * @param CompanyAttributes $attributes A hash of company level attributes
     *  and values.
     * @return object|null
     * @throws GuzzleException
     */
    public function changeCompany(
        string $company,
        CompanyAttributes $attributes,
    ) : object | null
    {
        $payload = [ 'company' => $company, 'attributes' => $attributes ];
        $output = $this->call( call: 'change_company', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* CHANGE COMPANY BULLETIN
------------------------------------------------------------------------------ */

    /**
     * The change_company_bulletin method creates, changes, or deletes a
     * company-level bulletin.
     *
     * If a bulletin with the specified name and type does not exist, that bulletin
     * is created. If a bulletin with the specified name and type exists and
     * bulletin_text is submitted in the method, the existing bulletin is changed.
     * If a bulletin with the specified name and type exists and bulletin_text is
     * not submitted, the existing bulletin is deleted.
     *
     * @param string $company The name of the company.
     * @param string $bulletin The name of the bulletin you want to create, change, or delete.
     *
     *  Note: All bulletins in a company must have a unique type and name. That is, it is
     *  possible for anautobulletin and amanualbulletin to have the same name, but not
     *  for two auto** bulletins to have the same name.
     *
     * @param string $type Specify the bulletin type. Allowed values are:
     *  auto — The bulletin is automatically sent to new users when their accounts are created.
     *  manual — The bulletin is sent only when the post_company_bulletin method is run.
     *
     * @param string|null $bulletin_text The text of the bulletin. Bulletins must be
     *  formatted as a raw email. If the bulletin_text does not contain a Date header,
     *  a header will be appended, with the date that the bulletin was posted.
     *
     *  The text can include the following variables:
     *  {'account'} — The recipient's mailbox address (joe_user@example.com).
     *  {'domain'} — The domain to which the user belongs (example.com).
     *  {'name'} — The recipient's name.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function changeCompanyBulletin(
         string $company,
         string $bulletin,
         string $type,
        ?string $bulletin_text = null
    ) : object | null
    {
        $payload = [ 'company' => $company, 'bulletin' => $bulletin , 'type' => $type ];
        if( $bulletin_text !== null ) { $payload['bulletin_text'] = $bulletin_text; }

        $output = $this->call( call: 'change_company_bulletin', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* GET COMPANY
------------------------------------------------------------------------------ */

    /**
     * The get_company method retrieves settings and other information for
     * a specified company.
     *
     * @param string $company The company whose settings you want to view.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function getCompany( string $company ) : object | null
    {
        $output = $this->call(
               call: 'get_company',
            payload: ['company' => $company ]
        );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* GET COMPANY BULLETIN
------------------------------------------------------------------------------ */

    /**
     * The get_company_bulletin method retrieves the text of a specified company-
     * level bulletin.
     *
     * @param string $company The name of the company.
     * @param string $bulletin The name of the bulletin you want to view.
     * @param string $type Specify the bulletin type. Allowed values are:
     *  auto — The bulletin is automatically sent to new users when their accounts are created.
     *  manual — The bulletin is sent only when the post_company_bulletin ADD LINK method is run.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function getCompanyBulletin(
        string $company,
        string $bulletin,
        string $type,
    ) : object | null
    {
        $payload = [ 'company' => $company, 'bulletin' => $bulletin , 'type' => $type ];
        $output = $this->call( call: 'get_company_bulletin', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* GET COMPANY CHANGES
------------------------------------------------------------------------------ */

    /**
     * The get_company_changes method retrieves a summary of the changes that
     * have been made to a company.
     *
     * @param string $company The name of the company.
     * @param int|null $first The 0-based index of the first result to return.
     * @param int|null $limit The maximum number of results to return.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function getCompanyChanges(
        string $company,
          ?int $first = null,
          ?int $limit = null,
    ) : object | null
    {
        $payload = [ 'company' => $company ];
        $payload['range'] = new SearchRange();
        if( $first !== null ) { $payload['range']->first = $first; }
        if( $limit !== null ) { $payload['range']->limit = $limit; }

        $output = $this->call( call: 'get_company_changes', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }



/* POST COMPANY BULLETIN
------------------------------------------------------------------------------ */

    /**
     * The post_company_bulletin method sends the specified bulletin to all
     * users in all domains in the company.
     *
     * @param string $company The name of the company.
     * @param string $bulletin The name of the bulletin you want to view.
     * @param string $type Specify the bulletin type.
     *  Allowed values are:
     *  auto — The bulletin is automatically sent to new users when their accounts are created.
     *  manual — The bulletin is sent only when the post_company_bulletin method is run.
     * @param string|null $test_email Sends the bulletin to only the specified email address.
     * @return object|null Response object.
     * @throws GuzzleException
     */
    public function postCompanyBulletin(
         string $company,
         string $bulletin,
         string $type,
        ?string $test_email = null
    ) : object | null
    {
        $payload = [ 'company' => $company, 'bulletin' => $bulletin , 'type' => $type ];
        if( $test_email !== null ) { $payload['test_email'] = $test_email; }
        $output = $this->call( call: 'post_company_bulletin', payload: $payload );

        if( empty( $output->body )) { return null; }
        return (object)$output->body;
    }
}