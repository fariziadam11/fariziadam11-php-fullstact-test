<?php

namespace App\Http\Controllers;

use App\Models\MyClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MyClientController extends Controller
{

    public function index()
    {
        $clients = MyClient::all();
        return response()->json(['data' => $clients], 200);
    }



    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();


        if ($request->hasFile('client_logo')) {
            $file = $request->file('client_logo');
            $filename = Str::slug($request->name) . '-' . time() . '.' . $file->getClientOriginalExtension();


            $path = Storage::disk('s3')->putFileAs('client-logos', $file, $filename);

            $data['client_logo'] = config('app.url') . '/storage/' . $path;
        }


        $client = MyClient::create($data);

        return response()->json(['message' => 'Client created successfully', 'data' => $client], 201);
    }


    public function show(string $slug)
    {

        $cachedClient = Cache::get('client:' . $slug);

        if ($cachedClient) {
            return response()->json(['data' => json_decode($cachedClient)], 200);
        }


        $client = MyClient::where('slug', $slug)->first();

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }


        Cache::put('client:' . $client->slug, json_encode($client->toArray()));

        return response()->json(['data' => $client], 200);
    }


    public function update(Request $request, string $id)
    {
        $client = MyClient::findOrFail($id);


        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:250',
            'slug' => 'sometimes|required|string|max:100|unique:my_client,slug,' . $id,
            'is_project' => 'sometimes|required|in:0,1',
            'self_capture' => 'sometimes|required|in:0,1',
            'client_prefix' => 'sometimes|required|string|max:4',
            'client_logo' => 'nullable|image|max:2048',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        if ($request->hasFile('client_logo')) {
            $file = $request->file('client_logo');
            $filename = Str::slug($request->name ?? $client->name) . '-' . time() . '.' . $file->getClientOriginalExtension();


            if ($client->client_logo !== 'no-image.jpg' && Storage::disk('s3')->exists($client->client_logo)) {
                Storage::disk('s3')->delete($client->client_logo);
            }


            $path = Storage::disk('s3')->putFileAs('client-logos', $file, $filename);

            $request->merge(['client_logo' => config('app.url') . '/storage/' . $path]);
        }


        $client->update($request->all());

        return response()->json(['message' => 'Client updated successfully', 'data' => $client], 200);
    }


    public function destroy(string $id)
    {
        $client = MyClient::findOrFail($id);


        $client->delete();

        return response()->json(['message' => 'Client deleted successfully'], 200);
    }
}
