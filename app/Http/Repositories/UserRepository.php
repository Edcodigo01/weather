<?php

namespace App\Http\Repositories;
use App\Models\User;
use Cache;
use Illuminate\Http\Request;

class UserRepository
{
    protected $tagCacheList = "user-list";

    public function getAll(Request $request)
    {
        // Se verifican los parámetros de la solicitud HTTP, dependiendo de estos sera el nombre de la lista guardada en cache
        $nameCacheList = $this->tagCacheList;
        $params = $request->only(['page', 'per_page', 'sort_by', 'sort_order']);

        foreach ($params as $key => $filter) {
            $nameCacheList .= "-$key-$filter";
        }

        // Verifica si existe en cache redis, de lo contrario retorna desde postgress
        return Cache::tags([$this->tagCacheList])->remember($nameCacheList, now()->addMinutes(60), function () use ($request) {
            return User::select('users.id', 'users.name', 'users.email', 'weather.condition_icon', 'weather.temp_c', 'weather.condition_text')
                ->leftJoin('weather', 'users.id', '=', 'weather.user_id')
                ->orderBy($request->sort_by, $request->sort_order)
                ->paginate($request->per_page);
        });
    }

    public function get($id)
    {
        // Verifica si existe en cache redis, de lo contrario retorna desde postgress
        return Cache::remember("$this->tagCacheList-$id", now()->addMinutes(60), function () use ($id) {
            return User::select(
                'users.id',
                'users.name',
                'users.email',
                'weather.location',
                'weather.region',
                'weather.country',
                'weather.localtime',
                'weather.temp_c',
                'weather.is_day',
                'weather.condition_text',
                'weather.condition_icon',
                'weather.wind_dir',
                'weather.wind_kph',
                'weather.feelslike_c',
                'weather.gust_kph',
            )
                ->leftJoin('weather', 'users.id', '=', 'weather.user_id')
                ->where("users.id", $id)
                ->first();
        });
    }
}
