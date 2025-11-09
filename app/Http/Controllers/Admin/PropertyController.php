<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::all();
        return view('admin.properties.index', compact('properties'));
    }

    public function create()
    {
        $amenities = Amenity::where('status', 1)->get();
        return view('admin.properties.form', compact('amenities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:properties,slug',
            'location' => 'nullable|string|max:255',
            'guest_capacity' => 'nullable|integer',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'property_brief' => 'nullable|string',
            'property_description' => 'nullable|string',
            'property_experience' => 'nullable|string',
            'spaces' => 'nullable|string',
            'cancellation_policy' => 'nullable|string',
            'other_important_information' => 'nullable|string',
            'faqs' => 'nullable|string',
            'amenities' => 'nullable|array', // Validate as array
            'amenities.*' => 'exists:amenities,id', // Validate each amenity ID
        ]);

        $property = Property::create([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'location' => $request->location,
            'guest_capacity' => $request->guest_capacity,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'property_brief' => $request->property_brief,
            'property_description' => $request->property_description,
            'property_experience' => $request->property_experience,
            'spaces' => $request->spaces,
            'cancellation_policy' => $request->cancellation_policy,
            'other_important_information' => $request->other_important_information,
            'faqs' => $request->faqs,
        ]);

        if ($request->has('amenities')) {
            $property->amenities()->sync($request->amenities);
        }

        return redirect()->route('admin.properties.index')->with('success', 'Property created successfully.');
    }

    public function edit(Property $property)
    {
        $amenities = Amenity::where('status', 1)->get();
        return view('admin.properties.form', compact('property', 'amenities'));
    }

    public function update(Request $request, Property $property)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:properties,slug,' . $property->id,
            'location' => 'nullable|string|max:255',
            'guest_capacity' => 'nullable|integer',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'property_brief' => 'nullable|string',
            'property_description' => 'nullable|string',
            'property_experience' => 'nullable|string',
            'spaces' => 'nullable|string',
            'cancellation_policy' => 'nullable|string',
            'other_important_information' => 'nullable|string',
            'faqs' => 'nullable|string',
            'amenities' => 'nullable|array', // Validate as array
            'amenities.*' => 'exists:amenities,id', // Validate each amenity ID
        ]);

        $property->update([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'location' => $request->location,
            'guest_capacity' => $request->guest_capacity,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'property_brief' => $request->property_brief,
            'property_description' => $request->property_description,
            'property_experience' => $request->property_experience,
            'spaces' => $request->spaces,
            'cancellation_policy' => $request->cancellation_policy,
            'other_important_information' => $request->other_important_information,
            'faqs' => $request->faqs,
        ]);

        if ($request->has('amenities')) {
            $property->amenities()->sync($request->amenities);
        } else {
            $property->amenities()->detach(); // If no amenities are selected, detach all
        }

        return redirect()->route('admin.properties.index')->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $property)
    {
        $property->delete();
        return redirect()->route('admin.properties.index')->with('success', 'Property deleted successfully.');
    }
}
