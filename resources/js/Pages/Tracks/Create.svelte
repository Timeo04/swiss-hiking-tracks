<script>
    // Layout importieren
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.svelte";
    // Funktionen für Netzwerk-Requests importieren
    import { router, useForm } from "@inertiajs/svelte";
    // Funktionen für GeoJSON-Dateien (und das Konvertieren von .gpx und .kml) importieren
    import {
        parseGeofile,
        filterGeoJson,
        getLineString,
    } from "@/utils/geofile";
    // Funktionen für die Berechnung der minimalen Distanz importieren
    import { getElevation, getMinDistance, hasElevation } from "@/utils/geojson/linestring";
    // UI-Komponenten aus Library flowbite-svelte importieren
    import { Button, FloatingLabelInput, Helper } from "flowbite-svelte";
    // Icon importieren
    import { ArrowDownOutline } from "flowbite-svelte-icons";

    // Authentifizierungs-Informationen laden
    export let auth;

    // Datei-Variable initialisieren
    let geofile = null;
    // Variable für Fehlermeldung initialisieren
    let geofileError = null;

    // Formular initialisieren
    let form = useForm({
        title: "",
        starting_location: null,
        destination_location: null,
        geojson: null,
    });

    // Funktion, die aufgerufen wird, wenn das Formular abgeschickt wird
    async function submit() {
        try {
            $form.processing = true;
            // Nur die erste Datei auswählen
            let geoJson = await parseGeofile(geofile[0]);
            // Falls die Datei nicht geparst werden kann, wird ein Fehler geworfen
            if (!geoJson) {
                throw new Error("Die Datei konnte nicht geladen werden.");
            }
            // GeoJSON-Objekt filtern nach LineString-Objekten
            geoJson = filterGeoJson(geoJson);
            // Nur LineString-Objekt auswählen
            let geoJsonLineString = getLineString(geoJson);

            if(hasElevation(geoJsonLineString)) {
                console.log("Elevation vorhanden");
            } else {
                console.log("Elevation nicht vorhanden");
            }

            geoJsonLineString = await getElevation(geoJsonLineString);

            // let minDistance = getMinDistance(geoJsonLineString);
            // console.log("min-Distance", minDistance);

            // Netzwerk-Request an den Server senden
            $form
                .transform((data) => ({
                    ...data,
                    // GeoJSON-Objekt in einen String umwandeln und anfügen
                    geojson: JSON.stringify(geoJsonLineString),
                }))
                .post(route("tracks.store"));
        } catch (error) {
            // Fehlermeldung in der Konsole anzeigen
            console.error(error);
            // Fehlermeldung anzeigen
            geofileError = error.message;
            $form.processing = false;
        }
    }

    // Funktion, die aufgerufen wird, wenn sich die Datei ändert
    function changeTitle(event) {
        if (
            // Titel-Feld ist leer und Datei wurde ausgewählt
            ($form.title == "" || $form.title == null) &&
            event.target.files[0] != null
        ) {
            // Dateiname auslesen
            let fileName = event.target.files[0].name;
            // Dateiendung entfernen
            fileName = fileName.substring(0, fileName.lastIndexOf("."));
            // Alle Unterstriche und Bindestriche durch Leerzeichen ersetzen
            fileName = fileName.replace(/(_|-)/g, " ");
            // Leerzeichen am Anfang und Ende entfernen
            fileName = fileName.trim();
            // Dateiname als Titel setzen
            $form.title = fileName;
        }
    }
</script>

<svelte:head>
    <title>Route erstellen</title>
</svelte:head>

<AuthenticatedLayout {auth}>
    <h1 class="py-10 text-2xl font-semibold text-center">Route erstellen</h1>

    <div
        class="flex items-center justify-between p-4 bg-white rounded-lg shadow-md"
    >
        <!-- Display form to create a new Track -->
        <form on:submit|preventDefault={submit} class="w-full">
            <div class="flex flex-col gap-4 mb-6">
                <div>
                    <input
                        class="file:bg-primary-700 file:hover:bg-primary-800 file:text-white file:border-none file:cursor-pointer file:font-medium
                        file:px-5 file:py-2.5 file:dark:bg-primary-600 file:dark:hover:bg-primary-700 file:mr-2
                        block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                        dark:focus:border-primary-500 focus:border-primary-600"
                        aria-describedby="file_input_help"
                        id="geofile"
                        required
                        type="file"
                        accept=".gpx, .geojson, .kml"
                        on:change={changeTitle}
                        bind:files={geofile}
                    />
                    {#if $form.errors.geojson}
                        <Helper color="red">{$form.errors.geojson}</Helper>
                    {:else if geofileError != null}
                        <Helper color="red">{geofileError}</Helper>
                    {:else}
                        <Helper
                            >Wählen Sie eine GPX-, KML- oder GeoJSON-Datei.</Helper
                        >
                    {/if}
                </div>
                <div>
                    <FloatingLabelInput
                        style="outlined"
                        id="title"
                        name="title"
                        type="text"
                        required
                        bind:value={$form.title}
                    >
                        Titel
                    </FloatingLabelInput>
                    {#if $form.errors.title}
                        <Helper color="red">{$form.errors.title}</Helper>
                    {/if}
                </div>
                <div>
                    <FloatingLabelInput
                        style="outlined"
                        id="starting_location"
                        name="starting_location"
                        type="text"
                        bind:value={$form.starting_location}
                    >
                        Startort
                    </FloatingLabelInput>
                    {#if $form.errors.starting_location}
                        <Helper color="red"
                            >{$form.errors.starting_location}</Helper
                        >
                    {/if}
                    <div class="mt-4 flex justify-center items-center">
                        <ArrowDownOutline tabindex="-1" />
                    </div>
                </div>
                <div>
                    <FloatingLabelInput
                        style="outlined"
                        id="destination_location"
                        name="destination_location"
                        type="text"
                        bind:value={$form.destination_location}
                    >
                        Zielort
                    </FloatingLabelInput>
                    {#if $form.errors.destination_location}
                        <Helper color="red"
                            >{$form.errors.destination_location}</Helper
                        >
                    {/if}
                </div>
                <Button
                    class="mt-2"
                    type="submit"
                    color="primary"
                    disabled={$form.processing}>Wanderung erstellen</Button
                >
                <!-- svelte-ignore missing-declaration -->
                <Button
                    outline
                    on:click={() => {
                        $form.cancel();
                        router.visit(route("tracks.index"));
                    }}>Abbrechen</Button
                >
            </div>
        </form>
    </div>
</AuthenticatedLayout>
