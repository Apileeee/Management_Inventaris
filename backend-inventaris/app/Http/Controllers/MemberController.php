<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Inventory;

use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['data' => Member::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required',
            'position'=>'required',
            'departement'=>'required'
        ]);

        return response()->json(['data' => Member::create($data)],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(['data' => Member::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $member = Member::findOrFail($id);
        $member->update($request->all());
        return response()->json(['data' => $member]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Clear inventory assigned to this member first
            Inventory::where('assigned_to', $id)->update([
                'assigned_to' => null,
                'department' => ''
            ]);
            
            // Then delete the member
            Member::findOrFail($id)->delete();
            return response()->json(['message'=>'Deleted']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
