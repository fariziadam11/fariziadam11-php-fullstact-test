@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Edit Client</h4>
        <div>
            <a href="{{ route('clients.show', $client->id) }}" class="btn btn-info me-2">
                <i class="fas fa-eye"></i> View
            </a>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('clients.update', $client->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $client->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $client->slug) }}" required>
                        <small class="text-muted">Auto-generated from name, but can be edited</small>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="client_prefix" class="form-label">Client Prefix <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('client_prefix') is-invalid @enderror" id="client_prefix" name="client_prefix" value="{{ old('client_prefix', $client->client_prefix) }}" maxlength="4" required>
                        <small class="text-muted">Maximum 4 characters</small>
                        @error('client_prefix')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="is_project" class="form-label">Is Project <span class="text-danger">*</span></label>
                        <select class="form-select @error('is_project') is-invalid @enderror" id="is_project" name="is_project" required>
                            <option value="0" {{ (old('is_project', $client->is_project) == '0') ? 'selected' : '' }}>No</option>
                            <option value="1" {{ (old('is_project', $client->is_project) == '1') ? 'selected' : '' }}>Yes</option>
                        </select>
                        @error('is_project')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="self_capture" class="form-label">Self Capture <span class="text-danger">*</span></label>
                        <select class="form-select @error('self_capture') is-invalid @enderror" id="self_capture" name="self_capture" required>
                            <option value="1" {{ (old('self_capture', $client->self_capture) == '1') ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ (old('self_capture', $client->self_capture) == '0') ? 'selected' : '' }}>No</option>
                        </select>
                        @error('self_capture')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $client->city) }}" maxlength="50">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number', $client->phone_number) }}" maxlength="50">
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="client_logo" class="form-label">Client Logo</label>
                        <input type="file" class="form-control @error('client_logo') is-invalid @enderror" id="client_logo" name="client_logo">
                        <small class="text-muted">Max size: 2MB. Leave empty to keep current logo.</small>
                        @error('client_logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        @if($client->client_logo && $client->client_logo != 'no-image.jpg')
                            <div class="mt-2">
                                <p>Current Logo:</p>
                                <img src="{{ asset('storage/' . $client->client_logo) }}" alt="{{ $client->name }}" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $client->address) }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('clients.show', $client->id) }}" class="btn btn-secondary me-md-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Client</button>
            </div>
        </form>
    </div>
</div>
@endsection
