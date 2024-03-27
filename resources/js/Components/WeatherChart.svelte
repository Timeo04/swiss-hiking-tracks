<script>
    import { Chart, Line } from "svelte-chartjs";
    // Funktion zum Berechnen der Länge importieren
    // import { calculateHeightPoints } from "@/utils/geojson/linestring";
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
    // import colors from "tailwindcss/colors";

    export let foreCastData;

    // let ForeCast = {
    //     labels: ForeCastData.map((row) => row.time),
    //     datasets: [
    //         {
    //             label: "Temperature",
    //             data: ForeCastData.map((row) => row.temperature),
    //             fill: false,
    //             borderColor: "rgb(75, 192, 192)",
    //             tension: 0.1,
    //         },
    //     ],
    // };

    console.log("ForeCastOne", foreCastData);

    let foreCastTemp = [];
    foreCastTemp = foreCastData.minutely_15.temperature_2m;
    console.log("ForeCast", foreCast);


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

    // ChartJS.defaults.color = colors.gray[500];
    // ChartJS.defaults.font.size = 12;
    // ChartJS.defaults.font.family =
    //     'Kanit, ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"';
    // ChartJS.defaults.font.weight = "lighter";

    // let heightPoints = calculateHeightPoints(track.geojson);
    // let maxHeight = Math.max(...heightPoints.map((row) => row.height));
    // let minHeight = Math.min(...heightPoints.map((row) => row.height));

    // let distanceData = {
    //     datasets: [
    //         {
    //             label: "Höhe über Meer",
    //             data: heightPoints.map((row) => ({
    //                 y: row.height,
    //                 x: row.distance / 1000,
    //             })),
    //             fill: "start",

    //             backgroundColor: "#EF562F",
    //             borderColor: "#EF562F",
    //         },
    //     ],
    // };
    // const distanceOptions = {
    //     plugins: {
    //         legend: {
    //             display: false,
    //         },
    //         tooltip: {
    //             enabled: false,
    //         },
    //         decimation: {
    //             enabled: true,
    //         },
    //     },
    //     scales: {
    //         x: {
    //             title: {
    //                 display: false, // set to true to show the title
    //                 text: "Distanz (km)",
    //                 font: {
    //                     weight: "bold",
    //                     size: 14,
    //                 },
    //             },
    //             type: "linear",
    //             ticks: {
    //                 callback: (value) => `${Math.round(value * 10) / 10} km`,
    //             },
    //             max: heightPoints[heightPoints.length - 1].distance / 1000,
    //             min: 0,
    //             beginAtZero: true,
    //         },
    //         y: {
    //             title: {
    //                 display: true,
    //                 text: "Höhe (m ü. M.)",
    //                 font: {
    //                     weight: "bold",
    //                     size: 14,
    //                 },
    //             },
    //             type: "linear",
    //             suggestedMax: Math.max(
    //                 minHeight + 150,
    //                 maxHeight + (maxHeight - minHeight) * 0.15,
    //             ),
    //         },
    //     },
    //     aspectRatio: 2.5,
    //     maintainAspectRation: false,
    //     responsive: true,
    //     datasets: {
    //         line: {
    //             pointRadius: 0, // disable for all `'line'` datasets
    //         },
    //     },
    // };
</script>

<div class="w-full max-h-[350px] flex justify-center items-center">
    <!-- <Line data={distanceData} options={distanceOptions} / -->
    <Line data={foreCast} />
</div>
