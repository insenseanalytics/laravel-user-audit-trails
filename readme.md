# Insense/LaravelUserAuditTrails

![Travis (.org)](https://img.shields.io/travis/insenseanalytics/laravel-user-audit-trails/master.svg)
[![License](https://img.shields.io/github/license/mashape/apistatus.svg)](https://packagist.org/packages/insenseanalytics/laravel-user-audit-trails)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/insenseanalytics/laravel-user-audit-trails/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/insenseanalytics/laravel-user-audit-trails/?branch=master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/insenseanalytics/laravel-user-audit-trails/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

This package is created to add audit user trails by using Laravel Eloquent ORM. Using this package, you would be able to record in the respective database tables, the `created_by` and `updated_by` user IDs.

## Basic Example

Add the audit trail columns in your database **migrations** like so:

```php
$table->usertrails(); // for created_by, updated_by
$table->deletetrails(); // for deleted_by
```

Next, in your model just use the `HasUserTrails` and `HasDeleteTrails` trait to automatically setup audit user trails like so:
```php
class Post extends \Illuminate\Database\Eloquent\Model
{
    use \Insense\LaravelUserAuditTrails\HasUserTrails;
    use \Insense\LaravelUserAuditTrails\HasDeleteTrails;
}
```

That's it! Now, sit back and observe the magic of audit user trails. When a new record is created, `created_by` will be updated to the user ID that created it. When a record is updated, `updated_by` will be updated to the user ID that updated it. When a record is soft deleted, `deleted_by` will be updated to the user ID that deleted it.

## Requirements
- [PHP >= 7.1](http://php.net/)
- [Laravel 5.5|5.6|5.7](https://github.com/laravel/framework)

## Quick Installation
```bash
$ composer require insenseanalytics/laravel-user-audit-trails
```

#### Service Provider (Optional / auto discovered on Laravel 5.5+)
Register provider on your `config/app.php` file.
```php
'providers' => [
    ...,
    Insense\LaravelUserAuditTrails\UserTrailsServiceProvider::class,
]
```

## Setting Up Custom Column Names
If you want to override the default audit trail names of `created_by`, `updated_by` and `deleted_by`, you may do so like so:

In your database **migration**, add the audit trail columns like so:
```php
$table->usertrails('your-created-by-column', 'your-updated-by-column');
$table->deletetrails('your-deleted-by-column');
```

Next, in your model, override the static properties `CREATED_BY`, `UPDATED_BY` and `DELETED_BY`. Note that PHP does not allow overriding static properties in the same class, so you would need to extend your model class from a base model class that uses the `\Insense\LaravelUserAuditTrails\HasUserTrails` trait like so:

First create your base model class (if not already created). If already created, just add the trait.

```php
class BaseModel extends \Illuminate\Database\Eloquent\Model
{
    use \Insense\LaravelUserAuditTrails\HasUserTrails;
}
```
Next, override the static properties `CREATED_BY`, `UPDATED_BY` and `DELETED_BY` in your model (that extends the base model) like so:

```php
class YourModel extends BaseModel
{
    public static $CREATED_BY = 'your-created-by-column';
    public static $UPDATED_BY = 'your-updated-by-column';
    public static $DELETED_BY = 'your-deleted-by-column';
}
```
 
## Omitting Updated By or Created By Columns
If you wish to omit one of the audit trail columns, you can just set the one you would like to omit to null in your database **migration** like so:

```php
$table->usertrails('created_by', null);
```
The example above omits the updated_by column. You can also do the reverse to omit updated_by by setting the first argument to null.

Next, override the static properties `CREATED_BY` and `UPDATED_BY` in your model (that extends the base model) to set the omitted property to null like so:

```php
class YourModel extends BaseModel
{
    public static $CREATED_BY = 'created_by';
    public static $UPDATED_BY = null;
}
```

## Contributing
We are open to PRs as long as they're backed by tests and a small description of the feature added / problem solved.

## License

The MIT License (MIT). Please see [License File](https://github.com/insenseanalytics/laravel-user-audit-trails/blob/master/LICENSE.txt) for more information.
