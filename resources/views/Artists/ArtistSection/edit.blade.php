@extends('layouts.Artists.Dashboard')

@section('content')

<h1 class="text-2xl font-bold mb-4 text-gray-700">Edit Artist</h1>

<form action="{{ route('Artists.ArtistSection.update', $artist->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block">Name</label>
        <input type="text" name="name" class="border border-gray-300 rounded p-2 w-full" value="{{ old('name', $artist->name) }}">
    </div>

    <div class="mb-4">
        <label class="block">Stage Name</label>
        <input type="text" name="stage_name" class="border border-gray-300 rounded p-2 w-full" value="{{ old('stage_name', $artist->stage_name) }}">
    </div>

    <div class="mb-4">
        <label class="block">Email</label>
        <input type="email" name="email" class="border border-gray-300 rounded p-2 w-full" value="{{ old('email', $artist->email) }}">
    </div>

    <div class="mb-4">
        <label class="block">Phone</label>
        <input type="text" name="phone" class="border border-gray-300 rounded p-2 w-full" value="{{ old('phone', $artist->phone) }}">
    </div>

    <div class="mb-4">
        <label class="block">Bank Details</label>
        <input type="text" name="bank_details" class="border border-gray-300 rounded p-2 w-full" value="{{ old('bank_details', $artist->bank_details) }}">
    </div>

    <div class="mb-4">
        <label class="block">M-Pesa Paybill</label>
        <input type="text" name="mpesa_paybill" class="border border-gray-300 rounded p-2 w-full" value="{{ old('mpesa_paybill', $artist->mpesa_paybill) }}">
    </div>

    <div class="mb-4">
        <label class="block">National ID</label>
        <input type="text" name="national_id" class="border border-gray-300 rounded p-2 w-full" value="{{ old('national_id', $artist->national_id) }}">
    </div>

    <div class="mb-4">
        <label class="block">Profile Image</label>
        <input type="file" name="profile_image" class="border border-gray-300 rounded p-2 w-full">
    </div>

    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        Update Artist
    </button>
</form>

@endsection
