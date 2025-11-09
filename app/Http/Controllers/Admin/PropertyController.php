<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; // Add this line

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
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'main_image_id' => 'nullable|integer|exists:property_images,id',
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

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            $imagePath = public_path('storage/property_images/' . $property->id);
            if (!File::exists($imagePath)) {
                File::makeDirectory($imagePath, 0777, true, true);
            }

            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move($imagePath, $imageName);

                $property->images()->create([
                    'image_path' => $imageName,
                    'is_main' => false, // Default to false, will be updated later if main_image_id is set
                ]);
            }
        }

        // Set main image if provided
        if ($request->has('main_image_id')) {
            $property->images()->update(['is_main' => false]); // Unset all main images first
            PropertyImage::where('id', $request->main_image_id)->update(['is_main' => true]);
        } else {
            // If no main image is explicitly selected, make the first image main
            $firstImage = $property->images()->first();
            if ($firstImage) {
                $firstImage->update(['is_main' => true]);
            }
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
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'main_image_id' => 'nullable|integer|exists:property_images,id',
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

        // Handle multiple image uploads for update
        if ($request->hasFile('images')) {
            $imagePath = public_path('storage/property_images/' . $property->id);
            if (!File::exists($imagePath)) {
                File::makeDirectory($imagePath, 0777, true, true);
            }

            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move($imagePath, $imageName);

                $property->images()->create([
                    'image_path' => $imageName,
                    'is_main' => false,
                ]);
            }
        }

        // Update main image if provided
        if ($request->has('main_image_id')) {
            $property->images()->update(['is_main' => false]); // Unset all main images first
            PropertyImage::where('id', $request->main_image_id)->update(['is_main' => true]);
        } else {
            // If no main image is explicitly selected, and there are images, make the first one main
            $firstImage = $property->images()->first();
            if ($firstImage) {
                $property->images()->update(['is_main' => false]); // Unset all main images first
                $firstImage->update(['is_main' => true]);
            }
        }

        return redirect()->route('admin.properties.index')->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $property)
    {
        $property->delete();
        return redirect()->route('admin.properties.index')->with('success', 'Property deleted successfully.');
    }

    public function ajax_property_img_delete(Request $request)
    {
        $request->validate([
            'image_id' => 'required|exists:property_images,id',
        ]);

        $image = PropertyImage::find($request->image_id);
        if ($image) {
            $imagePath = public_path('storage/property_images/' . $image->property_id . '/' . $image->image_path);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            $image->delete();

            // If the deleted image was the main image, and there are other images, set a new main image
            if ($image->is_main) {
                $property = Property::find($image->property_id);
                if ($property && $property->images->count() > 0) {
                    $property->images()->first()->update(['is_main' => true]);
                }
            }

            return response()->json(['status' => 'ok', 'message' => 'Image deleted successfully.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Image not found.']);
    }

    public function generateSlug(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
        ]);

        $title = $request->input('title');
        $slug = getSlug('properties', 'slug', $title); // Call the helper function

        return response()->json(['slug' => $slug]);
    }
}
