<?php

namespace Tests;

trait DatabaseMigrations
{
    public function setUp() {
        parent::setUp();

        try {
            $this->artisan('migrate:rollback');
        } catch(\PDOException $e) {}

        $this->artisan('migrate');
    }
}
