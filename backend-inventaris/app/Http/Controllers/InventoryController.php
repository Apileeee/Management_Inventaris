<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['data' => Inventory::with('member')->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'item_name'=>'required',
            'type'=>'required',
            'serial_number'=>'required',
            'specification'=>'required',
            'status'=>'required',
            'assigned_to'=>'nullable|exists:members,id',
            'department'=>'required'
        ]);

        return response()->json(['data' => Inventory::create($data)],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(['data' => Inventory::with('member')->findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $inv = Inventory::findOrFail($id);
        $inv->update($request->all());
        return response()->json(['data' => $inv]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Inventory::findOrFail($id)->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
