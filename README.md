## Dependency Management via Composer for managing the external libraries like Caliper.

1. Download Composer and install it. For installation details, see: https://getcomposer.org/download/. 
   The instructions specifies to install locally as part of your project, or globally as a system wide executable. 
   This Document gives instruction with Global.
2. Run `composer install`. This will download the libraries that are mentioned in the composer.json(located at
   the project root directory) to  `vendor` directory( generated as part of composer).
3. Use `composer update ` to add new dependencies to the project. `composer update` should only be used when it's important to make changes to the `composer.lock` file. 
   Once the project's dependencies are settled, `composer.json` has not been changed, and `composer.lock` has been committed, it's important to not use the `composer update` command. 
   In that case, use the `composer install` command instead.

##  Enable caliper service in the application.

4.  Caliper capture and measure the learning activity. In PR caliper captures the information like a new problemSet#Start, problem#Start, problem#Complete by a student and more converts it to a  json object and send 
    it to an Event store. More information on Caliper goto http://www.imsglobal.org/activity/caliperram.    
    To enable caliper in PR, 
   `$GLOBALS["CALIPER_ENABLED"] = true;` in the `paths.inc.php` file in the project
   
##  Enable Viadutoo in the application.

5.  Viadutoo is a proxy caliper endpoint which quickly accepts the events and close the connection from the request and after try to send the events to the
    final Caliper End point. In case of any network issues to the Caliper event store it will store the information to the local database. The advantage of using the Viadutoo 
    in case of network slow down/ or error at event store 1) users won't be effected by this, 2) events won't be lost and stored locally. More info on viadutoo https://github.com/tl-its-umich-edu/viadutoo
    To enable viadutoo `$GLOBALS["CALIPER_PROXY_ENABLED"] = true;` 

### Give the below variables appropriate value in the 'path.inc.php' file in project for configuring caliper and viadutoo.


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
     
     // This property controls if viadutoo should be used to send caliper events.
     $GLOBALS["CALIPER_PROXY_ENABLED"] = true;
     
     // this is the loop back url that is looking back to the local machine the application is hosted
     $GLOBALS["CALIPER_PROXY_ENDPOINT_URL"]=null; //eg, "http://127.0.0.1:8888/problemroulette/caliper_proxy.php/";
     
     // this is the directory to the CA certificate to send the events to the OpenLRS event store. for development purposes this can be any string. 
     $GLOBALS["CA_CERTS_PATH"]=null; // eg: "/etc/pki/tls/certs/";
  
### More info on caliper-php sensor go to https://github.com/IMSGlobal/caliper-php-public   

