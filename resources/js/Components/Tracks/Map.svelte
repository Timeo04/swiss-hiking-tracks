<script>
    // export let track;
    // export let geojson = track.geojson;
    export let tracks = [];
    // export let track;
    // export let geojsons = [];
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

    import { onDestroy, onMount } from "svelte";

    let mapContainer;
    let map;
    let startPos = [8.231726, 46.798473];
    let zoomLevel = 8;
    let bounds = null;

    let coordinates = [];
    tracks.forEach((track) => {
        coordinates.push(...track.geojson.coordinates);
    });
    bounds = coordinates.reduce(
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
            zoom: zoomLevel,
            hash: false,
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
            tracks.forEach((track) => {
                _map.addSource("track_" + track.id, {
                    type: "geojson",
                    data: track.geojson,
                });
                _map.addLayer({
                    id: "track_" + track.id,
                    type: "line",
                    source: "track_" + track.id,
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
            _map.resize();
            if (bounds != null) {
                _map.fitBounds(bounds, {
                    padding: 100,
                });
            }
        });
        if (bounds != null) {
            _map.fitBounds(bounds, {
                padding: 100,
            });
        }
        map = _map;
    };

    onMount(() => {
        init();
    });

    onDestroy(() => {
        if (map != null) map.remove();
    });
</script>

<div class="map w-full h-full" data-testid="map" bind:this={mapContainer} />

<style>
    @import "maplibre-gl/dist/maplibre-gl.css";
</style>
