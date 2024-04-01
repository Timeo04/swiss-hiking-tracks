<script>
    // Chart-Komponenten importieren und registrieren
    import { Bar } from "svelte-chartjs";
    import {
        Chart as ChartJS,
        Title,
        Tooltip,
        Legend,
        LineElement,
        BarElement,
        LinearScale,
        PointElement,
        CategoryScale,
        Filler,
    } from "chart.js";
    ChartJS.register(
        Title,
        Tooltip,
        Legend,
        Filler,
        LineElement,
        BarElement,
        LinearScale,
        PointElement,
        CategoryScale,
    );

    // Übergabewerte initialisieren
    export let foreCastData;
    export let currentTime;

    // Aktuelle Zeit auslesen
    let time = [];
    const timePattern = /\d{2}:\d{2}/;
    currentTime = currentTime.match(timePattern)[0];

    // Zeitpunkte auslesen
    foreCastData.minutely_15.time.forEach((times) => {
        time.push(times.match(timePattern)[0]);
    });

    // Werte für Temperatur und Niederschlag auslesen
    let foreCastTemp = [];
    foreCastTemp = foreCastData.minutely_15.temperature_2m;
    let foreCastPrecipitation = [];
    foreCastPrecipitation = foreCastData.minutely_15.precipitation;

    // Daten für das Diagramm vorbereiten
    let forecastData = {
        labels: time,
        datasets: [
            {
                type: "line",
                label: "Temperatur (C°)",
                data: foreCastTemp,
                borderColor: "#EF562F",
                yAxisID: "y1",
            },
            {
                type: "bar",
                label: "Niederschlag (mm)",
                data: foreCastPrecipitation,
                backgroundColor: "#0000FF",
                order: 2,
                yAxisID: "y2",
            },
        ],
    };
    // Optionen für das Diagramm festlegen
    const forecastOptions = {
        plugins: {
            legend: {
                display: false,
            },
            tooltip: {
                enabled: true,
            },
            decimation: {
                enabled: true,
            },
        },
        scales: {
            x: {
                title: {
                    display: false, // set to true to show the title
                    text: "Zeit (min)",
                    font: {
                        weight: "bold",
                        size: 10,
                    },
                },
                ticks: {
                    callback: (value) => time[value],
                },
            },
            y1: {
                title: {
                    display: true,
                    text: "Temperatur (°C)",
                    font: {
                        weight: "bold",
                        size: 10,
                    },
                },
                type: "linear",
            },
            y2: {
                title: {
                    display: true,
                    text: "Niederschlag (mm)",
                    font: {
                        weight: "bold",
                        size: 10,
                    },
                },
                type: "linear",
                position: "right",
            },
        },
        aspectRatio: 1.5,
        maintainAspectRation: false,
        responsive: true,
        datasets: {
            line: {
                pointRadius: 0, // disable for all `'line'` datasets
            },
        },
    };
</script>

<div class="w-full h-full max-h-[400px] flex justify-center items-center">
    <!-- Graph anzeigen -->
    <Bar data={forecastData} options={forecastOptions} />
</div>
