# Test Project

## Installation
The project using docker. So go to the folder which is path {projectPath}/{projectName}/docker and run the following command
```bash
docker-compose up --build -d
```

If you run the docker-compose up --build command docker will trigger following commands. So you don't need to run these commands. 
```bash
composer install
php artisan migrate
php artisan db:seed
php artisan passport:install (It will generate client id and secret)
```

## Usage
There are APIs for products and user login. The project using [Laravel Passport](https://laravel.com/docs/7.x/passport) for API Authentication.
You can see the API requests on the following examples.
```bash
# POST - {{local}}/api/login
email:fatih@test.com
password:123456
It returns the token, user name and email.

# GET - {{local}}/api/product/{identifier}
It returns product which identifier is equal with given

# POST - {{local}}/api/store-product
name:Product Name
identifier:PRODUCT01
description:Product Description
categories:Category / Category, Category3
prices:9.90|2021-02-01 00:00:00|2021-02-28 23:59:59, 9.95|2021-03-01 00:00:00|2021-03-28 23:59:59
images:https://image.com/test1.jpg, https://image.com/test2.jpg, https://image.com/test3.jpg

```

The project fetch product data every 2 hours from the SFTP Server. I used [Rebex Tiny SFTP Server](https://www.rebex.net/tiny-sftp-server/) to test the process.
The project login to another server with informations and export product data to another server.


## Developer
[Fatih Yılmaz](https://fatihyilmaz.info/)
