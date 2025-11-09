@extends('layouts.admin.main')
@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($property) ? 'Edit Property' : 'Add Property' }}</h4>
                            <a href="{{ route(getAdminRouteName() . '.properties.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>
                        <div class="card-body">
                            <form
                                action="{{ isset($property) ? route(getAdminRouteName() . '.properties.update', $property->id) : route(getAdminRouteName() . '.properties.store') }}"
                                method="POST">
                                @csrf
                                @if (isset($property))
                                    @method('PUT')
                                @endif

                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title"
                                        value="{{ old('title', $property->title ?? '') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                        id="slug" name="slug"
                                        value="{{ old('slug', $property->slug ?? '') }}" required>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror"
                                        id="location" name="location"
                                        value="{{ old('location', $property->location ?? '') }}">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="guest_capacity" class="form-label">Guest Capacity</label>
                                    <input type="number" class="form-control @error('guest_capacity') is-invalid @enderror"
                                        id="guest_capacity" name="guest_capacity"
                                        value="{{ old('guest_capacity', $property->guest_capacity ?? '') }}">
                                    @error('guest_capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="bedrooms" class="form-label">Bedrooms</label>
                                    <input type="number" class="form-control @error('bedrooms') is-invalid @enderror"
                                        id="bedrooms" name="bedrooms"
                                        value="{{ old('bedrooms', $property->bedrooms ?? '') }}">
                                    @error('bedrooms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="bathrooms" class="form-label">Bathrooms</label>
                                    <input type="number" class="form-control @error('bathrooms') is-invalid @enderror"
                                        id="bathrooms" name="bathrooms"
                                        value="{{ old('bathrooms', $property->bathrooms ?? '') }}">
                                    @error('bathrooms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="property_brief" class="form-label">Property Brief</label>
                                    <textarea class="form-control @error('property_brief') is-invalid @enderror" id="property_brief"
                                        name="property_brief" rows="3">{{ old('property_brief', $property->property_brief ?? '') }}</textarea>
                                    @error('property_brief')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="property_description" class="form-label">Property Description</label>
                                    <textarea class="form-control @error('property_description') is-invalid @enderror" id="property_description"
                                        name="property_description" rows="5">{{ old('property_description', $property->property_description ?? '') }}</textarea>
                                    @error('property_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="property_experience" class="form-label">Property Experience</label>
                                    <textarea class="form-control @error('property_experience') is-invalid @enderror" id="property_experience"
                                        name="property_experience" rows="5">{{ old('property_experience', $property->property_experience ?? '') }}</textarea>
                                    @error('property_experience')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="amenities" class="form-label">Amenities</label>
                                    <textarea class="form-control @error('amenities') is-invalid @enderror" id="amenities" name="amenities"
                                        rows="3">{{ old('amenities', $property->amenities ?? '') }}</textarea>
                                    @error('amenities')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="spaces" class="form-label">Spaces</label>
                                    <textarea class="form-control @error('spaces') is-invalid @enderror" id="spaces" name="spaces" rows="3">{{ old('spaces', $property->spaces ?? '') }}</textarea>
                                    @error('spaces')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="cancellation_policy" class="form-label">Cancellation Policy</label>
                                    <textarea class="form-control @error('cancellation_policy') is-invalid @enderror" id="cancellation_policy"
                                        name="cancellation_policy" rows="3">{{ old('cancellation_policy', $property->cancellation_policy ?? '') }}</textarea>
                                    @error('cancellation_policy')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="other_important_information" class="form-label">Other Important
                                        Information</label>
                                    <textarea class="form-control @error('other_important_information') is-invalid @enderror"
                                        id="other_important_information" name="other_important_information" rows="3">{{ old('other_important_information', $property->other_important_information ?? '') }}</textarea>
                                    @error('other_important_information')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="faqs" class="form-label">FAQs</label>
                                    <textarea class="form-control @error('faqs') is-invalid @enderror" id="faqs" name="faqs" rows="3">{{ old('faqs', $property->faqs ?? '') }}</textarea>
                                    @error('faqs')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
