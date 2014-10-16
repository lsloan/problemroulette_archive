<?php
date_default_timezone_set('America/New_York');

class Migration {

    var $logfile;
    var $logname;
    var $send_mail = false;
    var $db;

    function __construct() {
        global $dbmgr;
        $this->openlog();
        $this->db = $dbmgr;
        if (isset($GLOBALS["SEND_MIGRATION_MAIL"])) {
            $this->send_mail = $GLOBALS["SEND_MIGRATION_MAIL"];
        }
        $this->init();
    }

    function __destruct() {
        $this->closelog();
        $this->deletelog();
    }

    function name() {
        return get_class($this);
    }

    function init() {
        return;
    }

    function openlog() {
        $this->logname = tempnam("/tmp", "PR_migration_");
        $this->logfile = fopen($this->logname, "w");
    }

    function closelog() {
        if ($this->logfile) {
            fclose($this->logfile);
            $this->logfile = null;
        }
    }

    function deletelog() {
        if ($this->logname) {
            unlink($this->logname);
            $this->logname = null;
        }
    }

    function info($message) {
        $this->log("INFO", $message);
    }

    function error($message) {
        $this->log("ERROR", $message);
    }

    function log($level, $message) {
        $message = date(DATE_ATOM) . " - [$level] - " . $message . "\r\n";
        if ($this->logfile) {
            fwrite($this->logfile, $message);
        } else {
            error_log($message);
        }
    }

    function flushLog() {
        if ($this->logfile) {
            fflush($this->logfile);
        }
    }
    function mailLog() {
        $this->flushlog();
        $to = 'botimer@umich.edu';
        $subject = "Problem Roulette Migration Log - " . $this->name();
        $headers =<<<EOF
From: PR Migrations <botimer@umich.edu>
Reply-To: botimer@umich.edu
EOF;

        $message = file_get_contents($this->logname);
        if ($this->send_mail) {
            mail($to, $subject, $message, $headers);
        } else {
            print($message);
        }
    }

    function migrate() {
        // This is abstract and does nothing by default.
        return;
    }

    function run() {
        $this->info("Starting Migration '". $this->name() ."'");
        $this->migrate();
        $message = "Migration '" . $this->name() . "' completed";
        $this->info($message);
        $this->mailLog();
    }
}

