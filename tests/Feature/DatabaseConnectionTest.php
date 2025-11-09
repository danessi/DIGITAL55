<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseConnectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_uses_sqlite_memory_database(): void
    {
        $connection = DB::connection()->getDriverName();
        $database = DB::connection()->getDatabaseName();
        
        $this->assertEquals('sqlite', $connection);
        $this->assertEquals(':memory:', $database);
        
        echo "\n   Using: {$connection} - {$database}\n";
    }
    
    public function test_does_not_use_production_database(): void
    {
        $database = DB::connection()->getDatabaseName();
        
        $this->assertNotEquals('course_management', $database);
        $this->assertNotEquals('laravel', $database);
    }
}