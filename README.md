Problem Roulette
================

## Installation (For Development)

For development purposes, follow these steps to easily set up a virtual machine
(requires [Vagrant](https://www.vagrantup.com/)) running Problem Roulette:

0. If you need sample data to work with, get `pr.sql` and install it in the `sql` directory.
0. `vagrant up`
0. `vagrant ssh`
0. `cd /var/www/public`
0. `source provision.sh`

    * Ideally, this should be done automatically as part of `vagrant up`.  However, when the provisioning
      commands were added to `Vagrantfile`, they didn't work correctly.  That needs to be fixed at some
      point, but for now, this workaround is sufficient.
    * This script sets the `REMOTE_USER` to `jtritz`.  If you need to set it to some other user, edit
      the name shown in the definition of `REMOTE_USER_STATEMENT`.

0. `sudo apache2ctl graceful`
0. In a web browser, connect to http://192.168.33.10/.

    This IP address is in a range reserved for local use and 
    doesn't require you to have an Internet connection.  Most likely it 
    will not conflict with other IP addresses on your network, but if you 
    would like to change it, you may edit `Vagrantfile` and enter a different 
    address. 


## Dependency Management via Composer for managing the external libraries like Caliper.

1. Download Composer and install it. For installation details, see: https://getcomposer.org/download/. 
   The instructions specifies to install locally as part of your project, or globally as a system wide executable. 
   This Document gives instruction with Global.
2. Run `composer install`. This will download the libraries that are mentioned in the composer.json(located at
   the project root directory) to  `vendor` directory( generated as part of composer).
3. **_Only_** use `composer update` to add new dependencies to the project.

    🚨 The `composer update` command must **_only_** be used when it's **_necessary_** to make changes to the `composer.lock` file. 
    
    After the project's dependencies are settled, `composer.json` has not been changed, 
    and `composer.lock` has been committed, it's important to not use the `composer update` command. 
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
     
     // This property controls if viadutoo should be used to send caliper events.
     $GLOBALS["CALIPER_PROXY_ENABLED"] = true;
     
     // this is the loop back url that is looking back to the local machine the application is hosted
     $GLOBALS["CALIPER_PROXY_ENDPOINT_URL"]=null; //eg, "http://127.0.0.1:8888/problemroulette/caliper_proxy.php/";
     
     // this is the directory to the CA certificate to send the events to the OpenLRS event store. for development purposes this can be any string. 
     $GLOBALS["CA_CERTS_PATH"]=null; // eg: "/etc/pki/tls/certs/";
     
     //OAuth key that an event store uses to allow caliper event. This must be a string
     $GLOBALS["VIADUTOO_REMOTE_ENDPOINT_OAUTH_KEY"]=null;
     
     //OAuth secret that an event store uses to allow caliper event. This must be a string
     $GLOBALS["VIADUTOO_REMOTE_ENDPOINT_OAUTH_SECRET"]=null;
  
### More info on caliper-php sensor go to https://github.com/IMSGlobal/caliper-php-public   

