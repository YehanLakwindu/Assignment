<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-indigo-200 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        🌤️ Weather Dashboard
                    </h1>
                    <p class="text-gray-600 mt-1">
                        Welcome, <strong>{{ $user['email'] }}</strong>
                    </p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="location.reload()" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                        🔄 Refresh
                    </button>
                    <a href="{{ route('logout') }}" 
                       class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                        🚪 Logout
                    </a>
                </div>
            </div>
        </div>

        <!-- Weather Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
            @foreach($weatherData as $city)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    
                    <!-- City Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-4 text-white">
                        <div class="flex justify-between items-center mb-3">
                            <div>
                                <h3 class="text-xl font-bold">{{ $city['name'] }}</h3>
                                <p class="text-blue-100 text-sm">{{ $city['country'] }}</p>
                            </div>
                            <img src="https://openweathermap.org/img/w/{{ $city['icon'] }}.png" 
                                 alt="{{ $city['description'] }}" 
                                 class="w-12 h-12">
                        </div>
                        
                        <div class="text-3xl font-bold">{{ $city['temperature'] }}°C</div>
                        <p class="text-blue-100 capitalize">{{ $city['description'] }}</p>
                        
                        <!-- Status Badge -->
                        <div class="mt-2">
                            @if(isset($city['api_status']) && $city['api_status'] === 'SUCCESS')
                                <span class="text-xs bg-green-500 text-white px-2 py-1 rounded-full">
                                    🟢 LIVE DATA
                                </span>
                            @else
                                <span class="text-xs bg-yellow-500 text-white px-2 py-1 rounded-full">
                                    🟡 DEMO DATA
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Weather Details -->
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="text-center">
                                <p class="text-gray-500">Feels like</p>
                                <p class="font-bold">{{ $city['feels_like'] }}°C</p>
                            </div>
                            <div class="text-center">
                                <p class="text-gray-500">Humidity</p>
                                <p class="font-bold">{{ $city['humidity'] }}%</p>
                            </div>
                            <div class="text-center">
                                <p class="text-gray-500">Wind</p>
                                <p class="font-bold">{{ $city['wind_speed'] }} m/s</p>
                            </div>
                            <div class="text-center">
                                <p class="text-gray-500">Pressure</p>
                                <p class="font-bold">{{ $city['pressure'] }} hPa</p>
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <p class="text-xs text-gray-500">
                                Updated: {{ $city['timestamp'] ?? now()->format('H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-8">
            <div class="bg-white rounded-lg shadow-md p-4">
                <p class="text-sm text-gray-600">
                    🕒 Data cached for 5 minutes • 
                    📅 Last updated: {{ now()->format('M d, H:i') }} • 
                    🔌 Powered by OpenWeatherMap API
                </p>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide messages
        setTimeout(() => {
            const messages = document.querySelectorAll('.fixed');
            messages.forEach(msg => msg.remove());
        }, 3000);
    </script>
</body>
</html>