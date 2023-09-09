# This is my package lms-redis

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elsayed85/lms-redis.svg?style=flat-square)](https://packagist.org/packages/elsayed85/lms-redis)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/elsayed85/lms-redis/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/elsayed85/lms-redis/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/elsayed85/lms-redis/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/elsayed85/lms-redis/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/elsayed85/lms-redis.svg?style=flat-square)](https://packagist.org/packages/elsayed85/lms-redis)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/lms-redis.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/lms-redis)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can
support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.
You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards
on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation On Each Service

You can install the package via composer:

```bash
composer require elsayed85/lms-redis "@dev"
```

### Config File [Required]

You Must publish the config file with:

```bash
php artisan vendor:publish --provider="Elsayed85\LmsRedis\LmsRedisServiceProvider" --tag="lms-redis-config"
```

This is the contents of the published config file:

```php
return [
    "service" => \Elsayed85\LmsRedis\LmsRedis::class,
];
```

Replace the service with the project redis service class (Created For You)

### Consume Command [Optional]

Also You Must Publish The Consume Command If You want To Handel The Incoming Redis Stream Events

```bash
php artisan vendor:publish --provider="Elsayed85\LmsRedis\LmsRedisServiceProvider" --tag="lms-redis-consume-command"
```

NOTE : You need to schedule function in App\Console\Kernel.php

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('lms-redis:consume')->everyMinute();
}
```

## Development

### First : clone the repo

```bash
git clone https://github.com/elsayed85/laravel-microservices-redis.git
```

### Second : install the dependencies

```bash
composer install
```

### Create New Service

```bash
php artisan make:service {service_name}
```

Example :

```bash
php artisan make:service Product
```

This will create src\Services\ProductService and this folders will be created by default

```bash
src
├── Services
    └── ProductService
        ├── DTO
        │   └── ProductData.php
        ├── Enum
        │   └── ProductEvent.php
        ├── Event
        │   └── ProductCreatedEvent.php
        │   └── ProductUpdatedEvent.php
        │   └── ProductDeletedEvent.php
        ├── ProductRedisService.php
```

## Usage

#### Extend The Service Class

```php
<?php

namespace app\Services;

use Elsayed85\LmsRedis\Services\ProductService as BaseRedisService;
use Elsayed85\LmsRedis\Services\ProductService\Event\ProductCreatedEvent;
use Elsayed85\LmsRedis\Services\ProductService\DTO\ProductData;

class ProductService extends BaseRedisService
{
    public function publishProductCreated(ProductData $data): void
    {
        $this->publish(new ProductCreatedEvent($data));
    }
}

```

### Creating Actions

```php
<?php

namespace App\Actions;

use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;

class CreateProductAction
{
    public function __construct(private readonly ProductService $redis)
    {
    }

    public function execute(string $name, string $description, float $price): Product
    {
        $product = Product::create([
            'name' => $name,
            'description' => $description,
            'price' => $price
        ]);

        $this->redis->publishProductCreated(
            $product->toData(),
        );

        return $product;
    }
}
```

#### ADD toData() function to your model

```php
use Elsayed85\LmsRedis\Services\ProductService\DTO\ProductData;

class Product extends Model
{
    use HasFactory;

    public function toData(): ProductData
    {
        return new ProductData(
            id : $this->id,
            name : $this->name,
            description : $this->description,
            price : $this->price,
        );
    }
}
```

### AddAction To Controller

```php
use App\Actions\CreateProductAction;
use App\Http\Requests\StoreProductRequest;

class ProductController extends Controller
{
    public function store(StoreProductRequest $request, CreateProductAction $createProduct)
    {
        $product = $createProduct->execute(
            $request->getName(),
            $request->getDescription(),
            $request->getPrice()
        );

        return response([
            'data' => $product->toData()
        ], Response::HTTP_CREATED);
    }
}
```

### Add Api Endpoint To Routes

```php
use App\Http\Controllers\ProductController;

Route::post('/v1/products', [ProductController::class, 'store']);
```

And That's It!

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Elsayed Kamal](https://github.com/elsayed85)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
