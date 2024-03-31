<script>
    import { router } from "@inertiajs/svelte";

    // export let track;
    // export let geojson = track.geojson;
    export let tracks = [];
    // export let track;
    // export let geojsons = [];
    export let imageryBaseMap = false;
    export let interactiveMap = true;
    export let cooperativeGestures = true;
    export let popups = false;

    import {
        Map,
        NavigationControl,
        GeolocateControl,
        ScaleControl,
        AttributionControl,
        LngLatBounds,
        Popup,
    } from "maplibre-gl";

    import { onDestroy, onMount } from "svelte";
    import MapPopupContent from "./MapPopupContent.svelte";

    let mapContainer;
    let map;
    let startPos = [8.231726, 46.798473];
    let zoomLevel = 8;
    let bounds = null;

    let coordinates = [];
    tracks.forEach((track) => {
        coordinates.push(...track.geojson.coordinates);
    });

    function createBounds(coordinates) {
        return coordinates.reduce(
            (bounds, coord) => {
                return bounds.extend(coord);
            },
            new LngLatBounds(coordinates[0], coordinates[0]),
        );
    }

    bounds = createBounds(coordinates);

    const init = () => {
        const _map = new Map({
            container: mapContainer,
            style: imageryBaseMap
                ? "https://vectortiles.geo.admin.ch/styles/ch.swisstopo.leichte-basiskarte-imagery.vt/style.json"
                : `https://vectortiles.geo.admin.ch/styles/ch.swisstopo.basemap.vt/style.json`,
            center: startPos,
            zoom: zoomLevel,
            bounds: bounds,
            fitBoundsOptions: {
                padding: 100,
            },
            pitchWithRotate: false,
            touchPitch: false,
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

        _map.on("load", async () => {
            tracks.forEach((track) => {
                let layerName = "track_" + track.id;
                _map.addSource(layerName, {
                    type: "geojson",
                    data: track.geojson,
                    // maxzoom: 20,
                });
                // _map.addSource(layerName, {
                //     type: "geojson",
                //     data: track.geojson,
                // });
                _map.addLayer({
                    id: layerName,
                    type: "line",
                    source: layerName,
                    layout: {
                        "line-join": "round",
                        "line-cap": "round",
                    },
                    paint: {
                        "line-color": "#ef562f",
                        "line-width": 5,
                    },
                    minzoom: 10,
                });
                if (popups) {
                    _map.on("click", layerName, (e) => {
                        console.log("clicked", e, track);
                        console.log(e.lngLat);
                        let popup = new Popup()
                            .setLngLat(e.lngLat)
                            .setText("")
                            .on("open", (e) => {
                                console.log("popup opened");
                                // console.log(this);
                                console.log(e.target);
                                let popupContentElement = e.target._content;

                                let anchorElement = e.target._closeButton;

                                let popupContentComponent = new MapPopupContent(
                                    {
                                        target: popupContentElement,
                                        anchor: anchorElement,
                                        props: {
                                            track,
                                        },
                                    },
                                );

                                // Document.querySelector(".maplibregl-popup-content").classList.add("sht-popup");
                            })
                            .on("close", (e) => {
                                console.log("popup closed");
                                // destroy popup
                            })
                            .addTo(_map)
                            .addClassName("sht-popup");

                        // popup;

                        // popup
                        // router.visit(route("tracks.show", track.id), {
                        //     preserveScroll: false,
                        // });
                    });
                    _map.on("mouseenter", layerName, () => {
                        _map.getCanvas().style.cursor = "pointer";
                    });

                    _map.on("mouseleave", layerName, () => {
                        _map.getCanvas().style.cursor = "";
                    });
                }
            });
            let image = await map.loadImage("/logo.png");

            _map.addImage("marker", image.data);
            tracks.forEach((track) => {
                let layerName = "track_icon_" + track.id;
                _map.addSource(layerName, {
                    type: "geojson",
                    data: {
                        type: "Point",
                        coordinates: track.geojson.coordinates[0],
                    },
                });
                _map.addLayer({
                    id: layerName,
                    type: "symbol",
                    source: layerName,
                    layout: {
                        "icon-image": "marker",
                        "icon-size": 0.18,
                        "icon-anchor": "bottom",
                    },
                    maxzoom: 10,
                });
                _map.on("mouseenter", layerName, () => {
                    _map.getCanvas().style.cursor = "pointer";
                });

                _map.on("mouseleave", layerName, () => {
                    _map.getCanvas().style.cursor = "";
                });

                _map.on("click", layerName, (event) => {
                    _map.fitBounds(
                        createBounds(track.geojson.coordinates),
                        {
                            padding: 100,
                            duration: 2500,
                        },
                    );
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

<style lang="postcss">
    @import "maplibre-gl/dist/maplibre-gl.css";

    :global(.sht-popup) {
        max-width: none !important;
    }
    :global(.sht-popup) :global(.maplibregl-popup-content) {
        /* @apply bg-primary-600; */
        @apply font-sans;
        @apply rounded-lg;
        @apply p-3;
    }
    :global(.sht-popup) :global(.maplibregl-popup-close-button) {
        /* @apply mr-3; */
        @apply w-5 h-5;
        @apply flex justify-center items-center;
        @apply text-2xl;
        /* @apply bg-primary-600 text-white align-middle; */
        @apply rounded-full;
        @apply -translate-x-4 translate-y-4;
    }
</style>
