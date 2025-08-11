<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
  public function index()
  {
    if (!Session::has('user_authenticated')) {
      return redirect('/login')->with('error', 'Please login first to access weather data');
    }

    $user = [
      'email' => Session::get('user_email', 'careers@fidenz.com'),
      'name' => 'Weather App User'
    ];

    // Get real weather data using individual API calls
    $weatherData = $this->getRealWeatherDataIndividual();

    return view('weather', compact('user', 'weatherData'));
  }

  private function getRealWeatherDataIndividual()
  {
    // Cache for 5 minutes as per assignment
    return Cache::remember('weather_data_individual', 300, function () {
      $apiKey = 'ab50cd6809302850774f1c6b5318aa55';

      // City IDs from assignment
      $cities = [
        ['id' => 524901, 'name' => 'Moscow'],
        ['id' => 703448, 'name' => 'Kiev'],
        ['id' => 2643743, 'name' => 'London'],
        ['id' => 5128581, 'name' => 'New York'],
        ['id' => 1850147, 'name' => 'Tokyo']
      ];

      Log::info('🌤️ FETCHING REAL WEATHER DATA - Individual API Calls');

      $weatherData = [];

      foreach ($cities as $city) {
        try {
          // Individual API call for each city
          $url = "https://api.openweathermap.org/data/2.5/weather?id={$city['id']}&units=metric&appid={$apiKey}";

          Log::info('📡 Calling API for ' . $city['name'], ['url' => $url]);

          $response = Http::timeout(15)->get($url);

          if ($response->successful()) {
            $data = $response->json();

            Log::info(' SUCCESS for ' . $city['name'], [
              'temp' => $data['main']['temp'],
              'weather' => $data['weather'][0]['description']
            ]);

            $weatherData[] = [
              'name' => $data['name'] ?? $city['name'],
              'temperature' => round($data['main']['temp']),
              'description' => ucfirst($data['weather'][0]['description']),
              'icon' => $data['weather'][0]['icon'],
              'country' => $data['sys']['country'],
              'humidity' => $data['main']['humidity'],
              'feels_like' => round($data['main']['feels_like']),
              'wind_speed' => round($data['wind']['speed'], 1),
              'pressure' => $data['main']['pressure'],
              'data_source' => ' LIVE OPENWEATHER API',
              'api_status' => 'SUCCESS',
              'timestamp' => now()->format('H:i:s'),
              'api_method' => 'Individual API calls'
            ];
          } else {
            Log::error(' API FAILED for ' . $city['name'], [
              'status' => $response->status(),
              'response' => $response->json()
            ]);

            // Add error entry
            $weatherData[] = [
              'name' => $city['name'],
              'temperature' => 0,
              'description' => 'API Error: ' . $response->status(),
              'icon' => '01d',
              'country' => 'ERR',
              'humidity' => 0,
              'feels_like' => 0,
              'wind_speed' => 0,
              'pressure' => 0,
              'data_source' => ' API ERROR',
              'api_status' => 'ERROR',
              'timestamp' => now()->format('H:i:s'),
              'error_details' => $response->status()
            ];
          }
        } catch (\Exception $e) {
          Log::error(' EXCEPTION for ' . $city['name'], ['error' => $e->getMessage()]);

          // Add exception entry
          $weatherData[] = [
            'name' => $city['name'],
            'temperature' => 0,
            'description' => 'Exception: ' . $e->getMessage(),
            'icon' => '01d',
            'country' => 'ERR',
            'humidity' => 0,
            'feels_like' => 0,
            'wind_speed' => 0,
            'pressure' => 0,
            'data_source' => ' EXCEPTION',
            'api_status' => 'EXCEPTION',
            'timestamp' => now()->format('H:i:s')
          ];
        }
      }

      Log::info(' FINAL RESULT', [
        'total_cities' => count($weatherData),
        'successful_calls' => count(array_filter($weatherData, fn($city) => $city['api_status'] === 'SUCCESS'))
      ]);

      return $weatherData;
    });
  }
}
