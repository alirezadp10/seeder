# Seeder
## _Made Laravel Seeding More Useful_

### :disappointed: Problem

Assume you have an application and in the middle of developing you should inject some data in your existing tables.

So you write your seeder and put insertion query and do your right thing and in the end you come to a question.

**How run this seeder in different environments only once and not more?**

The problem is this data should insert to all environments And you don't sure this seeder doesn't execute more than once.

### :collision: Solution

Seeder is a layer that changes Laravel seeding behavior slightly.

By the way, the seeding approach that is currently used in laravel, doesn't have support for seeding classes one time per running.

With this package you can define your seeders like before, but the task of executing them, is the responsibility of `php artisan seed` command.

By doing that you can put `seed` command in your CI configuration without worrying about some seeders execute several times.

### :inbox_tray: Installation
You can install the package via composer:
```
composer require alirezadp10/seeder
```
Create seeds table:
```
php artisan migrate
```
You can also migrate previous seeders file to new style if you want:
```
php artisan seeder:update
```

### :exclamation: Notice
After installing this package your seeder files generate like migration files, which you are already familiar with them.

### License
MIT