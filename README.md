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

Create a local file where your can put you google map api key under assets/config/keys.js with the following
```
export default {
    GOOGLE_API_KEY : "YOUR API KEY"
};
```

Build the assets using npm
```
$ cd projects_dir/
$ npm run build
```

You can run the available fixtures to generate somme dummy data for testing (Dummy stores and dummy store types)
```
$ cd projects_dir/
$ php bin/console doctrine:fixtures:load --group=store
``` 
Or you can create your own stores and store types in the database.

Some server configuration may be needed for the deployement 
You can follow the [Symfony doplyement guide](https://symfony.com/doc/current/deployment.html) to get it done.
## Description
The application is a single page application where you can create an account and login and vizualize a list of nearby stores. You can add the stores you like to your favourites list and dislike store you don't like. You can even see you the stores location on the map. 

## TODO 
A lot of features still can be added to the project like : 
* Admin store creation 
* Admin store management (Activation and Deactivation) 
* Store pages 
* User profile page
* ...
These features and many others may be added in a near future.
