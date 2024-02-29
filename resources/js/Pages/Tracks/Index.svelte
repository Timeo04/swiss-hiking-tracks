<script>
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.svelte";
    import { router } from "@inertiajs/svelte";
    import { Button } from "flowbite-svelte";
    export let auth;
</script>

<svelte:head>
    <title>Routen</title>
</svelte:head>

<AuthenticatedLayout {auth}>
    <div slot="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Routen
        </h2>
    </div>
    <h1 class="text-2xl font-semibold">Routen</h1>
    <Button
        color="primary"
        on:click={() => router.visit(route("tracks.create"))}
        >Route erstellen</Button
    >
</AuthenticatedLayout>
