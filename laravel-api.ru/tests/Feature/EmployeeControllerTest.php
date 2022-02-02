<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use App\Models\Employee;

class EmployeeControllerTest extends TestCase {
use RefreshDatabase;


public function testIndexReturnsDataInValidFormat() {
    $this->json('get', 'api/employees')
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure(
        [
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'position',
                    'created_at'
                ]
            ]
        ]
    );
}

public function testEmployeeIsCreatedSuccessfully() {
    $infoToCreate = 
        [
        'name' => $this->faker->name,
        'position' => $this->faker->word,
        ];
    $this->json('post', 'api/employees', $infoToCreate)
        ->assertStatus(Response::HTTP_CREATED)
        ->assertJsonStructure(
        [
            'data' => [
                'id',
                'name',
                'position',
                'created_at'
            ]
        ]
        );
    $this->assertDatabaseHas('employees', $infoToCreate);
}



public function testEmployeeIsShownCorrectly() {

    $employee = Employee::create(
        [
        'name' => $this->faker->name,
        'position' => $this->faker->word
        ]
    );
        
    $this->json('get', "api/employees/$employee->id")
        ->assertStatus(Response::HTTP_OK)
        ->assertExactJson(
            [
                'data' => 
                    [
                        'id' => $employee->id,
                        'name' => $employee->name,
                        'position' => $employee->position,
                        'created_at' => $employee->created_at
                    ]
            ]
        );
}

public function testEmployeeIsDestroyed() {
    $employeeData =
        [
        'name' => $this->faker->name,
        'position' => $this->faker->word
        ];

    $meeting = Employee::create($employeeData);
    
    $this->json('delete', "api/employees/$meeting->id")
        ->assertNoContent();
    $this->assertDatabaseMissing('employees', $employeeData);
}

public function testUpdateMeetingReturnsCorrectData() {

    $employee = Employee::create(
        [
            'name' => $this->faker->name,
            'position' => $this->faker->word
        ]
    );

    $newEmployeeInfo = [
        'name' => $this->faker->name,
        'position' => $this->faker->word
    ];
        
    $this->json('put', "api/employees/$employee->id", $newEmployeeInfo)
        ->assertStatus(Response::HTTP_OK)
        ->assertExactJson(
            [
                'data' => [
                        'id' => $employee->id,
                        'name' => $newEmployeeInfo['name'],
                        'position' => $newEmployeeInfo['position'],
                        'created_at' => $employee['created_at']
                ]
            ]
        );
}
}
