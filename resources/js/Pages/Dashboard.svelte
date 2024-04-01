<script>
    // Layout importieren
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.svelte";
    // Map-Komponente importieren
    import Map from "@/Components/Tracks/Map.svelte";

    // Übergabewerte initialisieren
    export let tracks = [];
    export let auth;
</script>

<svelte:head>
    <title>Karte</title>
</svelte:head>

<AuthenticatedLayout {auth}>
    <!-- Karte anzeigen -->
    <div class="fixed top-0 left-0 h-[calc(100vh-4rem)] w-screen">
        <Map
            {tracks}
            imageryBaseMap={true}
            cooperativeGestures={false}
            class="h-full w-full"
            popups
        />
    </div>
</AuthenticatedLayout>
