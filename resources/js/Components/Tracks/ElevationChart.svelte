<script>
    // Importieren und registrieren der Chart-Komponenten
    import { Line } from "svelte-chartjs";
    import {
        Chart as ChartJS,
        Title,
        Tooltip,
        Legend,
        LineElement,
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
        LinearScale,
        PointElement,
        CategoryScale,
    );
    // Funktion zum Berechnen der Länge importieren
    import { calculateHeightPoints } from "@/utils/geojson/linestring";
    // Tailwindcss Farben importieren
    import colors from "tailwindcss/colors";

    export let track;

    // ChartJS-Standard-Aussehen anpassen
    ChartJS.defaults.color = colors.gray[500];
    ChartJS.defaults.font.size = 12;
    ChartJS.defaults.font.family =
        'Kanit, ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"';

    // Höhenpunkte berechnen
    let heightPoints = calculateHeightPoints(track.geojson);
    let maxHeight = Math.max(...heightPoints.map((row) => row.height));
    let minHeight = Math.min(...heightPoints.map((row) => row.height));

    // Daten und Optionen für die Distanz-Höhen-Diagramm
    let distanceData = {
        datasets: [
            {
                label: "Höhe über Meer",
                data: heightPoints.map((row) => ({
                    y: row.height, // Höhe in m ü. M.
                    x: row.distance / 1000, // Distanz in km
                })),
                fill: "start",

                backgroundColor: "#EF562F",
                borderColor: "#EF562F",
            },
        ],
    };
    const distanceOptions = {
        plugins: {
            legend: {
                display: false,
            },
            tooltip: {
                enabled: false,
            },
            decimation: {
                enabled: true,
            },
        },
        scales: {
            x: {
                title: {
                    display: false, // set to true to show the title
                    text: "Distanz (km)",
                    font: {
                        weight: "bold",
                        size: 14,
                    },
                },
                type: "linear",
                ticks: {
                    callback: (value) => `${Math.round(value * 10) / 10} km`,
                },
                max: heightPoints[heightPoints.length - 1].distance / 1000,
                min: 0,
                beginAtZero: true,
            },
            y: {
                title: {
                    display: true,
                    text: "Höhe (m ü. M.)",
                    font: {
                        weight: "bold",
                        size: 14,
                    },
                },
                type: "linear",
                suggestedMax: Math.max(
                    minHeight + 150,
                    maxHeight + (maxHeight - minHeight) * 0.15,
                ),
            },
        },
        aspectRatio: 2.5,
        maintainAspectRation: false,
        responsive: true,
        datasets: {
            line: {
                pointRadius: 0, // disable for all `'line'` datasets
            },
        },
    };
</script>

<div class="w-full max-h-[350px] flex justify-center items-center">
    <!-- Diagramm -->
    <Line data={distanceData} options={distanceOptions} />
</div>
