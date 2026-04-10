<?php

declare( strict_types = 1 );

namespace Ocolin\OpenSrsMail\Traits;

use GuzzleHttp\Exception\GuzzleException;

/**
 * This section contains the following methods:
 * changeCompany — Changes the attributes of an existing company.
 * changeCompanyBulletin — Creates, changes, or deletes a company- level bulletin.
 * getCompany — Retrieves settings and other information for a specified company.
 * getCompanyBulletin — Retrieves the text of a specified company-level bulletin.
 * getCompanyChanges — Retrieves a summary of the changes that have been made to a company.
 * postCompanyBulletin — Sends the specified bulletin to all users in all domains in the company.
 * searchAdmins — Retrieves a list of the admins in a specified company.
 */

trait CompanyMethods
{

/* CHANGE / MODIFY COMPANY
----------------------------------------------------------------------------- */

    /**
     * The change_company method changes the attributes of an existing company.
     *
     * @param string $company The name of the company you want to change.
     * @param array<string, mixed>|object $attributes A hash of company level
     * attributes and values.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function changeCompany( string $company, array|object $attributes ) : object
    {
        $params = [ 'company' => $company, 'attributes' => $attributes ];

        return $this->request( method: 'change_company', params: $params );
    }


/* CHANGE COMPANY BULLETIN
----------------------------------------------------------------------------- */

    /**
     * The change_company_bulletin method creates, changes, or deletes a
     * company-level bulletin.
     *
     * If a bulletin with the specified name and type does not exist, that
     * bulletin is created. If a bulletin with the specified name and type
     * exists and bulletin_text is submitted in the method, the existing
     * bulletin is changed. If a bulletin with the specified name and
     * type exists and bulletin_text is not submitted, the existing
     * bulletin is deleted.
     *
     * @param string $company The name of the company.
     * @param string $bulletin The name of the bulletin you want to create,
     * change, or delete.
     * @param string $type Specify the bulletin type. Allowed values are:
     *
     * auto — The bulletin is automatically sent to new users when their
     * accounts are created.
     *
     * manual — The bulletin is sent only when the post_company_bulletin
     * method is run.
     * @param ?string $bulletinText
     * @return object API response object.
     * @throws GuzzleException
     */
    public function changeCompanyBulletin(
         string $company,
         string $bulletin,
         string $type,
        ?string $bulletinText = null,
    ) : object
    {
        $params = [ 'company' => $company, 'bulletin' => $bulletin, 'type' => $type ];
        if( $bulletinText !== null ) { $params['bulletin_text'] = $bulletinText; }

        return $this->request( method: 'change_company_bulletin', params: $params );
    }



/* GET COMPANY
----------------------------------------------------------------------------- */

    /**
     * The get_company method retrieves settings and other information for
     * a specified company.
 *
     * @param string $company The company whose settings you want to view.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function getCompany( string $company ) : object
    {
        $params = [ 'company' => $company ];

        return $this->request( method: 'get_company', params: $params );
    }



/* GET COMPANY BULLETIN
----------------------------------------------------------------------------- */

    /**
     * The get_company_bulletin method retrieves the text of a
     * specified company-level bulletin.
     *
     * @param string $company The name of the company.
     * @param string $bulletin The name of the bulletin you want to view.
     * @param string $type Specify the bulletin type. Allowed values are:
     *      auto — The bulletin is automatically sent to new users when
     * their accounts are created.
     *      manual — The bulletin is sent only when the
     * post_company_bulletin ADD LINK method is run.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function getCompanyBulletin(
        string $company,
        string $bulletin,
        string $type,
    ) : object
    {
        $params = [ 'company' => $company, 'bulletin' => $bulletin, 'type' => $type ];

        return $this->request( method: 'get_company_bulletin', params: $params );
    }



/* GET COMPANY CHANGES
----------------------------------------------------------------------------- */

    /**
     * The get_company_changes method retrieves a summary of the changes
     * that have been made to a company.
     *
     * @param string $company The name of the company.
     * @param ?int $rangeFirst The 0-based index of the first result to return.
     * @param ?int $rangeLimit The maximum number of results to return.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function getCompanyChanges(
        string $company,
        ?int $rangeFirst = null,
        ?int $rangeLimit = null,
    ) : object
    {
        $params = [ 'company' => $company ];
        if( $rangeFirst !== null ) { $params['range']['first'] = $rangeFirst; }
        if( $rangeLimit !== null ) { $params['range']['limit'] = $rangeLimit; }

        return $this->request( method: 'get_company_changes', params: $params );
    }



/* POST COMPANY BULLETIN
----------------------------------------------------------------------------- */

    /**
     * The post_company_bulletin method sends the specified bulletin to all
     * users in all domains in the company.
     *
     * @param string $company The name of the company.
     * @param string $bulletin The name of the bulletin you want to view.
     * @param string $type Specify the bulletin type. Allowed values are:
     *      auto — The bulletin is automatically sent to new users
     * when their accounts are created.
     *      manual — The bulletin is sent only when the
     * post_company_bulletin method is run.
     * @param ?string $testEmail Sends the bulletin to only the specified
     * email address.
     * @return object API response object.
     * @throws GuzzleException
     */
    public function postCompanyBulletin(
         string $company,
         string $bulletin,
         string $type,
        ?string $testEmail = null,
    ) : object
    {
        $params = [ 'company' => $company, 'bulletin' => $bulletin, 'type' => $type ];
        if( $testEmail !== null ) { $params['test_email'] = $testEmail; }

        return $this->request( method: 'post_company_bulletin', params: $params );
    }
}