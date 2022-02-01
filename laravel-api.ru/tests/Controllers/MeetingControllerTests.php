<?php
   
namespace Tests\Controllers;
   
use Illuminate\Http\Response;
use Tests\TestCase;
use App\Models\Meeting;

   
class MeetingControllerTests extends TestCase {

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
      $randomStart = (1643580060 + rand(0,1000)*3600);
      $startStamp = date('Y-m-d H:i:s', $randomStart);
      $endStamp = date('Y-m-d H:i:s', $randomStart + 3000);
      $infoToCreate = 
         [
            'name' => $this->faker->sentence(),
            'startstamp' => (string) $startStamp,
            'endstamp' => (string) $endStamp
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
      $randomStart = (1643580060 + rand(0,1000)*3600);
      $startStamp = date('Y-m-d H:i:s', $randomStart);
      $endStamp = date('Y-m-d H:i:s', $randomStart + 3000);
      $meeting = Meeting::create(
         [
            'name' => $this->faker->sentence(),
            'startstamp' => (string) $startStamp,
            'endstamp' => (string) $endStamp
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
                     'startstamp' => (string) $meeting->startstamp,
                     'endstamp' => (string) $meeting->endstamp,
                     'created_at' => (string)$meeting->created_at,
                  ]
            ]
         );
   }

   public function testMeetingIsDestroyed() {
      $randomStart = (1643580060 + rand(0,1000)*3600);
      $startStamp = date('Y-m-d H:i:s', $randomStart);
      $endStamp = date('Y-m-d H:i:s', $randomStart + 3000);
      $meetingData =
         [
            'name' => $this->faker->word,
            'startstamp' => (string) $startStamp,
            'endstamp' => (string) $endStamp
         ];

      $meeting = Meeting::create($meetingData);
      
      $this->json('delete', "api/meetings/$meeting->id")
            ->assertNoContent();
      $this->assertDatabaseMissing('meetings', $meetingData);
   }

   public function testUpdateMeetingReturnsCorrectData() {
      $randomStart = (1643580060 + rand(0,1000)*3600);
      $startStamp = date('Y-m-d H:i:s', $randomStart);
      $endStamp = date('Y-m-d H:i:s', $randomStart + 3000);

      $meeting = Meeting::create(
            [
               'name' => $this->faker->word,
               'startstamp' => (string) $startStamp,
               'endstamp' => (string) $endStamp
            ]
      );
      
      $randomStart = (1643580060 + rand(0,1000)*3600);
      $startStamp = date('Y-m-d H:i:s', $randomStart);
      $endStamp = date('Y-m-d H:i:s', $randomStart + 3000);

      $newMeetingInfo = [
            'name' => $this->faker->word,
            'startstamp' => (string) $startStamp,
            'endstamp' => (string) $endStamp
      ];
            
      $this->json('put', "api/meetings/$meeting->id", $newMeetingInfo)
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
               [
                  'data' => [
                        'id' => $meeting->id,
                        'name' => $newMeetingInfo['name'],
                        'startstamp' => (string) $newMeetingInfo['startstamp'],
                        'endstamp' => (string) $newMeetingInfo['endstamp'],
                        'created_at' => (string) $meeting->created_at,
                  ]
               ]
            );
   }
}