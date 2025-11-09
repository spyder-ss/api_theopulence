<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AmenityController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:amenity-list|amenity-create|amenity-edit|amenity-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:amenity-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:amenity-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:amenity-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $amenities = Amenity::orderBy('id', 'DESC')->paginate(config('custom.default_page_limit'));
        $data = array(
            'title' => 'Amenities',
            'amenities' => $amenities,
        );
        return view('admin.amenities.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = array(
            'title' => 'Add Amenity',
        );
        return view('admin.amenities.form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:amenities,name',
            'status' => 'required',
        ]);

        $input = $request->all();
        Amenity::create($input);
        Session::flash('success', 'Amenity created successfully.');
        return redirect()->route('amenities.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $amenity = Amenity::find($id);
        $data = array(
            'title' => 'Amenity Details',
            'amenity' => $amenity,
        );
        return view('admin.amenities.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $amenity = Amenity::find($id);
        $data = array(
            'title' => 'Edit Amenity',
            'amenity' => $amenity,
        );
        return view('admin.amenities.form')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:amenities,name,' . $id,
            'status' => 'required',
        ]);

        $input = $request->all();
        $amenity = Amenity::find($id);
        $amenity->update($input);
        Session::flash('success', 'Amenity updated successfully.');
        return redirect()->route('amenities.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Amenity::find($id)->delete();
        Session::flash('success', 'Amenity deleted successfully.');
        return redirect()->route('amenities.index');
    }
}
