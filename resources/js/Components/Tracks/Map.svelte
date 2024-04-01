<script>
    // Map-Komponenten importieren
    import {
        Map,
        NavigationControl,
        GeolocateControl,
        ScaleControl,
        AttributionControl,
        LngLatBounds,
        Popup,
    } from "maplibre-gl";
    // Lifecycle-Funktionen importieren
    import { onDestroy, onMount } from "svelte";
    // Popup-Inhalt-Komponente importieren
    import MapPopupContent from "./MapPopupContent.svelte";

    // Übergabewerte initialisieren
    export let tracks = [];
    export let imageryBaseMap = false;
    export let interactiveMap = true;
    export let cooperativeGestures = true;
    export let popups = false;

    // Variablen deklarieren
    let mapContainer;
    let map;
    let startPos = [8.231726, 46.798473];
    let zoomLevel = 8;
    let bounds = null;

    // Koordinaten aus Tracks extrahieren, um Bounds zu erstellen
    let coordinates = [];
    tracks.forEach((track) => {
        coordinates.push(...track.geojson.coordinates);
    });

    // Bounds von Koordinaten erstellen
    function createBounds(coordinates) {
        return coordinates.reduce(
            (bounds, coord) => {
                return bounds.extend(coord);
            },
            new LngLatBounds(coordinates[0], coordinates[0]),
        );
    }
    // Bounds erstellen falls Koordinaten vorhanden
    if (coordinates.length > 0) bounds = createBounds(coordinates);

    function addPopup(map, track, lngLat) {
        new Popup()
            .setLngLat(lngLat)
            .setText("")
            .on("open", (e) => {
                let popupContentElement = e.target._content;

                let anchorElement = e.target._closeButton;

                // Svelte-Komponente für Popup-Inhalt erstellen
                let popupContentComponent = new MapPopupContent({
                    target: popupContentElement,
                    anchor: anchorElement,
                    props: {
                        track,
                    },
                });
            })
            .addTo(map) // Popup hinzufügen
            .addClassName("sht-popup");
    }

    // Map initialisieren
    const init = () => {
        // Map-Instanz erstellen
        const _map = new Map({
            container: mapContainer,
            style: imageryBaseMap // SwissTopo Imagery oder Standard-Karte verwenden
                ? "https://vectortiles.geo.admin.ch/styles/ch.swisstopo.leichte-basiskarte-imagery.vt/style.json"
                : `https://vectortiles.geo.admin.ch/styles/ch.swisstopo.basemap.vt/style.json`,
            center: startPos, // Startposition (falls keine Bounds vorhanden)
            zoom: zoomLevel, // Start-Zoom-Level (falls keine Bounds vorhanden)
            bounds: bounds, // Start-Bounds
            fitBoundsOptions: {
                padding: 100, // Abstand zum Rand bei bounds
            },
            pitchWithRotate: false, // Pitch mit Mausrad-Scrollen deaktivieren
            touchPitch: false, // Pitch mit zwei Fingern deaktivieren
            hash: false,
            cooperativeGestures: interactiveMap && cooperativeGestures, // Ggf. kooperative Gesten aktivieren
            locale: {
                // Lokalisierung für kooperative Gesten
                "CooperativeGesturesHandler.WindowsHelpText":
                    "Mit Strg + Maus bewegen und scrollen, um die Karte zu bewegen und zoomen.",
                "CooperativeGesturesHandler.MacHelpText":
                    "Mit ⌘ + Maus bewegen und scrollen, um die Karte zu bewegen und zoomen.",
                "CooperativeGesturesHandler.MobileHelpText":
                    "Mit zwei Fingern Karte bewegen und zoomen.",
            },
            attributionControl: false,
            interactive: interactiveMap, // Ggf. Interaktivität aktivieren
        });
        // Kontroll-Elemente hinzufügen
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

        // Tracks hinzufügen
        _map.on("load", async () => {
            tracks.forEach((track) => {
                // Für jeden Track
                let layerName = "track_" + track.id;
                _map.addSource(layerName, {
                    // GeoJSON-Quelle hinzufügen
                    type: "geojson",
                    data: track.geojson,
                });
                _map.addLayer({
                    // Linien-Layer hinzufügen
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
                    minzoom: 10, // Ab Zoom-Level 10 sichtbar
                });
                // Popup bei Klick hinzufügen falls aktiviert
                if (popups) {
                    _map.on("click", layerName, (e) => {
                        addPopup(_map, track, e.lngLat);
                    });

                    // Cursor-Änderung bei Hover
                    _map.on("mouseenter", layerName, () => {
                        _map.getCanvas().style.cursor = "pointer";
                    });
                    _map.on("mouseleave", layerName, () => {
                        _map.getCanvas().style.cursor = "";
                    });
                }
            });

            // Bild für Marker laden
            let image = await map.loadImage("/logo.png");
            _map.addImage("marker", image.data);

            tracks.forEach((track) => {
                // Für jeden Track
                let iconLayerName = "track_icon_" + track.id;
                _map.addSource(iconLayerName, {
                    // GeoJSON-Punkt-Quelle für Marker hinzufügen
                    type: "geojson",
                    data: {
                        type: "Point",
                        coordinates: track.geojson.coordinates[0],
                    },
                });
                _map.addLayer({
                    id: iconLayerName,
                    type: "symbol",
                    source: iconLayerName,
                    layout: {
                        "icon-image": "marker",
                        "icon-size": 0.18,
                        "icon-anchor": "bottom",
                    },
                    maxzoom: 10, // Bis Zoom-Level 10 sichtbar
                });

                // Cursor-Änderung bei Hover
                _map.on("mouseenter", iconLayerName, () => {
                    _map.getCanvas().style.cursor = "pointer";
                });
                _map.on("mouseleave", iconLayerName, () => {
                    _map.getCanvas().style.cursor = "";
                });

                // Bei Klick auf Track-Bounds zoomen
                _map.on("click", iconLayerName, (event) => {
                    _map.fitBounds(createBounds(track.geojson.coordinates), {
                        padding: 100,
                        duration: 2500,
                    });
                    if (popups) {
                        let coords =
                            track.geojson.coordinates[
                                Math.floor(track.geojson.coordinates.length / 2)
                            ];
                        setTimeout(() => {
                            addPopup(_map, track, {
                                lng: coords[0],
                                lat: coords[1],
                            });
                        }, 2500);
                    }
                });
            });

            // Kartengrösse anpassen
            _map.resize();
            if (bounds != null) {
                _map.fitBounds(bounds, {
                    padding: 100,
                });
            }
        });

        // Kartengrösse anpassen
        if (bounds != null) {
            _map.fitBounds(bounds, {
                padding: 100,
            });
        }

        // Map-Instanz speichern
        map = _map;
    };

    // Map initialisieren
    onMount(() => {
        init();
    });

    // Map entfernen
    onDestroy(() => {
        if (map != null) map.remove();
    });
</script>

<!-- Containter für Map -->
<div class="map w-full h-full" data-testid="map" bind:this={mapContainer} />

<style lang="postcss">
    /* Maplibre-GL-Styles importieren */
    @import "maplibre-gl/dist/maplibre-gl.css";

    /* Stil für Popups anpassen */
    :global(.maplibregl-popup) {
        max-width: none !important;
    }
    :global(.sht-popup) :global(.maplibregl-popup-content) {
        @apply font-sans;
        @apply rounded-lg;
        @apply p-3;
    }
    :global(.sht-popup) :global(.maplibregl-popup-close-button) {
        @apply w-5 h-5;
        @apply flex justify-center items-center;
        @apply text-2xl;
        @apply rounded-full;
        @apply -translate-x-4 translate-y-4;
    }
</style>
