[![Build Status](https://semaphoreci.com/api/v1/projects/eb39bc95-8a33-4582-8203-d7fd3676b53d/2300257/badge.svg)](https://semaphoreci.com/cleaniquecoders/oomrah)

## Oomrah

### Installation

1. Import `database/sql/table.sql` and `database/sql/data.sql` into database.
1. Clone this directory
2. `cp .env.example .env`
3. `php artisan key:generate`
4. Update `.env` file - database config and `APP_URL`.

### Development 

Run `php artisan seed:dev` will update all current owners subdomain to following format:

```
app-1.oomrah.app
app-2.oomrah.app
app-3.oomrah.app
app-4.oomrah.app 
```

Where `oomrah.app` is the `APP_URL` value.

This command also reset all users password to `secret`.

#### MacOS

Once you have done setup above step, you may link and secure the domain name you want to use, for instance:

```
$ cd path/to/project/public
$ valet link app-1.ommrah && valet secure app-1.oomrah 
```

Once you are done, you can navigate to `app-1.oomrah.app` for the first owner.

#### Windows 

It is recommended to use [Laragon](https://laragon.org).

> TODO: Developer using Laragon need to update this section.

#### Available Custom Artisan Commands

**Clear all caches**

```
$ php artisan reload:cache
```

**Remigrate tables and seed data**

```
$ php artisan reload:db
```

Since we are not dependent on Laravel Seeders, developers required to import manually data into database. SQL File located at `database/sql/data.sql`.

Pass `-d` option to allow development data to be seed.

```
$ php artisan reload:db -d 
```

**Reload Caches and Database**

```
$ php artisan reload:all 
```

Pass `-d` option to allow development data to be seed.

```
$ php artisan reload:all -d
```