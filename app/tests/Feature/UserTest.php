<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register()
    {
        $data = [
            "employee_name" => "Admin",
            "employee_no" => "5000",
            "role" => "Admin",
            "password" => "123123",
        ];
        $response = $this->post('/api/register', $data);
        $response->assertStatus(200);
    }

    public function test_login()
    {
        $data = [
            "employee_no" => "5000",
            "password" => "123123",
        ];
        $response = $this->post('/api/login', $data);
        $response->assertStatus(200);
        // $response = $this->postJson('/api/reservation/reservation-available', $data);
        // $response->assertStatus(200);
    }

}
