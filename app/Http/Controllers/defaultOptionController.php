<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class defaultOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function setDefault(Request $request)
    {
        //
        $request->validate([
            'id' => 'required',
        ]);
        try{
            User::where('id',auth()->user()->id)->update(['landing_module'=>$request->id]);
            return response()->json(['success'=>true,'message' => 'Default option is set successfully.']);
        }
        catch (\Exception $e) {
        // Log the exception message for debugging
        \Log::error('Error updating landing_module: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Something went wrong.'], 500);
        }   
    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
