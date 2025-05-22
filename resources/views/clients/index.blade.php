@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Client List</h4>
        <a href="{{ route('clients.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Client
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Prefix</th>
                        <th>Is Project</th>
                        <th>City</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr>
                        <td>{{ $client->id }}</td>
                        <td>
                            @if($client->client_logo && $client->client_logo != 'no-image.jpg')
                                <img src="{{ asset('storage/' . $client->client_logo) }}" alt="{{ $client->name }}" class="client-logo">
                            @else
                                <img src="{{ asset('storage/no-image.jpg') }}" alt="No Image" class="client-logo">
                            @endif
                        </td>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->slug }}</td>
                        <td>{{ $client->client_prefix }}</td>
                        <td>{{ $client->is_project == '1' ? 'Yes' : 'No' }}</td>
                        <td>{{ $client->city ?? 'N/A' }}</td>
                        <td class="d-flex">
                            <a href="{{ route('clients.show', $client->id) }}" class="btn btn-info btn-sm me-1">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary btn-sm me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this client?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No clients found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
