@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Client Details</h4>
        <div>
            <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                @if($client->client_logo && $client->client_logo != 'no-image.jpg')
                    <img src="{{ asset('storage/' . $client->client_logo) }}" alt="{{ $client->name }}" class="img-fluid mb-3" style="max-height: 200px;">
                @else
                    <img src="{{ asset('storage/no-image.jpg') }}" alt="No Image" class="img-fluid mb-3" style="max-height: 200px;">
                @endif
                <h4>{{ $client->name }}</h4>
                <p class="text-muted">{{ $client->slug }}</p>
            </div>
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 30%">ID</th>
                                <td>{{ $client->id }}</td>
                            </tr>
                            <tr>
                                <th>Client Prefix</th>
                                <td>{{ $client->client_prefix }}</td>
                            </tr>
                            <tr>
                                <th>Is Project</th>
                                <td>{{ $client->is_project == '1' ? 'Yes' : 'No' }}</td>
                            </tr>
                            <tr>
                                <th>Self Capture</th>
                                <td>{{ $client->self_capture == '1' ? 'Yes' : 'No' }}</td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td>{{ $client->city ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Phone Number</th>
                                <td>{{ $client->phone_number ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $client->address ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $client->created_at->format('d M Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $client->updated_at->format('d M Y H:i:s') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this client?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i> Delete Client
            </button>
        </form>
    </div>
</div>
@endsection
