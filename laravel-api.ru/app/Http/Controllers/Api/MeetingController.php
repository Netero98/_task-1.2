<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MeetingController extends Controller {
    
     //Все собрания за промежуток времени
    public function getMeetings(int $dayStarts,int $dayEnds) {
        $meetings = DB::table('meetings')
            ->where('startstamp', '>',date('Y-m-d H:i:s', $dayStarts))
            ->where('endstamp', '<',date('Y-m-d H:i:s', $dayEnds))
            ->orderBy('startstamp')
            ->get();
        return $meetings;
    }

    // оптимальное расписание за промежуток времени:
    public function getOptimumMeetings($dayStarts, $dayEnds) {
        $meetings = DB::table('meetings')
            ->where('startstamp', '>',date('Y-m-d H:i:s', $dayStarts))
            ->where('endstamp', '<',date('Y-m-d H:i:s', $dayEnds))
            ->orderBy('startstamp')
            ->get();
                    
        $meetingsArr = json_decode(json_encode($meetings), True); //неплохой способ сделать из объекта массив.
        $optimumMeetingsArr = [];
        while($meetingsArr) {
            $idOfBestVar = 0;
            $minimalDiff = INF;
            foreach($meetingsArr as $rowId => $rowHimSelf) {
                $newDiff = strtotime($rowHimSelf['startstamp']) - $dayStarts;
                if($newDiff < 0) {
                    unset($meetingsArr[$rowId]);
                } elseif($newDiff < $minimalDiff) {
                    $minimalDiff = $newDiff;
                    $idOfBestVar = $rowId;
                }
            }
            if($meetingsArr){
            $optimumMeetingsArr[] = $meetingsArr[$idOfBestVar];
            $dayStarts = strtotime($meetingsArr[$idOfBestVar]['endstamp']);
            $dayStarts +=60; // следующее собрание должно начинаться хотя бы через минуту после конца предыдущего
            unset($meetingsArr[$idOfBestVar]);
            }
        }
        return $optimumMeetingsArr;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Meeting::all(); //на случай, если нужны все существующие расписания, а не за конкретный промежуток времени.
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Meeting::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
