<script>
    import { Chart, Line, Bar } from "svelte-chartjs";
    // Funktion zum Berechnen der Länge importieren
    // import { calculateHeightPoints } from "@/utils/geojson/linestring";
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
    import { AngleRightOutline } from "flowbite-svelte-icons";
    // import colors from "tailwindcss/colors";

    export let foreCastData;
    export let currentTime;
    let time = [];
    const timePattern = /\d{2}:\d{2}/;

    currentTime = currentTime.match(timePattern)[0];

    foreCastData.minutely_15.time.forEach((times) => {
        time.push(times.match(timePattern)[0]);
    });

    let foreCastTemp = [];
    foreCastTemp = foreCastData.minutely_15.temperature_2m;

    let foreCastPrecipitation = [];
    foreCastPrecipitation = foreCastData.minutely_15.precipitation;

    // console.log("ForeCast", foreCastTemp);
    // console.log("Current Time", currentTime);
    // console.log("Time", time);
    // console.log("ForeCastOne", foreCastData);
    // console.log("ForeCastPrecipitation", foreCastPrecipitation);

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

    //     const mixedChart = new Chart(ctx, {
    //    type: 'bar',
    //    data: {
    //        datasets: [{
    //            label: 'Bar Dataset',
    //            data: [10, 20, 30, 40],
    //            // this dataset is drawn below
    //            order: 2
    //        }, {
    //            label: 'Line Dataset',
    //            data: [10, 10, 10, 10],
    //            type: 'line',
    //            // this dataset is drawn on top
    //            order: 1
    //        }],
    //        labels: ['January', 'February', 'March', 'April']
    //    },
    //    options: options
    // });

    // let forecastDataPrecipitation = {
    //     labels: time,
    //     datasets: [
    //         {
    //             label: "Niederschlag (mm)",
    //             // data: foreCastTemp.map((temp, index) => ({
    //             // y: temp,
    //             // x: (index * 15)/60,
    //             // x: foreCastData.minutely_15.time,
    //             // })),
    //             data: foreCastPrecipitation,
    //             // fill: "start",
    //             // backgroundColor: "#EF562F",
    //             borderColor: "#EFEFEF",
    //         },
    //     ],
    // };
    // let forecastDataTemp = {
    //     labels: time,
    //     datasets: [
    //         {
    //             label: "Temperatur (C°)",
    //             // data: foreCastTemp.map((temp, index) => ({
    //             // y: temp,
    //             // x: (index * 15)/60,
    //             // x: foreCastData.minutely_15.time,
    //             // })),
    //             data: foreCastTemp,
    //             // fill: "start",
    //             // backgroundColor: "#EF562F",
    //             borderColor: "#EF562F",
    //         },
    //     ],
    // };
    let forecastData = {
        labels: time,
        // data: {
        datasets: [
            {
                type: "line",
                label: "Temperatur (C°)",
                // data: foreCastTemp.map((temp, index) => ({
                // y: temp,
                // x: (index * 15)/60,
                // x: foreCastData.minutely_15.time,
                // })),
                data: foreCastTemp,
                // fill: "start",
                // backgroundColor: "#EF562F",
                borderColor: "#EF562F",
                // order: 1,
                yAxisID: "y1",
            },
            {
                type: "bar",
                label: "Niederschlag (mm)",
                // data: foreCastTemp.map((temp, index) => ({
                // y: temp,
                // x: (index * 15)/60,
                // x: foreCastData.minutely_15.time,
                // })),
                data: foreCastPrecipitation,
                // fill: "start",
                backgroundColor: "#0000FF",
                // borderColor: "#FF0000",
                order: 2,
                yAxisID: "y2",
            },
        ],
        // },
    };
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
                // type: "linear",
                ticks: {
                    callback: (value) => time[value],
                },
                // ticks: {
                //     callback: (value) => `${Math.round(value * 10) / 10} km`,
                // },
                // max: heightPoints[heightPoints.length - 1].distance / 1000,
                // min: 0,
                // max: 24,
                // beginAtZero: true,
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
                // suggestedMax: Math.max(
                //     minHeight + 150,
                //     maxHeight + (maxHeight - minHeight) * 0.15,
                // ),
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
    <Bar data={forecastData} options={forecastOptions} />
    <!-- <Line data={forecastDataPrecipitation} options={forecastOptions} /> -->
</div>
