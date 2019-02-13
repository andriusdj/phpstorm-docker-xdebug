[![Build Status](https://travis-ci.org/andriusdj/phpstorm-docker-xdebug-supermetrics-task.svg?branch=master)](https://travis-ci.org/andriusdj/phpstorm-docker-xdebug-supermetrics-task)

# Coding task
For Supermetrics

fork from supermetrics/phpstorm-docker-xdebug in order see how working with similar environment would be.. (see notes)

## How to use

- Build the docker containers using the docker-compose yaml files or add to PhpStorm and let it do it for you
- Visit http://localhost:8811/ or phpstorm with xdebug
- the following query parameters are available:
  - `max-pages`, limit number of pages to process and show stats for
- Saves two files to /tmp in docker:
  - /tmp/app_store.json - serialized storage of api data
  - /tmp/CompiledContainer.php - some useless DI container which is more or less empty :-)

## Some development info

Work has been done on Debian 9.7 GNU/Linux computer with PhpStorm.

Written in PHP without a framework using the following libraries:

- Guzzle - Guzzle Curl wrapper to make requests more OOP-friendly
- PHP-DI - dependency injection container with autowiring
- PHPUnit - Unit testing framework (used TDD approach)
- PHPStan - static analysis tool

It uses a filesystem persistence layer to save data from API so that it is not bombed with unnecessary requests.
It also uses an in-process memory caching to not bomb the filesystem too much too. 

Uses 

## Notes

- PhpStorm + Docker + Xdebug did not work as shown during the VilniuPHP presentation on Debian Stretch GNU/Linux...
- Includes API token in the code which should never be done in FOSS/Production systems - this should be done in a separate config file which is not commitable to git

