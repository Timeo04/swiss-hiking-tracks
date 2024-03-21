<script>
    export let track;
    export let geojson = track.geojson;
    export let imageryBaseMap = false;
    export let interactiveMap = true;
    export let cooperativeGestures = true;

    import {
        Map,
        NavigationControl,
        GeolocateControl,
        ScaleControl,
        AttributionControl,
        LngLatBounds,
    } from "maplibre-gl";

    import { onDestroy } from 'svelte';
    
    let mapContainer;
    let map;
    let startPos = [8.04494, 47.38579];

    const coordinates = geojson.coordinates;
    const bounds = coordinates.reduce(
        (bounds, coord) => {
            return bounds.extend(coord);
        },
        new LngLatBounds(coordinates[0], coordinates[0]),
    );

    const init = () => {
        const _map = new Map({
            container: mapContainer,
            style: imageryBaseMap
                ? "https://vectortiles.geo.admin.ch/styles/ch.swisstopo.leichte-basiskarte-imagery.vt/style.json"
                : `https://vectortiles.geo.admin.ch/styles/ch.swisstopo.basemap.vt/style.json`,
            center: startPos,
            zoom: 15,
            hash: true,
            cooperativeGestures: interactiveMap && cooperativeGestures,
            locale: {
                "CooperativeGesturesHandler.WindowsHelpText":
                    "Mit Strg + Maus bewegen und scrollen, um die Karte zu bewegen und zoomen.",
                "CooperativeGesturesHandler.MacHelpText":
                    "Mit ⌘ + Maus bewegen und scrollen, um die Karte zu bewegen und zoomen.",
                "CooperativeGesturesHandler.MobileHelpText":
                    "Mit zwei Fingern Karte bewegen und zoomen.",
            },
            attributionControl: false,
            interactive: interactiveMap,
        });
        if (interactiveMap) {
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
        }

        _map.on("load", () => {
            _map.addSource("LineString", {
                type: "geojson",
                data: geojson,
            });
            _map.addLayer({
                id: "LineString",
                type: "line",
                source: "LineString",
                layout: {
                    "line-join": "round",
                    "line-cap": "round",
                },
                paint: {
                    "line-color": "#ff0000",
                    "line-width": 5,
                },
            });
        });
        _map.fitBounds(bounds, {
            padding: 100,
        });
        map = _map;
    };

    $: if (mapContainer) {
        init();
    }

    onDestroy(() => {
        if (map != null) map.remove();
    });
</script>

<div class="map w-full h-[600px]" data-testid="map" bind:this={mapContainer} />

<style>
    @import "maplibre-gl/dist/maplibre-gl.css";
</style>
