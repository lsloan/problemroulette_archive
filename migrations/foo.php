<?php
class Foo extends Migration {
    function migrate() {
        $this->info("some cool, specific message from migration");
    }
}

