<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function getWeather(Request $request)
    {
        // 1. Get city from request
        $city = $request->query('city', 'Paris');

        // 2. Call external API
        $response = Http::get(config('services.openweather.url'), [
            'q' => $city,
            'appid' => config('services.openweather.key'),
            'units' => 'metric',
        ]);

        // 3. Handle API error
        if ($response->failed()) {
            return response()->json([
                'error' => 'Unable to fetch weather data'
            ], 500);
        }

        // 4. Return clean JSON
        return response()->json([
            'city' => $response['name'],
            'temperature' => $response['main']['temp'],
            'description' => $response['weather'][0]['description'],
            'humidity' => $response['main']['humidity'],
            'wind_speed' => $response['wind']['speed'],
        ]);
    }
}
