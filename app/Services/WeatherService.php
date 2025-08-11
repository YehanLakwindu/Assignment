<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WeatherService
{
  private $apiKey;
  private $baseUrl = 'http://api.openweathermap.org/data/2.5';

  public function __construct()
  {
    $this->apiKey = env('WEATHER_API_KEY');
  }

  public function getWeatherData()
  {
    return Cache::remember('weather_data', 300, function () {
      $cityIds = '524901,703448,2643743,5128581,1850147'; // Moscow, Kiev, London, New York, Tokyo

      try {
        Log::info('🌤️ Fetching weather data from OpenWeatherMap API');

        $response = Http::timeout(15)->get("{$this->baseUrl}/group", [
          'id' => $cityIds,
          'units' => 'metric',
          'appid' => $this->apiKey
        ]);

        if ($response->successful()) {
          $data = $response->json();
          Log::info(' Weather API successful', ['cities_count' => count($data['list'] ?? [])]);
          return $this->formatWeatherData($data);
        } else {
          Log::error(' Weather API Error: ' . $response->status() . ' - ' . $response->body());
          return $this->getFallbackData();
        }
      } catch (\Exception $e) {
        Log::error(' Weather API Exception: ' . $e->getMessage());
        return $this->getFallbackData();
      }
    });
  }

  private function formatWeatherData($data)
  {
    if (!isset($data['list']) || empty($data['list'])) {
      return $this->getFallbackData();
    }

    return collect($data['list'])->map(function ($city) {
      return [
        'name' => $city['name'] ?? 'Unknown',
        'temperature' => round($city['main']['temp'] ?? 0),
        'description' => ucfirst($city['weather'][0]['description'] ?? 'No data'),
        'icon' => $city['weather'][0]['icon'] ?? '01d',
        'country' => $city['sys']['country'] ?? '',
        'humidity' => $city['main']['humidity'] ?? 0,
        'feels_like' => round($city['main']['feels_like'] ?? 0),
        'wind_speed' => round(($city['wind']['speed'] ?? 0), 1),
        'pressure' => $city['main']['pressure'] ?? 0,
      ];
    })->sortBy('name')->values()->toArray();
  }

  private function getFallbackData()
  {
    Log::info('🔄 Using fallback weather data');
    return [
      [
        'name' => 'London',
        'temperature' => 15,
        'description' => 'Partly cloudy',
        'icon' => '02d',
        'country' => 'GB',
        'humidity' => 65,
        'feels_like' => 13,
        'wind_speed' => 3.2,
        'pressure' => 1013,
      ],
      [
        'name' => 'Tokyo',
        'temperature' => 22,
        'description' => 'Clear sky',
        'icon' => '01d',
        'country' => 'JP',
        'humidity' => 55,
        'feels_like' => 21,
        'wind_speed' => 1.8,
        'pressure' => 1015,
      ],
      [
        'name' => 'Moscow',
        'temperature' => -2,
        'description' => 'Light snow',
        'icon' => '13d',
        'country' => 'RU',
        'humidity' => 78,
        'feels_like' => -5,
        'wind_speed' => 2.1,
        'pressure' => 1008,
      ]
    ];
  }
}
