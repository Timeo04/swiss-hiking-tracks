<!-- Coppied from https://open-meteo.com/en/docs/ -->
<script>
    import WeatherChart from "./WeatherChart.svelte";

    export let lat = 51.962944;
    export let lon = 7.6286944;

    let weatherData = null;
    let current = null;
    let daily = null;
    let currentTime = null;

    let paramsCurrent = [
        "temperature_2m",
        "relative_humidity_2m",
        "apparent_temperature",
        "is_day",
        "precipitation",
        "rain",
        "showers",
        "snowfall",
        "weather_code",
        "cloud_cover",
        "wind_speed_10m",
        "wind_direction_10m",
    ];

    let paramsHourly = ["temperature_2m"];

    let paramsDaily = [
        "weather_code",
        "temperature_2m_max",
        "temperature_2m_min",
        "sunrise",
        "sunset",
        "daylight_duration",
        "sunshine_duration",
        "uv_index_max",
        "precipitation_sum",
        "rain_sum",
        "showers_sum",
        "snowfall_sum",
        "precipitation_hours",
    ];

    let paramsMinutely = ["temperature_2m", "precipitation"];

    async function getWeatherData(lat, lon) {
        const response = await fetch(
            "https://api.open-meteo.com/v1/forecast?latitude=" +
                lat +
                "&longitude=" +
                lon +
                "&current=" +
                paramsCurrent +
                "&hourly=" +
                paramsHourly +
                "&daily=" +
                paramsDaily +
                "&minutely_15=" +
                paramsMinutely +
                "&timezone=Europe%2FBerlin&forecast_minutely_15=96",
        );
        let weatherData = await response.json();
        return weatherData;
    }

    getWeatherData(lat, lon).then((data) => {
        weatherData = data;
        current = weatherData.current;
        daily = weatherData.daily;
        console.log("current Weather", weatherData);
        currentTime = current.time;
    });
</script>

<div>
    {#if weatherData != null}
        <!-- Diagramm des Wetters des Tages: Graph Wetter durch den Tag: 
        - Temperatur
        - Regenfall
        - Wettercode Darstellung durch Icon
        - Markierung der Uhrzeit + Darstellung der aktuellen Uhrzeit -->

        <div class="w-full">
            {#if weatherData.minutely_15.temperature_2m != null}
                
                <WeatherChart foreCastData={weatherData} currentTime={currentTime}/>
                <!-- <Line data={weatherData} /> -->
            {/if}
        </div>
        <h3>Current weather</h3>
        <!-- <p>
            {current.is_day != null
                ? current.is_day == 1
                    ? "Day"
                    : "Night"
                : "-"}
        </p>
        <p>
            Temperature: {current.temperature_2m != null
                ? current.temperature_2m
                : "-"}°C
        </p>
        <p>
            Humidity: {current.relative_humidity_2m != null
                ? current.relative_humidity_2m
                : "-"}%
        </p>
        <p>
            Apparent Temperature: {current.apparent_temperature != null
                ? current.apparent_temperature
                : "-"}°C
        </p>
        <p>
            Precipitation: {current.precipitation != null
                ? current.precipitation
                : "-"} mm
        </p>
        <p>
            Rain: {current.rain != null ? current.rain : "-"} mm
        </p>
        <p>
            Showers: {current.showers != null ? current.showers : "-"} mm
        </p>
        <p>
            Snowfall: {current.snowfall != null ? current.snowfall : "-"} mm
        </p>
        <p>
            Cloud cover: {current.cloud_cover != null
                ? current.cloud_cover
                : "-"}%
        </p>
        <p>
            UV index: {current.uv_index_max != null
                ? current.uv_index_max
                : "-"}
        </p>
        <p>
            Wind speed: {current.wind_speed_10m != null
                ? current.wind_speed_10m
                : "-"} km/h
        </p>
        <p>
            Wind direction: {current.wind_direction_10m != null
                ? current.wind_direction_10m
                : "-"}°
        </p>
        <p>
            Weather code: {current.weather_code != null
                ? current.weather_code
                : "-"}
        </p> -->
    {:else}
        <p>Loading...</p>
    {/if}
</div>
