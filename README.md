# OpenSRS Email

## What is it?

This is a small PHP client for accessing Open SRS's Email hosting API services.

It's designed so you can set to make using the API services quickly and easily, as well as provide very good documentation to help along the way using detailed PHPDoc information taken from the user manual.  

Here is a link to the OpenSRS manual for reference: [Manual](https://email.opensrs.guide/docs/)

---

## Table Of Contents

- [What is it?](#What-is-it?)
- [Requirements](#Requirements)
- [Installation](#Installation)
- [Configuration](#Configuration)
- [Response object](#Response-object)
- [REQUEST METHOD](#REQUEST-METHOD)
- [User Methods](#User-Methods)
  - [changeUser](#changeUser)
  - [deleteUser](#deleteUser)
  - [generateToken](#generateToken)
  - [getDeletedContacts](#getDeletedContacts)
  - [getDeletedMessages](#getDeletedMessages)
  - [getUser](#getUser)
  - [getUserAttributeHistory](#getUserAttributeHistory)
  - [getUserChanges](#getUserChanges)
  - [getUserFolders](#getUserFolders)
  - [getUserMessages](#getUserMessages)
  - [logoutUser](#logoutUser)
  - [moveUserMessages](#moveUserMessages)
  - [reindex](#reindex)
  - [renameUser](#renameUser)
  - [restoreDeletedContacts](#restoreDeletedContacts)
  - [restoreDeletedMessages](#restoreDeletedMessages)
  - [restoreUser](#restoreUser)
  - [searchUsers](#searchUsers)
  - [setRole](#setRole)
  - [userNotify](#userNotify)
  - [appPassword](#appPassword)
  - [resetEmailTest](#resetEmailTest)
  - [getSieve](#getSieve)
  - [setSieve](#setSieve)
- [Stats Methods](#Stats-Methods)
  - [statsList](#statsList)
  - [statsSnapshot](#statsSnapshot)
  - [statsSummary](#statsSummary)
- [Domain Methods](#Domain-Methods)
  - [changeDomain](#changeDomain)
  - [changeDomainBulletin](#changeDomainBulletin)
  - [deleteDomain](#deleteDomain)
  - [getDomain](#getDomain)
  - [getDomainBulletin](#getDomainBulletin)
  - [postDomainBulletin](#postDomainBulletin)
  - [restoreDomain](#restoreDomain)
  - [searchDomains](#searchDomains)
  - [pushDomain](#pushDomain)
- [Company Methods](#Company-Methods)
  - [changeCompany](#changeCompany)
  - [changeCompanyBulletin](#changeCompanyBulletin)
  - [getCompany](#getCompany)
  - [getCompanyBulletin](#getCompanyBulletin)
  - [getCompanyChanges](#getCompanyChanges)
  - [postCompanyBulletin](#postCompanyBulletin)
- [Brand Methods](#Brand-Methods)
  - [searchBrands](#searchBrands)
  - [searchBrandMembers](#searchBrandMembers)
- [Workgroup Methods](#Workgroup-Methods)
  - [createWorkgroup](#createWorkgroup)
  - [deleteWorkgroup](#deleteWorkgroup)
  - [searchWorkgroups](#searchWorkgroups)
- [Authentication Methods](#Authentication-Methods)
  - [authenticate](#authenticate)
  - [echo](#echo)
- [Migration Methods](#Migration-Methods)
  - [migrationAdd](#migrationAdd)
  - [migrationJobs](#migrationJobs)
  - [migrationStatus](#migrationStatus)
  - [migrationTrace](#migrationTrace)


---
## Requirements

- PHP ^8.3
- guzzlehttp/guzzle ^7.10
- ocolin/global-type ^2.0
- 
---
## Installation

```
composer require ocolin/open-srs-mail
```
---
## Configuration

There are two ways to configure the client. One is through Environment variables and the other is through constructor arguments. Here is a list of the parameters:

|Env Names| Arg Names | Type    | Default           | Description                               |
|---------|-----------|---------|-------------------|-------------------------------------------|
|OPENSRS_MAIL_HOST| $host     | string  | test server       | Name of OpenSRS server                    |
|OPENSRS_MAIL_USERNAME|$username| string  | N/A               | Username to log in as                     |
|OPENSRS_MAIL_PASSWORD|$password| string  | N/A               | Password for log in                       |
|OPENSRS_MAIL_MODE|$mode| string  | TOKEN             | Whether to use a password or token        |
|OPENSRS_MAIL_TOKEN_EXPIRATION|$token_expiration| integer | 10800| How long token will last. Up to 24 hours. |
|OPENSRS_MAIL_CACHE_PATH|$cache_path| string  | system temp files | Where to store a temporary token.         |

### Host

Open SRS has 3 servers. One for testing, one for their A cluster, and one for their B cluster. If no server is specified it will result in the test server being used. There are also shortcuts for the 3 servers for eas of use.

|Shortcut value|Server|
|--------------|------|
|TEST|https://admin.test.hostedemail.com/api|
|A|https://admin.a.hostedemail.com/api|
|B|https://admin.b.hostedemail.com/api|

Or you can specify a different server should OpenSRS add others in the future, or if you want to manually enter an existing an OpenSRS server.

### Mode

OpenSRS has two authentication methods.

#### PASSWORD

In password mode, you submit your username and password for every request. With this client you only set them once, but they are sent with every request. The downside to this method is that each API call takes longer as it has to validate the authentication each time.

#### TOKEN

In Token mode, the default, a temporary token is used and stored for each request. This improves API call time as the token is already validated. This client will automatically handle renewing the tokens for you so you don't have to worry about it. 

#### TOKEN EXPIRATION

The default token life is 10800 seconds. But you can use this property if you want to customize it.

This is optional only used in TOKEN mode.

#### CACHE PATH

By default, this client will use your system temp file folder to store tokens. This property allows you to override that and use a custom storage location.

This is optional only used in TOKEN mode.

### Configuring with environment variables

```php
// Manually setting vars for demonstration purposes
$_ENV['OPENSRS_MAIL_HOST'] = 'https://admin.test.hostedemail.com/api';
$_ENV['OPENSRS_MAIL_USERNAME'] = 'opensrs@mydomain.adm';
$_ENV['OPENSRS_MAIL_PASSWORD'] = '12345abc!@#';
// OPTIONAL PARAMETERS
$_ENV['OPENSRS_MAIL_MODE'] = 'TOKEN';
$_ENV['OPENSRS_MAIL_TOKEN_EXPIRATION'] = '600';
$_ENV['OPENSRS_MAIL_CACHE_PATH'] = '/tmp/';

$opensrs = new Ocolin\OpenSrsMail\Client();
```

### Configuring with constructor arguments

Configuration uses a Config class to store your settings.

```php
$opensrs = new Ocolin\OpenSrsMail\Client( 
    client: new Ocolin\OpenSrsMail\Config(
        host: 'https://admin.test.hostedemail.com/api',
        username: 'opensrs@mydomain.adm',
        password: '12345abc!@#',
        // OPTIONAL
        mode: 'TOKEN',
        token_expiration: 600,
        cache_path: '/tmp/'
    )
);
```

### Options

You can also pass some guzzle HTTP configuration options along such as timeouts or verifying SSL to override defaults if needed.

```php
$opensrs = new Ocolin\OpenSrsMail\Client( 
    client: new Ocolin\OpenSrsMail\Config(
        options: [ 'timeout' => 60, 'verify'] => false 
    )
);
```
---
## Response object

This client passes along the object from the server as it exists. To see what response to expect, see the OpenSRS documentation.

---
## REQUEST METHOD

The client has a single generic request method that can be used to manually call any endpoint on the OpenSRS API server. While not intended to be used by default, it exists in case OpenSRS adds new endpoints, if any are missing, or if any are not working properly. 

```php
$response = $opensrs->request(
    method: 'get_company',
    params: [ 'company' => 'myCompanyName' ]
);
```

There are two parameters. One is the method for the OpenSRS server, and the others is an array or object with the parameters and values to send to the server.

### Conventions

The OpenSRS api use snake case for names. This client uses camelCase instead. So anything in the manual using snake case just needs to be converted to camelCase.

Example: delete_user is deleteUser. create_only property is createOnly.

Knowing this will help you not have to rely on this help doc as much.

---
## User Methods

### changeUser

```php
$output = $opensrs->changeUser(...);
```

#### Manual

https://email.opensrs.guide/docs/change_user

#### Arguments

|Argument| Required | Type         | Description |
|--------|----------|--------------|-------------|
|$user| YES      | string       |The user that you want to create or modify.|
|$attributes| YES      | object\|arra |The list of fields that you want to define or modify and their new values.|
|$createOnly| NO       | boolean      |Used to prevent changes to existing accounts.|

### deleteUser

```php
$output = $opensrs->deleteUser(...);
```

#### Manual

https://email.opensrs.guide/docs/delete_user

#### Arguments

|Argument| Required | Type         | Description                       |
|--------|----------|--------------|-----------------------------------|
|$user| YES      | string       | The user that you want to delete. |

### generateToken

```php
$output = $opensrs->generateToken(...);
```

#### Manual

https://email.opensrs.guide/docs/generate_token

#### Arguments

|Argument| Required | Type   | Description                                                   |
|--------|----------|--------|---------------------------------------------------------------|
|$user| YES      | string | The user's mailbox name.                                      |
|$reason| YES      | string | The reason that the token was generated.                      |
|$duration| NO       | int    | The number of hours for which the token is valid.             |
|$oma| NO       | bool   | If set to true, a session or sso type token may used with OMA |
|$token| NO       | string | The token to add.|
|$type| NO       | string |The type of token to generate.|

### getDeletedContacts

```php
$output = $opensrs->getDeletedContacts(...);
```

#### Manual

https://email.opensrs.guide/docs/get_deleted_contacts

#### Arguments

|Argument| Required | Type   | Description              |
|--------|----------|--------|--------------------------|
|$user| YES      | string | The user's mailbox name. |

### getDeletedMessages

```php
$output = $opensrs->getDeletedMessages(...);
```

#### Manual

https://email.opensrs.guide/docs/get_deleted_messages

#### Arguments

|Argument| Required | Type   | Description              |
|--------|----------|--------|--------------------------|
|$user| YES      | string | The user's mailbox name. |
|$headers| NO       | array  |Specify the headers that you want returned.|
|$folder| NO       | string |Name of the folder to search.|

### getUser

```php
$output = $opensrs->getUser(...);
```

#### Manual

https://email.opensrs.guide/docs/get_user

#### Arguments

|Argument| Required | Type   | Description              |
|--------|----------|--------|--------------------------|
|$user| YES      | string | The user's mailbox name. |

### getUserAttributeHistory

```php
$output = $opensrs->getUserAttributeHistory(...);
```

#### Manual

https://email.opensrs.guide/docs/get_user_attribute_history

#### Arguments

|Argument| Required | Type   | Description              |
|--------|----------|--------|--------------------------|
|$user| YES      | string | The user's mailbox name. |
|$attribute| YES      | string |The name of the attribute to query.|

### getUserChanges

```php
$output = $opensrs->getUserChanges(...);
```

#### Manual

https://email.opensrs.guide/docs/get_user_changes

#### Arguments

|Argument| Required | Type   | Description              |
|--------|----------|--------|--------------------------|
|$user| YES      | string | The user's mailbox name. |
|$rangeFirst| NO       | int    |The 0-based index of the first result to return.|
|$rangeLimit| NO       | int    |The maximum number of results to return.|


### getUserFolders

```php
$output = $opensrs->getUserFolders(...);
```

#### Manual

https://email.opensrs.guide/docs/get_user_folders

#### Arguments

|Argument| Required | Type   | Description              |
|--------|----------|--------|--------------------------|
|$user| YES      | string | The user's mailbox name. |

### getUserMessages

```php
$output = $opensrs->getUserMessages(...);
```

#### Manual

https://email.opensrs.guide/docs/get_user_messages

#### Arguments

|Argument| Required | Type   | Description                                                                |
|--------|----------|--------|----------------------------------------------------------------------------|
|$user| YES      | string | The user's mailbox name.                                                   |
|$folder| NO       | string | The folder to search for messages.                                         |
|$limit|NO| int    | Specify the number of messages to return.                                  |
|$recent|NO| bool   | Orders the results starting by most recent                                 |
|$unseen|NO| bool   | Return a list of only those messages that have been delivered to the user. |

### logoutUser

```php
$output = $opensrs->logoutUser(...);
```

#### Manual

https://email.opensrs.guide/docs/logout_user

#### Arguments

|Argument| Required | Type   | Description|
|--------|----------|--------|-----------------------|
|$user| YES      | string | The user's mailbox name.|

### moveUserMessages

```php
$output = $opensrs->moveUserMessages(...);
```

#### Manual

https://email.opensrs.guide/docs/move_user_messages

#### Arguments

|Argument| Required | Type   | Description|
|--------|----------|--------|-----------------------|
|$user| YES      | string | The user's mailbox name.|
|$ids| YES      | array  |The list of ids that you want to move.|
|$folder| NO       | string |The folder to search for messages.|

### reindex

```php
$output = $opensrs->reindex(...);
```

#### Manual

https://email.opensrs.guide/docs/reindex

#### Arguments

|Argument| Required | Type   | Description|
|--------|----------|--------|-----------------------|
|$user| YES      | string | The user's mailbox name.|
|$ids| YES      | array  |The list of ids that you want to move.|
|$folder| NO       | string |The folder to search for messages.|

### renameUser

```php
$output = $opensrs->renameUser(...);
```

#### Manual

https://email.opensrs.guide/docs/rename_user

#### Arguments

|Argument| Required | Type   | Description|
|--------|----------|--------|-----------------------|
|$user| YES      | string | The user's mailbox name.|
|$newName| YES      | string |The new name for the mailbox.|

### restoreDeletedContacts

```php
$output = $opensrs->restoreDeletedContacts(...);
```

#### Manual

https://email.opensrs.guide/docs/restore_deleted_contacts

#### Arguments

|Argument| Required | Type   | Description|
|--------|----------|--------|-----------------------|
|$user| YES      | string | The user's mailbox name.|
|$ids| YES      | array  |The IDs for the messages that you want to restore.|

### restoreDeletedMessages

```php
$output = $opensrs->restoreDeletedMessages(...);
```

#### Manual

https://email.opensrs.guide/docs/restore_deleted_messages

#### Arguments

|Argument| Required | Type   | Description|
|--------|----------|--------|-----------------------|
|$user| YES      | string | The user's mailbox name.|
|$ids| YES      | array  |The IDs for the messages that you want to restore.|

### restoreUser

```php
$output = $opensrs->restoreUser(...);
```

#### Manual

https://email.opensrs.guide/docs/restore_user

#### Arguments

|Argument| Required | Type   | Description|
|--------|----------|--------|-----------------------|
|$user| YES      | string | The user's mailbox name.|
|$newName| YES      | string |Rename the restored account.|
|$id|YES| string |A unique ID that identifies the user.|

### searchUsers

```php
$output = $opensrs->searchUsers(...);
```

#### Manual

https://email.opensrs.guide/docs/search_users

#### Arguments

|Argument| Required | Type  | Description                                    |
|--------|----------|-------|------------------------------------------------|
|$criteria| YES      | array | Narrows the results                            |
|$fields| NO       | array | Additional fields to return.                   |
|$rangeFirst|NO| int   | Specify the first user to return.              |
|$rangeLimit|NO| int   | Specify the maximum number of users to return. |
|$sort|NO| array | etermines the way in which to sort results.    |

### setRole

```php
$output = $opensrs->setRole(...);
```

#### Manual

https://email.opensrs.guide/docs/set_role

#### Arguments

|Argument| Required | Type          | Description                                      |
|--------|----------|---------------|--------------------------------------------------|
|$user| YES      | string        | The user to whom you are assigning a role.       |
|$role| YES      | string        | The name of the role.                            |
|$object| YES      | array\|string | The object over which the user will have rights. |

### userNotify

```php
$output = $opensrs->userNotify(...);
```

#### Manual

https://email.opensrs.guide/docs/user_notify

#### Arguments

|Argument| Required | Type          | Description|
|--------|----------|---------------|-------------|
|$user| YES      | string        | The user's mailbox name.|

### userDisable2fa

```php
$output = $opensrs->userDisable2fa(...);
```

#### Manual

https://email.opensrs.guide/docs/user_disable_2fa

#### Arguments

|Argument| Required | Type          | Description|
|--------|----------|---------------|-------------|
|$user| YES      | string        | The user's mailbox name.|

### appPassword

```php
$output = $opensrs->appPassword(...);
```

#### Manual

https://email.opensrs.guide/docs/app_password

#### Arguments

|Argument| Required | Type   | Description                                |
|--------|----------|--------|--------------------------------------------|
|$user| YES      | string | The user's mailbox name.                   |
|$mode| YES      | string | A string, either "add" or "remove".        |
|$tag| NO       | string | A string. for "add", the tag name desired for this new App Password. |

### resetEmailTest

```php
$output = $opensrs->resetEmailTest(...);
```

#### Manual

https://email.opensrs.guide/docs/reset_email_test

#### Arguments

|Argument| Required | Type   | Description|
|--------|----------|--------|-------------|
|$testEmailRcpt| YES      | string |Email ID where test emails will be sent.|
|$brand| YES      | string |Brand id or brand name.|
|$company| NO       | string |Either a company id or company name.|

### getSieve

```php
$output = $opensrs->getSieve(...);
```

#### Manual

https://email.opensrs.guide/docs/get_sieve

#### Arguments

|Argument| Required | Type   | Description|
|--------|----------|--------|-------------|
|$user| YES      | string | The user's mailbox name.|

### setSieve

```php
$output = $opensrs->setSieve(...);
```

#### Manual

https://email.opensrs.guide/docs/set_sieve

### Arguments

|Argument| Required | Type   | Description              |
|--------|----------|--------|--------------------------|
|$user| YES      | string | The user's mailbox name. |
|$ruleset| YES      |string| Ruleset to set.          |
|$data| NO       |string| Rule data                |
|$activeRule| NO       |string| Set active rule          |

---
## Stats Methods

### statsList

```php
$output = $opensrs->statsList(...);
```

#### Manual

https://email.opensrs.guide/docs/stats_list

No arguments.

### statsSnapshot

```php
$output = $opensrs->statsSnapshot(...);
```

#### Manual

https://email.opensrs.guide/docs/stats_snapshot

#### Arguments

|Argument| Required | Type   | Description                                              |
|--------|----------|--------|----------------------------------------------------------|
|$type| YES      | string | The type of entity for which you want to see statistics. |
|$object|YES|string| The name of the company or domain.                       |
|$date|YES|string| The date for which you want to see statistics.           |

### statsSummary

```php
$output = $opensrs->statsSummary(...);
```

#### Manual

https://email.opensrs.guide/docs/stats_summary

#### Arguments

|Argument| Required | Type   | Description                                                 |
|--------|----------|--------|-------------------------------------------------------------|
|$by| NO       | string | The interval that you want displayed in the response.       |
|$type|NO|string| The type of object.                                         |
|$object|NO|string| The name of the company, domain, or user to get statistics. |

---
## Domain methods

### changeDomain

```php
$output = $opensrs->changeDomain(...);
```

#### Manual

https://email.opensrs.guide/docs/change_domain

#### Arguments
|Argument| Required | Type          | Description                                   |
|--------|----------|---------------|-----------------------------------------------|
|$domain| YES      | string        | The domain that you want to create or change. |
|$attributes| YES      | array\|object | The list of fields to configure.              |
|$createOnly| NO       | bool          |Used to prevent changes to existing domains.|

### changeDomainBulletin

```php
$output = $opensrs->changeDomainBulletin(...);
```

#### Manual

https://email.opensrs.guide/docs/change_domain_bulletin

#### Arguments

|Argument| Required | Type   | Description                                                   |
|--------|----------|--------|---------------------------------------------------------------|
|$domain| YES      | string | The domain name.                                              |
|$bulletin| YES      | string | The name of the bulletin you want to create, edit, or delete. |
|$type| YES      |string|The bulletin delivery method.|
|$bulletinText| NO       |string|he text of the bulletin.|

### deleteDomain

```php
$output = $opensrs->deleteDomain(...);
```

#### Manual

https://email.opensrs.guide/docs/delete_domain

#### Arguments

|Argument| Required | Type   | Description                                                   |
|--------|----------|--------|---------------------------------------------------------------|
|$domain| YES      | string | The domain name.                                              |

### getDomain

```php
$output = $opensrs->getDomain(...);
```

#### Manual

https://email.opensrs.guide/docs/get_domain

#### Arguments

|Argument| Required | Type   | Description                                                   |
|--------|----------|--------|---------------------------------------------------------------|
|$domain| YES      | string | The domain name.                                              |

### getDomainBulletin

```php
$output = $opensrs->getDomainBulletin(...);
```

#### Manual

https://email.opensrs.guide/docs/get_domain_bulletin

#### Arguments

|Argument| Required | Type   | Description                                                   |
|--------|----------|--------|---------------------------------------------------------------|
|$domain| YES      | string | The domain name.                                              |
|$bulletin| YES      |string|The name of the bulletin you want to view.|
|$type| YES      |string|The type of bulletin.|

### postDomainBulletin

```php
$output = $opensrs->postDomainBulletin(...);
```

#### Manual

https://email.opensrs.guide/docs/post_domain_bulletin

### Arguments

|Argument| Required | Type   | Description                                    |
|--------|----------|--------|------------------------------------------------|
|$domain| YES      | string | The domain name.                               |
|$bulletin| YES      |string| The name of the bulletin you want to view.     |
|$type| YES      |string| The bulletin type.                             |
|$testEmail| NO        |string| Send the bulletin to only the specified email. |

### restoreDomain

```php
$output = $opensrs->restoreDomain(...);
```

#### Manual

https://email.opensrs.guide/docs/restore_domain

### Arguments

|Argument| Required | Type        | Description                                    |
|--------|----------|-------------|------------------------------------------------|
|$domain| YES      | string      | The domain name.                               |
|$newName|YES| string      |The new name for the domain.|
|$id|YES| string\|int |The domain id. |

### searchDomains

```php
$output = $opensrs->searchDomains(...);
```

#### Manual

https://email.opensrs.guide/docs/search_domains

#### Arguments

|Argument| Required | Type          | Description                        |
|--------|----------|---------------|------------------------------------|
|$criteria| NO       | array\|object | Narrows the results.               |
|$sort|NO| array\|object | Sort the return display.           |
|$rangeFirst|NO| int           | Specify the first domain to return |
|$rangeLimit|NO| int           |Specify the maximum number of results to return.|

### pushDomain

```php
$output = $opensrs->pushDomain(...);
```

#### Manual

https://email.opensrs.guide/docs/push_domain

#### Arguments

|Argument| Required | Type   | Description                                    |
|--------|----------|--------|------------------------------------------------|
|$domain| YES      | string | The domain name.                               |
|$newCompany| YES      | string |company_id of the company that will receive the domain and mailboxes.|

---
## Company Methods

### changeCompany

```php
$output = $opensrs->changeCompany(...);
```

#### Manual

https://email.opensrs.guide/docs/change_company

#### Arguments

|Argument| Required | Type          | Description                       |
|--------|----------|---------------|-----------------------------------|
|$company| YES      | string        |The name of the company.|
|$attributes| YES      | array\|object |A hash of company level attributes and values.|

### changeCompanyBulletin

```php
$output = $opensrs->changeCompanyBulletin(...);
```

#### Manual

https://email.opensrs.guide/docs/change_company_bulletin

#### Arguments

|Argument| Required | Type          | Description                                                             |
|--------|----------|---------------|-------------------------------------------------------------------------|
|$company| YES      | string        | The name of the company.                                                |
|$bulletin| YES      |string| The name of the bulletin you want to create, change, or delete.         |
|$type| YES      |string| Specify the bulletin type                                               |
|$bulletinText| NO       |string| The bulletin is sent only when the post_company_bulletin method is run. |

### getCompany

```php
$output = $opensrs->getCompany(...);
```

#### Manual

https://email.opensrs.guide/docs/get_company

#### Arguments

|Argument| Required | Type          | Description                                                             |
|--------|----------|---------------|-------------------------------------------------------------------------|
|$company| YES      | string        | The name of the company.                                                |

### getCompanyBulletin

```php
$output = $opensrs->getCompanyBulletin(...);
```

#### Manual

https://email.opensrs.guide/docs/get_company_bulletin

#### Arguments

|Argument| Required | Type          | Description              |
|--------|----------|---------------|--------------------------|
|$company| YES      | string        | The name of the company.|
|$bulletin| YES      |string|The name of the bulletin you want to view.|
|$type| YES      |string|Specify the bulletin type.|

### getCompanyChanges

```php
$output = $opensrs->getCompanyChanges(...);
```

#### Manual

https://email.opensrs.guide/docs/get_company_changes

#### Arguments

| Argument    | Required | Type   | Description              |
|-------------|----------|--------|--------------------------|
| $company    | YES      | string | The name of the company.|
| $rangeFirst | NO       | int    |The 0-based index of the first result to return.|
| $rangeLimit | NO        | int    |The maximum number of results to return.|

### postCompanyBulletin

```php
$output = $opensrs->postCompanyBulletin(...);
```

#### Manual

https://email.opensrs.guide/docs/post_company_bulletin-1

#### Arguments

| Argument    | Required | Type   | Description                                     |
|-------------|----------|--------|-------------------------------------------------|
| $company    | YES      | string | The name of the company.                        |
|$bulletin| YES      |string| The name of the bulletin you want to view.      |
|$type| YES      |string| Specify the bulletin type.                      |
|$testEmail| NO        |string| Sends the bulletin to only the specified email. |

---
## Brand Methods

### searchBrands

```php
$output = $opensrs->searchBrands(...);
```

#### Manual

https://email.opensrs.guide/docs/search_brands

#### Arguments

| Argument    | Required | Type          | Description|
|-------------|----------|---------------|------------|
| $criteria   | NO       | array\|object |Narrows the search for brands.|
| $rangeFirst | NO       | int           |The 0-based index of the first result to return.|
| $rangeLimit | NO       | int           |The maximum number of results to return.|

### searchBrandMembers

```php
$output = $opensrs->searchBrandMembers(...);
```

#### Manual

https://email.opensrs.guide/docs/search_brand_members

#### Arguments

| Argument    | Required | Type          | Description|
|-------------|----------|---------------|------------|
| $criteria   | NO       | array\|object |Narrows the search for brands.|
| $rangeFirst | NO       | int           |The 0-based index of the first result to return.|
| $rangeLimit | NO       | int           |The maximum number of results to return.|

---
## Workgroup Methods

### createWorkgroup

```php
$output = $opensrs->createWorkgroup(...);
```

#### Manual

https://email.opensrs.guide/docs/create_workgroup

#### Arguments 

| Argument    | Required | Type   | Description|
|-------------|----------|--------|------------|
|$domain| YES      | string |The domain under which you want to create the workgroup.|
|$workgroup| YES      | string |The name of the new workgroup.|

### deleteWorkgroup

```php
$output = $opensrs->deleteWorkgroup(...);
```

#### Manual

https://email.opensrs.guide/docs/delete_workgroup

#### Arguments

| Argument    | Required | Type   | Description|
|-------------|----------|--------|------------|
|$domain| YES      | string |The domain under which you want to create the workgroup.|
|$workgroup| YES      | string |The name of the new workgroup.|

### searchWorkgroups

```php
$output = $opensrs->searchWorkgroups(...);
```

#### Manual

https://email.opensrs.guide/docs/search_workgroups

#### Arguments

| Argument    | Required | Type          | Description                           |
|-------------|----------|---------------|---------------------------------------|
|$criteria| NO        | array\|object | Narrows the results.                  |
|$sort|NO| array\|object | How to sort display results           |
|$rangeFirst|NO| int           | Specify the first workgroup to return |
|$rangeLimit|NO| int           |Specify the maximum number of results to return.|

---
## Authentication Methods

### authenticate

```php
$output = $opensrs->authenticate(...);
```

#### Manual

https://email.opensrs.guide/docs/authenticate

#### Arguments

| Argument    | Required | Type   | Description                                      |
|-------------|----------|--------|--------------------------------------------------|
|$token| NO       | string | Specify the token that you want to use.          |
|$fetchExtraInfo|NO| bool   | Returns additional information about the user.   |
|$generateSessionToken|NO| bool   | Returns a session token.                         |
|$sessionTokenDuration|NO| int    | Specify how long session token lasts in seconds. |

### echo

```php
$output = $opensrs->echo(...);
```

#### Manual

https://email.opensrs.guide/docs/echo

#### Arguments

| Argument    | Required | Type   | Description                                      |
|-------------|----------|--------|--------------------------------------------------|
|$data| YES      |array\|object|Payload to echo back.|

---
## Migration Methods

### migrationAdd

```php
$output = $opensrs->migrationAdd(...);
```

#### Manual

https://email.opensrs.guide/docs/migration_add

#### Arguments

| Argument    | Required | Type          | Description                                                 |
|-------------|----------|---------------|-------------------------------------------------------------|
|$users| YES      | array\|object | Defines the source and destination of the email to migrate. |
|$job| NO       | string\|int   |A job ID.|

### migrationJobs

```php
$output = $opensrs->migrationJobs(...);
```

#### Manual

https://email.opensrs.guide/docs/testinput-1

#### Arguments

No arguments

### migrationStatus

```php
$output = $opensrs->migrationStatus(...);
```

#### Manual

https://email.opensrs.guide/docs/migration_status

#### Arguments

| Argument | Required | Type        | Description|
|----------|----------|-------------|------------|
| $id      | YES      | string\|int |The ID of the job you are querying.|

### migrationTrace

```php
$output = $opensrs->migrationTrace(...);
```

#### Manual

https://email.opensrs.guide/docs/migration_trace

#### Arguments

| Argument | Required | Type        | Description                         |
|----------|----------|-------------|-------------------------------------|
| $job     | YES      | string\|int | The ID of the job you are querying. |
| $user    | YES      | string      | The username of the migration job.  |