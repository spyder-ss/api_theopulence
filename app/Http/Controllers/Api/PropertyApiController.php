<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::select('id', 'slug', 'title', 'location', 'guest_capacity', 'bedrooms', 'bathrooms', 'property_brief', 'image')
            ->where('status', 1)
            ->where('is_delete', 0)
            ->orderBy('created_at', 'desc');

        $properties = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'slug' => $item->slug,
                'title' => $item->title,
                'location' => $item->location,
                'guest_capacity' => $item->guest_capacity,
                'bedrooms' => $item->bedrooms,
                'bathrooms' => $item->bathrooms,
                'property_brief' => $item->property_brief,
                'image' => Helper::getImageUrl('property', $item->id, $item->image),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $properties
        ]);
    }

    public function show(Request $request, $slug)
    {
        $property = Property::where('slug', $slug)
            ->where('status', 1)
            ->where('is_delete', 0)
            ->with(['amenities', 'images'])
            ->first();

        if (!$property) {
            return response()->json([
                'success' => false,
                'message' => 'Property not found'
            ], 404);
        }

        $propertyData = $property->toArray();
        $propertyData['image'] = Helper::getImageUrl('property', $property->id, $property->image);

        $propertyId = $property->id;

        // Transform images
        $propertyData['images'] = $property->images->map(function ($image) use ($propertyId) {
            return [
                'id' => $image->id,
                'image' => Helper::getImageUrl('property_images', $propertyId, $image->image),
                'alt_text' => $image->alt_text,
                'sort_order' => $image->sort_order,
            ];
        });

        // Transform amenities
        $propertyData['amenities'] = $property->amenities->map(function ($amenity) {
            return [
                'id' => $amenity->id,
                'name' => $amenity->name,
                'slug' => $amenity->slug,
                'icon' => $amenity->icon,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $propertyData
        ]);
    }
}
