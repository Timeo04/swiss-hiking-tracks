<script>
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.svelte";
    import { router, useForm } from "@inertiajs/svelte";
    import {
        Button,
        Card,
        Fileupload,
        FloatingLabelInput,
        Helper,
        Input,
        Label,
    } from "flowbite-svelte";
    import { ArrowDownOutline } from "flowbite-svelte-icons";

    export let auth;
    export let track;
    console.log(track);

    let form = useForm({
        title: track.title,
        starting_location: track.starting_location,
        destination_location: track.destination_location,
    });

    function submit() {
        $form
            .put(route("tracks.update", {track}));
    }
</script>

<svelte:head>
    <title>Wanderung bearbeiten</title>
</svelte:head>

<AuthenticatedLayout {auth}>
    <!-- <div slot="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Route erstellen
        </h2>
    </div> -->
    <!-- <h1 class="text-2xl font-semibold">Route erstellen</h1> -->
    <h1 class="py-10 text-2xl font-semibold text-center">Wanderung bearbeiten</h1>
    

    <div
        class="flex items-center justify-between p-4 bg-white rounded-lg shadow-md"
    >
        <!-- <Card> -->
        <form on:submit|preventDefault={submit} class="w-full">
            <div class="flex flex-col gap-4 mb-6">
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
                    disabled={$form.processing}>Wanderung aktualisieren</Button
                >
                <Button
                    outline
                    on:click={() => {
                        $form.cancel();
                        router.visit(route("tracks.show", {track}));
                    }}>Abbrechen</Button
                >
            </div>
        </form>
        <!-- </Card> -->
    </div>
    <!-- <Button
        color="primary"
        on:click={() => router.visit(route("tracks.create"))}
        >Route erstellen</Button
    > -->
</AuthenticatedLayout>
