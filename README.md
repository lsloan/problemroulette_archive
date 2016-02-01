## Dependency Management via Composer for managing the external libraries like Caliper.

1. Download Composer and install it. For installation details, see: `https://getcomposer.org/download/`. 
   The instructions specifies to install locally as part of your project, or globally as a system wide executable. 
   This Document gives instruction with Global.
2. Run `composer install`. This will download the libraries that are mentioned in the composer.json(located at
   the project root directory) to  `vendor` directory( generated as part of composer).
3. Use `composer update ` to add new dependencies to the project. `composer update` should only be used when it's important to make changes to the `composer.lock` file. 
   Once the project's dependencies are settled, `composer.json` has not been changed, and `composer.lock` has been committed, it's important to not use the `composer update` command. 
   In that case, use the `composer install` command instead.

##  Enable caliper service in the application.

4.  If you want to enable caliper, then set the 
   `$GLOBALS["CALIPER_ENABLED"] = true;` in the `paths.inc.php` file in the project

5. Give the below 5 global variable appropriate value in the 'path.inc.php' file in project for configuring caliper.


     /*caliper needs to send event data to a Event store, for development purposes you can use Requestb.in or 
     the below mentioned url to send event acting as a event store*/
     $GLOBALS["CALIPER_ENDPOINT_URL"]  = "http://lti.tools/caliper/event?key=problemroulette"

     //this is the apikey that is appropriate to the event store that is agreed upon. For development purpose this can be any string. 
     $GLOBALS["CALIPER_API_KEY"]       = "apikey";
        
     //Sensor id unique to application this. For development purposes this can be any string.
     $GLOBALS["CALIPER_SENSOR_ID"]     = "ProblemRoulette";
     
     //ProblemRoulette is sending the caliper event over the wire using the http protocol. So this id should be unique for the application.
     $GLOBALS["CALIPER_HTTP_ID"] = "PRCaliperHttp";
     
     //Caliper client id and should be unique for application. 
     $GLOBALS["CALIPER_CLIENT_ID"] = "PRCaliperClient";
  
### More info on caliper-php sensor go to https://github.com/IMSGlobal/caliper-php-public   

