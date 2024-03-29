<script>
    import WeatherChart from "./WeatherChart.svelte";

    export let track;

    let lat = track.geojson.coordinates[0][1];
    let lon = track.geojson.coordinates[0][0];

    let weatherData = null;
    let current = null;
    let daily = null;
    let currentTime = null;
    let dayName = [];

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
        currentTime = current.time;

        daily.time.forEach(time => {
            let dayString = new Date(time);
            dayName.push(Intl.DateTimeFormat("en-US", {weekday: "long"}).format(dayString));
        });
    });
</script>

<div clss="w-full h-full flex flex-col justif-start items-center gap-4">
    {#if weatherData != null}
        <!-- Diagramm des Wetters des Tages: Graph Wetter durch den Tag: 
        - Temperatur
        - Regenfall
        - Wettercode Darstellung durch Icon
        - Markierung der Uhrzeit + Darstellung der aktuellen Uhrzeit -->
        <div class="w-full rounded-[15px] bg-white p-4 flex flex-col gap-4 justify-center">
            {#if weatherData.minutely_15.temperature_2m != null}
                <WeatherChart foreCastData={weatherData} currentTime={currentTime}/>
            {/if}
            {#if daily.time != null}
                {#each daily.time as {}, i}
                    <div class="w-full grid grid-cols-3 text-sm justify-center text-center">
                        <p class="text-left">
                            {dayName[i]}
                        </p>
                        <div class="flex justify-center text-center">
                            <p>
                                {daily.temperature_2m_max[i] != null
                                    ? daily.temperature_2m_max[i]
                                    : "-"}
                            /
                                {daily.temperature_2m_min[i] != null
                                    ? daily.temperature_2m_min[i]
                                    : "-"} C°
                            </p>
                        </div>
                        <p class="text-right">
                            {daily.precipitation_sum[i] != null
                                ? daily.precipitation_sum[i]
                                : "-"} mm
                        </p>
                    </div>
                {/each}
            {/if}
        </div>
    {:else}
        <p>Loading...</p>
    {/if}
</div>
