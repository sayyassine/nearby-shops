# Nearby Shops
This is a single page application built with Symfony and Vue. Offering to users to find stores nearby
## Built With

* [Symfony](https://symfony.com/) - PHP web framework used
* [Vue.JS](https://maven.apache.org/) - Javascript framework used to build the front end
* [Composer](https://getcomposer.org/) - PHP depency manager
* [Node.js](https://nodejs.org/) - A JavaScript runtime used for building assets
* [npm](https://npmjs.org/) - NodeJs Packet Manager
## Requirements 
Symfony: 
* PHP version: 7.1.3 or higher
* PHP extensions: (all of them are installed and enabled by default in PHP 7+)
  * Ctype
  * iconv
  * JSON
  * PCRE
  * Session
  * SimpleXML
  * Tokenizer
* Writable directories: (must be writable by the web server)
  * The project's cache directory (var/cache/ by default, but the app can override the cache dir)
  * The project's log directory (var/log/ by default, but the app can override the logs dir)
  
To automatically check the requirments you can use composer 
```bash
$  cd your-project/
$ composer require symfony/requirements-checker
```
Database:
* MySQL Database: 5.7 or higher  

## Compatibilty
This project is compatible with all [ECMAScript 5 compliant browsers](https://caniuse.com/#feat=es5), althought IE8 is not supported.
  

## Instalation
Clone this repository into a directory
```bash
$ cd projects_dir/
$ git clone https://github.com/sayyassine/nearby-shops.git 
```

Then install php depencies using composer :
```bash
$ cd projects_dir/
$ composer install 
```

Create a local config configuration file .env.local where you add your local database crendentials 
```
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7
```

Since the project is still under developement you may need to rebuild the assets using npm
```
$ cd projects_dir/
$ npm run build
```
## Getting Started
Project still under developpement, will be available soon.
