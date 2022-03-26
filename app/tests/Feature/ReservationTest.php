<?php

namespace Tests\Feature;

use Tests\TestCase;

class ReservationTest extends TestCase
{
    public function login()
    {
        $data = [
            "employee_no" => "5000",
            "password" => "123123",
        ];
        $response = $this->post('/api/login', $data);
        $response->assertStatus(200);
    }

    public function test_check_reservation_available()
    {
        $this->login();
        $data = [
            "seats_no" => "1",
        ];
        $response = $this->postJson('/api/reservation/reservation-available', $data);
        $response->assertStatus(200);
        $data = [
            "seats_no" => "3",
        ];
        $response = $this->postJson('/api/reservation/reservation-available', $data);
        $response->assertStatus(200);
        $data = [
            "seats_no" => "6",
        ];
        $response = $this->postJson('/api/reservation/reservation-available', $data);
        $response->assertStatus(200);
    }

    public function test_new_reservation()
    {
        $this->login();
        $data = [
            "table_no" => "100",
            "starting" => "04:21 PM",
            "ending" => "04:25 PM",
        ];
        $response = $this->postJson('/api/reservation/new', $data);
        $response->assertStatus(200);
    }

    public function test_get_all_reservation()
    {
        $this->login();
        $response = $this->postJson('/api/reservation/');
        $response->assertStatus(200);
    }

    public function test_get_all_reservation_filter()
    {
        $this->login();
        $data = [
            "table_no" => 100,
            "reservation_date" => "2022-03-25",
        ];
        $response = $this->postJson('/api/reservation/', $data);
        $response->assertStatus(200);
    }

}
