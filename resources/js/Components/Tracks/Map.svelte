<script>
    export let track;
    let geojson = track.geojson;

    import {
        Map,
        NavigationControl,
        GeolocateControl,
        ScaleControl,
        AttributionControl,
    } from "maplibre-gl";
    // import { map } from "$lib/stores";

    let mapContainer;
    let map;
    let startPos = [8.04494, 47.38579];

    const init = () => {
        const _map = new Map({
            container: mapContainer,
            style: `https://vectortiles.geo.admin.ch/styles/ch.swisstopo.basemap.vt/style.json`,
            center: startPos,
            zoom: 15,
            hash: true,
            cooperativeGestures: true,
            attributionControl: false,
        });
        _map.addControl(new NavigationControl({}), "top-right");
        _map.addControl(
            new GeolocateControl({
                positionOptions: { enableHighAccuracy: true },
                trackUserLocation: true,
            }),
            "top-right",
        );
        _map.addControl(
            new ScaleControl({ maxWidth: 80, unit: "metric" }),
            "bottom-left",
        );
        _map.addControl(
            new AttributionControl({ compact: true }),
            "bottom-right",
        );

        map = _map;
        // map.set(_map);
    };

    $: if (mapContainer) {
        init();
    }
</script>

<!-- Platzhalter -->
<!-- <div class="w-full h-full"> -->
<!-- <img
        src="https://kstatic.googleusercontent.com/files/9b837ed639746f02c66e5f00bec26588fc91fc4689292b791a80971eac477e72f8f0b00fdb8dec996f5e6fc0cb777946d3e4c7ee4dd9238d50e2bf2922f4a808"
        alt="Map"
        class="w-full h-full"
    /> -->
<div
    class="map w-full h-[600px]"
    data-testid="map"
    bind:this={mapContainer}
/>
<!-- </div> -->

<style>
    @import 'maplibre-gl/dist/maplibre-gl.css';
</style>