<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    public function index() {
        return Meeting::all();
    }
}
