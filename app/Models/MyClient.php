<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class MyClient extends Model
{
    use SoftDeletes;

    protected $table = 'my_client';

    protected $fillable = [
        'name',
        'slug',
        'is_project',
        'self_capture',
        'client_prefix',
        'client_logo',
        'address',
        'phone_number',
        'city'
    ];


    protected static function booted(): void
    {

        static::created(function ($client) {
            Cache::put('client:' . $client->slug, json_encode($client->toArray()));
        });


        static::updated(function ($client) {

            if ($client->isDirty('slug') && $client->getOriginal('slug')) {
                Cache::forget('client:' . $client->getOriginal('slug'));
            }


            if (!$client->deleted_at) {
                Cache::put('client:' . $client->slug, json_encode($client->toArray()));
            } else {

                Cache::forget('client:' . $client->slug);
            }
        });


        static::deleted(function ($client) {
            Cache::forget('client:' . $client->slug);
        });
    }
}
