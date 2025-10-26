# OpenSRS Email REST Client

## Description

This is a small lightweight API client for the OpenSRS Email API. Once configured it allows you to only have to worry about the API method and data. 

## API Documents

https://email.opensrs.guide/docs/

## Settings

There are two ways to configure the client. One is to provide them in the constructor when instantiating an object, The other is by using environment variables in the program calling this library.

### Environment Variables

See .env.example

- OPENSRS_MAIL_SERVER - The URL of the OpenSRS API server
- OPENSRS_MAIL_USER - The username of your OpenSRS account
- OPENSRS_MAIL_PASS - The password of your OpenSRS account

## Examples

### Creating Client with constructor arguments

```php
// Create client using API info
$client = new Ocolin\OpenSrsMail\Client(
    base_uri: 'https://admin.test.hostedemail.com/api/',
    user: 'myusername@example.com',
    pass: 'MyPassword'
);

// Setup payload to send to API
$payload = [
    'fetch_extra_info' => true,
    'generate_session_token' => false,
    'token' => 'lkjhfjhksdjhfds',
];

// Specify the method name of the API, and provide the payload
$output = $client->call( method: 'authenticate', payload: $payload )->body;
```

### Creating Client with Environment variables

```php
// Set up environment vaiebales somewhere
$_ENV['OPENSRS_MAIL_SERVER'] = 'https://admin.test.hostedemail.com/api/';
$_ENV['OPENSRS_MAIL_USER'] = 'myusername@example.com';
$_ENV['OPENSRS_MAIL_PASS'] = 'MyPassword';
    
// Create your API client
$client = new Ocolin\OpenSrsMail\Client();

// Create your payload to send to the API
$payload = [
    'fetch_extra_info' => true,
    'generate_session_token' => false,
    'token' => 'lkjhfjhksdjhfds',
 ];
 
// Make call to client specifying which method, and the payload to send
$output = $client->call( method: 'authenticate', payload: $payload )->body;
```