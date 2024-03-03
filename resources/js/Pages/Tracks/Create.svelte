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

    let form = useForm({
        title: "",
        starting_location: null,
        destination_location: null,
        gpx_file: null,
    });

    function submit() {
        $form
            .transform((data) => ({
                ...data,
                gpx_file: data.gpx_file[0],
            }))
            .post(route("tracks.store"));
    }
</script>

<svelte:head>
    <title>Route erstellen</title>
</svelte:head>

<AuthenticatedLayout {auth}>
    <!-- <div slot="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Route erstellen
        </h2>
    </div> -->
    <!-- <h1 class="text-2xl font-semibold">Route erstellen</h1> -->
    <h1 class="py-10 text-2xl font-semibold text-center">Wanderung erstellen</h1>
    

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
                <div>
                    <!-- <Label for="gpx_file" class="mb-2">GPX-Datei</Label> -->
                    <input
                        class="file:bg-primary-700 file:hover:bg-primary-800 file:text-white file:border-none file:cursor-pointer file:font-medium
                        file:px-5 file:py-2.5 file:dark:bg-primary-600 file:dark:hover:bg-primary-700 file:mr-2
                        block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                        dark:focus:border-primary-500 focus:border-primary-600"
                        aria-describedby="file_input_help"
                        id="gpx_file"
                        required
                        type="file"
                        accept=".gpx"
                        bind:files={$form.gpx_file}
                    />
                    <!-- <Fileupload
                        placeholder="Datei auswählen"
                        id="gpx_file"
                        required
                        bind:files={$form.gpx_file}
                    /> -->
                    {#if $form.errors.gpx_file}
                        <Helper color="red">{$form.errors.gpx_file}</Helper>
                    {:else}
                        <Helper>Wählen Sie eine GPX-Datei.</Helper>
                    {/if}
                </div>
                <Button
                    class="mt-2"
                    type="submit"
                    color="primary"
                    disabled={$form.processing}>Wanderung erstellen</Button
                >
                <Button
                    outline
                    on:click={() => {
                        $form.cancel();
                        router.visit(route("tracks.index"));
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
