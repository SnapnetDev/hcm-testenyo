<?php

namespace App\Http\Controllers;

use App\ShiftType;
use Illuminate\Http\Request;

class ShiftTypeController extends Controller
{

    public function index()
    {
        $shift_types=ShiftType::all();
        return view('',compact('shift_types'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(ShiftType $shiftType)
    {
        //
    }

    public function edit(ShiftType $shiftType)
    {
        //
    }

    public function update(Request $request, ShiftType $shiftType)
    {
        //
    }

    public function destroy(ShiftType $shiftType)
    {
        //
    }
}
