<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }

    public function testDatabase()
    {
        // Make call to application...

        $this->assertDatabaseHas('user', [
            'email' => 'xps@xtractsolutions.com'
        ]);

        $this->assertDatabaseHas('patient', [
            'lastname' => 'XTRACT'
        ]);

        $this->assertDatabaseMissing('patient_temp', [
            'firstname' => 'Joe'
        ]);
    }
}
