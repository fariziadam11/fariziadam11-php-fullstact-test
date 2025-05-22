<?php

namespace App\Http\Controllers;

use App\Models\MyClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WebClientController extends Controller
{

    public function index()
    {
        $clients = MyClient::all();
        return view('clients.index', compact('clients'));
    }


    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'slug' => 'required|string|max:100|unique:my_client,slug',
            'is_project' => 'required|in:0,1',
            'self_capture' => 'required|in:0,1',
            'client_prefix' => 'required|string|max:4',
            'client_logo' => 'nullable|image|max:2048',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:50',
        ]);

        $data = $request->all();


        if ($request->hasFile('client_logo')) {
            $file = $request->file('client_logo');
            $filename = Str::slug($request->name) . '-' . time() . '.' . $file->getClientOriginalExtension();


            $path = $file->storeAs('client-logos', $filename, 'public');
            $data['client_logo'] = $path;
        }

        MyClient::create($data);

        return redirect()->route('clients.index')
            ->with('success', 'Client created successfully.');
    }


    public function show($id)
    {
        $client = MyClient::findOrFail($id);
        return view('clients.show', compact('client'));
    }


    public function edit($id)
    {
        $client = MyClient::findOrFail($id);
        return view('clients.edit', compact('client'));
    }


    public function update(Request $request, $id)
    {
        $client = MyClient::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:250',
            'slug' => 'required|string|max:100|unique:my_client,slug,' . $id,
            'is_project' => 'required|in:0,1',
            'self_capture' => 'required|in:0,1',
            'client_prefix' => 'required|string|max:4',
            'client_logo' => 'nullable|image|max:2048',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:50',
        ]);

        $data = $request->all();


        if ($request->hasFile('client_logo')) {
            $file = $request->file('client_logo');
            $filename = Str::slug($request->name) . '-' . time() . '.' . $file->getClientOriginalExtension();


            if ($client->client_logo !== 'no-image.jpg' && Storage::disk('public')->exists($client->client_logo)) {
                Storage::disk('public')->delete($client->client_logo);
            }


            $path = $file->storeAs('client-logos', $filename, 'public');
            $data['client_logo'] = $path;
        }

        $client->update($data);

        return redirect()->route('clients.index')
            ->with('success', 'Client updated successfully.');
    }


    public function destroy($id)
    {
        $client = MyClient::findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully.');
    }
}
