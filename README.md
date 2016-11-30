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

## Viadutoo support for Redis

The Resque worker program periodically checks the specified Resque queue in
Redis and attempts to process the jobs it finds there. Resque workers will
instantiate an object of jobs' classes, set the job arguments in the object's
`args` property, and run their `perform()` method.

Invoked as:

```sh
php ResqueWorker.php
```

Since PHP Resque uses environment variables to configure the worker, they
will work with this program, too.  There's one difference:  If the queue
name is not specified in the "QUEUE" environment variable, it will be set
to 'default'.  That allows a short commandline when invoking this program.

Other possibly useful environment variables used by Resque workers:

* `COUNT` (default: 1)

    Number of worker processes to fork.

* `PIDFILE` (default: none)

    Pathname of file to write the process ID of worker.

See the PHP Resque documentation in README.md and the source of bin/resque
for further details on available configuration options:

* https://github.com/chrisboulton/php-resque/blob/master/README.md
* https://github.com/chrisboulton/php-resque/blob/master/bin/resque

To require PHP Resque with Composer, the following needs to be added to the `repositories`
section of `composer.json`:

```json
{
  "repositories": [
    {
      "comments": [
        "TL fork of chrisboulton/php-resque to tag specific non-release revision",
        "TL's 1.2.9999 -> chrisboulton's dev-master#cf187fa"
      ],
      "type": "vcs",
      "url": "https://github.com/tl-its-umich-edu/php-resque"
    }
  ]
}
```

Then in the `require` section, add the **_original_** package name and the special
release tag name defined in the TL repository:

```json
{
  "require": {
    "chrisboulton/php-resque": "1.2.9999"
  }
}
```

This is because PHP Resque doesn't have a release tag that includes the features needed by Problem Roulette.
Requiring `dev-master` would work, but it would allow Composer to accept any future changes from PHP Resque, 
even those that could break Problem Roulette.  The new release tag ensures that Problem Roulette gets exactly
the version of PHP Resque it needs.

> **_Note_**: A lot of time was spent (wasted?) trying to set up a TL fork of PHP Resque 
> that would have a release named "problemroulette-2.3.0".  The hope was to reference it as
> `"tl-its-umich-edu/php-resque": "problemroulette-2.3.0"`.  However, several problems occurred:
> 
> * Composer didn't consider that release's tag name to be a valid version string.
> * Using the ZIP file URL of the TL release to refer to the package as an archive 
>   would get the master branch of the original package if not referenced in a very
>   specific, complicated way.
> * When Composer did get the package from the TL release, it wouldn't interpret the
>   package's `composer.json` file and install its dependencies, resulting in an 
>   incomplete installation.
> 
> Although possibly confusing, it seems best to create a version-like release tag referring to the current 
> commit of the original package's master branch instead.

0. Enable Dependency Management via Composer
    0. Download Composer and install it. For installation details, see: https://getcomposer.org/download/. 
       The instructions specifies to install locally as part of your project, or globally as a system wide executable. 
       This Document gives instruction with Global.
    0. Run `composer install`. This will download the libraries that are mentioned in the composer.json(located at
       the project root directory) to  `vendor` directory( generated as part of composer).
    0. **_Only_** use `composer update` to add new dependencies to the project.
    
        🚨 The `composer update` command must **_only_** be used when it's **_necessary_** to make changes to the `composer.lock` file. 
        
        After the project's dependencies are settled, `composer.json` has not been changed, 
        and `composer.lock` has been committed, it's important to not use the `composer update` command. 
        In that case, use the `composer install` command instead.

0. Enable Caliper Event Logging
    0.  Caliper capture and measure the learning activity. In PR Caliper captures the information like a new problemSet#Start, problem#Start, 
        problem#Complete by a student and more converts it to a  json object and send 
        it to an Event store. More information on Caliper goto http://www.imsglobal.org/activity/caliperram.    
        To enable Caliper in PR, 
       `$GLOBALS["CALIPER_ENABLED"] = true;` in the `paths.inc.php` file in the project
   
0. Enable Viadutoo Proxying for Caliper
    0.  Viadutoo is a proxy Caliper endpoint which quickly accepts the events and close the connection from the request and after try to send the 
        events to the
        final Caliper End point. In case of any network issues to the Caliper event store it will store the information to the local database. The 
        advantage of using the Viadutoo 
        in case of network slow down/ or error at event store 1) users won't be effected by this, 2) events won't be lost and stored locally. More info 
        on Viadutoo https://github.com/tl-its-umich-edu/viadutoo
        To enable Viadutoo `$GLOBALS["CALIPER_PROXY_ENABLED"] = true;` 

### Give the below variables appropriate value in the 'path.inc.php' file in project for configuring Caliper and Viadutoo.

```php
/*
 * Caliper configuration values
 */
// Indicate whether Caliper support should be enabled (boolean)
$GLOBALS["CALIPER_ENABLED"]       = false;

// The API key that may be required by the endpoint to accept Caliper events (string)
$GLOBALS["CALIPER_API_KEY"]       = null;

// URL of the endpoint (string)
$GLOBALS["CALIPER_ENDPOINT_URL"]  = null; // E.g., 'http://lti.tools/Caliper/event?key=problemroulette'

// An ID used to distinguish this app's events in the endpoint from those of other apps (string)
$GLOBALS["CALIPER_SENSOR_ID"]     = null; // E.g., 'problem_roulette'

// Indicate whether Caliper should send events using a proxy, like Viadutoo (boolean)
$GLOBALS["CALIPER_PROXY_ENABLED"] = false;

// URL of the endpoint proxy, usually an internal web service accessed via the loopback interface (string)
$GLOBALS["CALIPER_PROXY_ENDPOINT_URL"]=null; // E.g., 'http://127.0.0.1:8888/problemroulette/caliper_proxy.php'

// Full pathname of directory containing CA certificates for verifying HTTPS connections (string)
$GLOBALS["CA_CERTS_PATH"]=null; // E.g., '/path/to/CA/Cert/directory'

// OAuth key that may be required by the endpoint to accept Caliper events (string)
$GLOBALS["VIADUTOO_REMOTE_ENDPOINT_OAUTH_KEY"]=null;

// OAuth secret that may be required by the endpoint to accept Caliper events (string)
$GLOBALS["VIADUTOO_REMOTE_ENDPOINT_OAUTH_SECRET"]=null;

// Indicate whether Viadutoo should use Redis support (boolean)
// (When Redis support is enabled, CALIPER_PROXY_ENDPOINT_URL will not be used.)
$GLOBALS['VIADUTOO_REDIS_ENABLED'] = false;

// Redis host name/address and port number, colon-delimited (string, optional)
$GLOBALS['VIADUTOO_REDIS_HOST_PORT'] = '127.0.0.1:6379';

// Name of the queue in Redis to be used by Viadutoo (string)
$GLOBALS['VIADUTOO_REDIS_QUEUE_NAME'] = 'default';
```
  
### More Informantion

* Caliper
    * General/Specifications: https://www.imsglobal.org/activity/caliperram
    * Caliper-php: https://github.com/IMSGlobal/Caliper-php-public
* Viadutoo
    * https://github.com/tl-its-umich-edu/viadutoo
* PHP Resque
    * https://github.com/chrisboulton/php-resque

