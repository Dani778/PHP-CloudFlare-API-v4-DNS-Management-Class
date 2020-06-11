# PHP CloudFlare API v4 DNS Management Class
Light-weight &amp; easy class written in PHP to manage DNS in Cloudflare

## Features:
* Add Record
* Edit Record
* Rename Record
* Delete Record
* Get Zone ID from domain name
* Record details
* List All Records In Zone

# Documentation



## Getting Started

To start working with class we must require class file

```php
require_once( __DIR__ . "cloud.php" );
```

Now we have included class file, but we must create class object.
We have two params in object constructor, $mail is Your email which used to create CloudFlare account and
$apikey is Your CloudFlare api key which you can retrieve from Your CloudFlare dashboard

```php
$api = new CloudFlareAPI($mail,$apikey);
```
In order to manage records we must select zone.
We can do this with function setZone().
This function have one parameter what is $domain_name

```php
$api -> setZone("example.com");
```
!!!! IMPORTANT !!!!

Off error reporting because class sometimes generates notice

```php
error_reporting(0);
```

## Functions

### Get Zone ID

```php
$result = $api -> getZoneID($domain_name);
```

#### Parameters:
- $domain_name - Domain Name ex. example.com

#### Return:
- Zone ID

### Record Details

```php
$result = $api -> getRecordInfo($name);
```


#### Parameters:
- $name - Name of record  ex. www.example.com

#### Return:
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

### List All Records

```php
$result = $api -> ListAllRecords();
```

#### Return:
- Multidimensional Array :
```php
$return[index of table] = [
	"id"
	"type"
	"name"
	"content"
	"proxied"
	"ttl" 
];
```

### Add Record

```php
$result = $api -> AddRecord($type,$name,$content,$ttl,$cloudflare_proxy);
```


#### Parameters:
- $type - Record Type
- $name - Record Name
- $content - Record Content
- $ttl - 1 for auto
- $cloudflare_proxy - CloudFlare proxy , true is yes , false is no

#### Return:
- Boolean true/false 

### Edit Record

```php
$result = $api ->  EditRecord($old_name,$type,$name,$content,$ttl,$cloudflare_proxy);
```


#### Parameters:
- $old_new - Old Record Name
- $type - Record Type
- $name - New Record Name
- $content - Record Content
- $ttl - 1 for auto
- $cloudflare_proxy - CloudFlare proxy , true is yes , false is no

#### Return:
- Boolean true/false 

### Delete Record

```php
$result = $api ->  DeleteRecord($name);
```


#### Parameters:
- $name - Record Name

#### Return:
- Boolean true/false 



















