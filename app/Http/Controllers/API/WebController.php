<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function index(Request $r)
    {
        $id = $r->query('id');
        return view('index')->with(['userId' =>  $id != null && preg_match("/^[0-9]*$/", $id) ? $r->input('id') : null]);
    }
}
