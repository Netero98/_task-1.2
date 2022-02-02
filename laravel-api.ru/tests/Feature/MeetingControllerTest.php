<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use App\Models\Meeting;
use Database\Seeders\MeetingSeeder;

class MeetingControllerTest extends TestCase {
   use RefreshDatabase;

   public function testGetMeetingsReturnsDataInValidFormat() {

      $randomStart = (1643500000 + rand(0,1000)*3600);
      $startStamp = date('Y-m-d H:i:s', $randomStart);
      $endStamp = date('Y-m-d H:i:s', $randomStart + 3000);
      $dayStarts = $randomStart-1;
      $dayEnds = $randomStart + 3001;

      Meeting::factory()->create(
         [
            'name' => $this->faker->sentence(),
            'startstamp' => $startStamp,
            'endstamp' => $endStamp
         ]);

      $this->json('get', "api/getMeetings/$dayStarts/$dayEnds")
         ->assertStatus(Response::HTTP_OK)
         ->assertJsonStructure(
            [
               'data' => [
                  '*' => [
                     'id',
                     'name',
                     'startstamp',
                     'endstamp',
                     'created_at',
                  ]
               ]
            ]
         );
   }


   public function testGetMeetingsReturnsValidSingleData() {
      
      $randomStart = (1643500000 + rand(0,1000)*3600);
      $startStamp = date('Y-m-d H:i:s', $randomStart);
      $endStamp = date('Y-m-d H:i:s', $randomStart + 3000);
      $dayStarts = $randomStart-1;
      $dayEnds = $randomStart + 3001;

      $meeting = Meeting::factory()->create(
         [
            'name' => $this->faker->sentence(),
            'startstamp' => $startStamp,
            'endstamp' => $endStamp
         ]
      );
      
      $this->json('get', "api/getMeetings/$dayStarts/$dayEnds")
      ->assertStatus(Response::HTTP_OK)
      ->assertExactJson(
         [
            'data' => 
               [
                  [
                     'id' => $meeting->id,
                     'name' => $meeting->name,
                     'startstamp' => $meeting->startstamp,
                     'endstamp' => $meeting->endstamp,
                     'created_at'  => date('Y-m-d H:i:s', strtotime($meeting->created_at))  //пришлось переформатировать, так как без этого тест ожидал из базы непонятный формат.
                  ]
               ]
         ]
      );
   }


   public function testGetMeetingsReturnsValidMultiData_3() {
      
      $randomStart = (1643500000 + rand(0,1000)*3600);
      $dayStarts = $randomStart-1;
      $dayEnds = $randomStart + 20000;


      $startStamp = date('Y-m-d H:i:s', $randomStart);
      $endStamp = date('Y-m-d H:i:s', $randomStart + 3000);
      $meetingOne = Meeting::factory()->create(
         [
            'name' => $this->faker->sentence(),
            'startstamp' => $startStamp,
            'endstamp' => $endStamp
         ]
      );


      $startStamp = date('Y-m-d H:i:s', $randomStart + 1000);
      $endStamp = date('Y-m-d H:i:s', $randomStart + 3000 + 1000);
      $meetingTwo = Meeting::factory()->create(
         [
            'name' => $this->faker->sentence(),
            'startstamp' => $startStamp,
            'endstamp' => $endStamp
         ]
      );


      $startStamp = date('Y-m-d H:i:s', $randomStart + 2000);
      $endStamp = date('Y-m-d H:i:s', $randomStart + 3000 + 2000);
      $meetingThree = Meeting::factory()->create(
         [
            'name' => $this->faker->sentence(),
            'startstamp' => $startStamp,
            'endstamp' => $endStamp
         ]
      );
      
      $this->json('get', "api/getMeetings/$dayStarts/$dayEnds")
      ->assertStatus(Response::HTTP_OK)
      ->assertExactJson(
         [
            'data' => 
               [
                  [
                     'id' => $meetingOne->id,
                     'name' => $meetingOne->name,
                     'startstamp' => $meetingOne->startstamp,
                     'endstamp' => $meetingOne->endstamp,
                     'created_at'  => date('Y-m-d H:i:s', strtotime($meetingOne->created_at))  //пришлось переформатировать, так как без этого тест ожидал из базы непонятный формат.
                  ],
                  [
                     'id' => $meetingTwo->id,
                     'name' => $meetingTwo->name,
                     'startstamp' => $meetingTwo->startstamp,
                     'endstamp' => $meetingTwo->endstamp,
                     'created_at'  => date('Y-m-d H:i:s', strtotime($meetingTwo->created_at))  //...
                  ],
                  [
                     'id' => $meetingThree->id,
                     'name' => $meetingThree->name,
                     'startstamp' => $meetingThree->startstamp,
                     'endstamp' => $meetingThree->endstamp,
                     'created_at'  => date('Y-m-d H:i:s', strtotime($meetingThree->created_at)) //...
                  ]
               ]
         ]
      );
   }

   public function testGetOptimumMeetingsReturnsDataInValidFormat() {
      $randomStart = (1643500000 + rand(0,1000)*3600);
      $startStamp = date('Y-m-d H:i:s', $randomStart);
      $endStamp = date('Y-m-d H:i:s', $randomStart + 3000);
      $dayStarts = $randomStart-1;
      $dayEnds = $randomStart + 3001;

      Meeting::create(
         [
            'name' => $this->faker->sentence(),
            'startstamp' =>  $startStamp,
            'endstamp' => $endStamp,
         ]
      );

      $this->json('get', "api/getOptimumMeetings/$dayStarts/$dayEnds")
         ->assertStatus(Response::HTTP_OK)
         ->assertJsonStructure(
            [
               'data' => [
                  '*' => [
                     'id',
                     'name',
                     'startstamp',
                     'endstamp',
                     'created_at',
                  ]
               ]
            ]
         );
   }


   public function testGetOptimumMeetingsReturnsValidSingleData() {
      $randomStart = (1643500000 + rand(0,1000)*3600);
      $dayStarts = $randomStart-1;
      $dayEnds = $randomStart + 20000;
      $startStamp = date('Y-m-d H:i:s', $randomStart);
      $endStamp = date('Y-m-d H:i:s', $randomStart + 3000);

      $meeting = Meeting::create(
         [
            'name' => $this->faker->sentence(),
            'startstamp' =>  $startStamp,
            'endstamp' => $endStamp,
         ]
      );

      $this->json('get', "api/getOptimumMeetings/$dayStarts/$dayEnds")
         ->assertStatus(Response::HTTP_OK)
         ->assertExactJson(
            [
               'data' => 
                  [
                     [
                        'id' => $meeting->id,
                        'name' => $meeting->name,
                        'startstamp' => $meeting->startstamp,
                        'endstamp' => $meeting->endstamp,
                        'created_at'  => date('Y-m-d H:i:s', strtotime($meeting->created_at))  //пришлось переформатировать, так как без этого тест ожидал из базы непонятный формат.
                     ],
                  ]
            ]
         );
   }


   public function testGetOptimumMeetingsReturnsValidMultiData_3() {
      $randomStart = (1643500000 + rand(0,1000)*3600);
      $dayStarts = $randomStart-1;
      $dayEnds = $randomStart + 20000;


      $startStamp = date('Y-m-d H:i:s', $randomStart);
      $endStamp = date('Y-m-d H:i:s', $randomStart + 3000);
      $meetingOne = Meeting::create(
         [
            'name' => $this->faker->sentence(),
            'startstamp' =>  $startStamp,
            'endstamp' => $endStamp,
         ]
      );


      $startStamp = date('Y-m-d H:i:s', $randomStart + 3100);
      $endStamp = date('Y-m-d H:i:s', $randomStart + 6000);
      $meetingTwo = Meeting::create(
         [
            'name' => $this->faker->sentence(),
            'startstamp' =>  $startStamp,
            'endstamp' => $endStamp,
         ]
      );


      $startStamp = date('Y-m-d H:i:s', $randomStart + 6100);
      $endStamp = date('Y-m-d H:i:s', $randomStart + 9000);
      $meetingThree = Meeting::create(
         [
            'name' => $this->faker->sentence(),
            'startstamp' =>  $startStamp,
            'endstamp' => $endStamp,
         ]
      );

      $this->json('get', "api/getOptimumMeetings/$dayStarts/$dayEnds")
         ->assertStatus(Response::HTTP_OK)
         ->assertExactJson(
            [
               'data' => 
                  [
                     [
                        'id' => $meetingOne->id,
                        'name' => $meetingOne->name,
                        'startstamp' => $meetingOne->startstamp,
                        'endstamp' => $meetingOne->endstamp,
                        'created_at'  => date('Y-m-d H:i:s', strtotime($meetingOne->created_at))  //пришлось переформатировать, так как без этого тест ожидал из базы непонятный формат.
                     ],
                     [
                        'id' => $meetingTwo->id,
                        'name' => $meetingTwo->name,
                        'startstamp' => $meetingTwo->startstamp,
                        'endstamp' => $meetingTwo->endstamp,
                        'created_at'  => date('Y-m-d H:i:s', strtotime($meetingTwo->created_at))  //...
                     ],
                     [
                        'id' => $meetingThree->id,
                        'name' => $meetingThree->name,
                        'startstamp' => $meetingThree->startstamp,
                        'endstamp' => $meetingThree->endstamp,
                        'created_at'  => date('Y-m-d H:i:s', strtotime($meetingThree->created_at)) //...
                     ]
                  ]
            ]
         );
   }

   public function testIndexReturnsDataInValidFormat() {
      $this->json('get', 'api/meetings')
         ->assertStatus(Response::HTTP_OK)
         ->assertJsonStructure(
            [
               'data' => [
                  '*' => [
                     'id',
                     'name',
                     'startstamp',
                     'endstamp',
                     'created_at',
                  ]
               ]
            ]
      );
   }

   public function testMeetingIsCreatedSuccessfully() {
      $randomStart = (1643500000 + rand(0,1000)*3600);
      $startStamp = date('Y-m-d H:i:s', $randomStart);
      $endStamp = date('Y-m-d H:i:s', $randomStart + 3000);
      $infoToCreate = 
         [
            'name' => $this->faker->sentence(),
            'startstamp' => $startStamp,
            'endstamp' => $endStamp
         ];
      $this->json('post', 'api/meetings', $infoToCreate)
         ->assertStatus(Response::HTTP_CREATED)
         ->assertJsonStructure(
            [
               'data' => [
                  'id',
                  'name',
                  'startstamp',
                  'endstamp',
                  'created_at',
               ]
            ]
         );
      $this->assertDatabaseHas('meetings', $infoToCreate);
   }



   public function testMeetingIsShownCorrectly() {
      $randomStart = (1643500000 + rand(0,1000)*3600);
      $startStamp = date('Y-m-d H:i:s', $randomStart);
      $endStamp = date('Y-m-d H:i:s', $randomStart + 3000);

      $meeting = Meeting::create(
         [
            'name' => $this->faker->sentence(),
            'startstamp' =>  $startStamp,
            'endstamp' => $endStamp,
         ]
      );
         
      $this->json('get', "api/meetings/$meeting->id")
         ->assertStatus(Response::HTTP_OK)
         ->assertExactJson(
            [
               'data' => 
                  [
                     'id' => $meeting->id,
                     'name' => $meeting->name,
                     'startstamp' => $meeting->startstamp,
                     'endstamp' => $meeting->endstamp,
                     'created_at' =>  $meeting->created_at
                  ]
            ]
         );
   }

   public function testMeetingIsDestroyed() {
      $randomStart = (1643500000 + rand(0,1000)*3600);
      $startStamp = date('Y-m-d H:i:s', $randomStart);
      $endStamp = date('Y-m-d H:i:s', $randomStart + 3000);
      $meetingData =
         [
            'name' => $this->faker->word,
            'startstamp' => $startStamp,
            'endstamp' => $endStamp
         ];

      $meeting = Meeting::create($meetingData);
      
      $this->json('delete', "api/meetings/$meeting->id")
            ->assertNoContent();
      $this->assertDatabaseMissing('meetings', $meetingData);
   }

   public function testUpdateMeetingReturnsCorrectData() {
      $randomStart = (1643500000 + rand(0,1000)*3600);
      $startStamp = date('Y-m-d H:i:s', $randomStart);
      $endStamp = date('Y-m-d H:i:s', $randomStart + 3000);

      $meeting = Meeting::create(
            [
               'name' => $this->faker->word,
               'startstamp' => $startStamp,
               'endstamp' => $endStamp
            ]
      );
      
      $randomStart = (1643500000 + rand(0,1000)*3600);
      $startStamp = date('Y-m-d H:i:s', $randomStart);
      $endStamp = date('Y-m-d H:i:s', $randomStart + 3000);

      $newMeetingInfo = [
            'name' => $this->faker->word,
            'startstamp' => $startStamp,
            'endstamp' => $endStamp
      ];
            
      $this->json('put', "api/meetings/$meeting->id", $newMeetingInfo)
         ->assertStatus(Response::HTTP_OK)
         ->assertExactJson(
            [
               'data' => [
                     'id' => $meeting->id,
                     'name' => $newMeetingInfo['name'],
                     'startstamp' => $newMeetingInfo['startstamp'],
                     'endstamp' => $newMeetingInfo['endstamp'],
                     'created_at' => $meeting->created_at,
               ]
            ]
         );
   }
}
