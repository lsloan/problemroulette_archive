## Dependency Management via Composer for managing the external libraries.

1. Download Composer and install it. For installation details, see: https://getcomposer.org/download/. 
   The instructions specifies to install locally as part of your project, or globally as a system wide executable. 
   This Document gives instruction with Global.
2. For the first time use the command `composer install`. 
   This will download the libraries that are mentioned in the composer.json(located at
   the project root directory) to a `vendor` directory.
3. Use `composer update` to if any of the dependencies get a new version, you won't get the updates automatically. 

##  To access the caliper repo in the project. Below two lines describes the wiring of it

4. Add the below line to the `paths.inc.php` file
   `$GLOBALS["DIR_COMPOSER_LIB"] =$GLOBALS["DIR"]."vendor/";`

5. Add the below line to `setup.php` to make the caliper source available to ProblemRoulette. To enable below line needs `vendor` directory where all the caliper source
   is located otherwise it will throw a exception saying this file is missing
   `require_once($GLOBALS["DIR_COMPOSER_LIB"]."autoload.php");`
   
6. More info on caliper-php sensor go to https://github.com/IMSGlobal/caliper-php-public   

7. add `vendor/` to your global .gitignore file. This directory should not be checked in to the source.
