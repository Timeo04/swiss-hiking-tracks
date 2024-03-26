<script>
    // Layout importieren
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.svelte";
    import Map from "@/Components/Tracks/Map.svelte";

    export let tracks = [];
    export let auth;
</script>

<svelte:head>
    <title>Dashboard</title>
</svelte:head>

<AuthenticatedLayout {auth}>
    <!-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">Sie sind angemeldet!</div>
                
            </div>
        </div>
    </div> -->
    <div class="fixed top-0 left-0 h-[calc(100vh-4rem)] w-screen">
        <Map 
            {tracks} 
            imageryBaseMap={true}
            cooperativeGestures={false} 
            class="h-full w-full"
        />
    </div>
</AuthenticatedLayout>
