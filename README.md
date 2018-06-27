# PHP-CloudFlare-API-v4-DNS-Records-Management-Class
Light-weight &amp; easy class to manage Your DNS Records using CloudFlare API v4

## Features:
* Add Record
* Edit Record
* Rename Record
* Delete Record
* Get Zone ID from domain name
* Record details
* List All Records In Zone

## Documentation

### Getting Started

To start working with class we must require class file

```php
require_once( __DIR__ . "cloud.php" );
```

Now we have included class file but we must create class object
We have two params in object constructor , $mail is Your email which used to create CloudFlare account and
$apikey is You CloudFlare Api Key which you can retrieve from Your CloudFlare dashboard

```php
$api = new CloudFlareAPI($mail,$apikey);
```
In Order to manage records we must select zone.
We do this with function setZone().
This function have one parameter what is $domain_name

```php
$api -> setZone("example.com");
```
### Functions

#### Get Zone ID

```php
$result = $api -> getZoneID($domain_name);
```

##### Parameters:
- $domain_name ex. example.com

##### Return:
- Zone ID

#### Record Details

```php
$result = $api -> getRecordInfo($name);
```

##### Parameters:
- $name ( of record ) ex. www.example.com

##### Return:
- Array :
```php

$return = [
  "id",
  "type",
  "name",
  "data",
  "content",
  "proxied",
  "ttl
];
```




















