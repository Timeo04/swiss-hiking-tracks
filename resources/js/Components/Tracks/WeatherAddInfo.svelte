<script>
    // UI-Komponenten importieren
    import { Spinner } from "flowbite-svelte";
    // WeatherChart-Komponente importieren
    import WeatherChart from "./WeatherChart.svelte";

    // Übergabewerte initialisieren
    export let track;

    // Koordinaten des ersten Punktes des Tracks auslesen
    let lat = track.geojson.coordinates[0][1];
    let lon = track.geojson.coordinates[0][0];

    // Variabeln für Wetterdaten initialisieren
    let weatherData = null;
    let current = null;
    let daily = null;
    let currentTime = null;
    let dayName = [];

    // Wetter-Parameter festlegen
    const paramsCurrent = [
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
    const paramsHourly = ["temperature_2m"];
    const paramsDaily = [
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
    const paramsMinutely = ["temperature_2m", "precipitation"];

    // Wetterdaten mit Parametern von Open-Meteo abrufen
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

    // Wetterdaten abrufen und in Variabeln speichern
    getWeatherData(lat, lon).then((data) => {
        weatherData = data;
        current = weatherData.current;
        daily = weatherData.daily;
        currentTime = current.time;
        // Wochentage auslesen
        daily.time.forEach((time) => {
            let dayString = new Date(time);
            dayName.push(
                Intl.DateTimeFormat("de-CH", { weekday: "long" }).format(
                    dayString,
                ),
            );
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
        <div
            class="w-full rounded-[15px] bg-white p-4 flex flex-col gap-4 justify-center"
        >
            {#if weatherData.minutely_15.temperature_2m != null}
                <!-- Wetter-Diagramm anzeigen -->
                <WeatherChart foreCastData={weatherData} {currentTime} />
            {/if}
            {#if daily.time != null}
                {#each daily.time as { }, i}
                    <div
                        class="w-full grid grid-cols-3 text-sm justify-center text-center"
                    >
                        <!-- Wochentag anzeigen -->
                        <p class="text-left">
                            {dayName[i]}
                        </p>
                        <!-- Minimale und maximale Temperatur anzeigen -->
                        <div class="flex justify-center text-center">
                            <p>
                                {daily.temperature_2m_min[i] != null
                                    ? daily.temperature_2m_min[i]
                                    : "-"}° /
                                {daily.temperature_2m_max[i] != null
                                    ? daily.temperature_2m_max[i]
                                    : "-"}°
                            </p>
                        </div>
                        <!-- Täglicher Niederschlag anzeigen -->
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
        <!-- Spinner anzeigen -->
        <div class="flex h-full w-full justify-center items-center">
            <Spinner />
        </div>
    {/if}
</div>
