<script>
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.svelte";
    import { router } from "@inertiajs/svelte";
    import { ArrowLeftOutline } from "flowbite-svelte-icons";

    export let track;
    export let auth;
</script>

<svelte:head>
    <title>{track.title}</title>
</svelte:head>

<AuthenticatedLayout {auth}>
    <button
        class="rounded-full w-12 h-12 fixed top-4 left-4 bg-primary-700 hover:bg-primary-500 flex justify-center items-center text-white shadow-md hover:shadow-lg transition ease-in duration-200 focus:outline-none"
        on:click={() => router.visit(route("tracks.index"))}
    >
        <ArrowLeftOutline size="xl" />
    </button>
    <h1 class="text-2xl text-center py-10 font-semibold">{track.title}</h1>
</AuthenticatedLayout>
