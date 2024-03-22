<script>
    // UI-Komponenten importieren
    import Avatar from "@/Components/Avatar.svelte";
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.svelte";
    import { Button, Range } from "flowbite-svelte";
    // Icon importieren
    import { ArrowRightToBracketOutline } from "flowbite-svelte-icons";
    // Funktion für Netzwerk-Requests importieren
    import { inertia, router, useForm } from "@inertiajs/svelte";

    export let auth;
    export let images;
    let hiking_speed = auth.user.hiking_speed ? auth.user.hiking_speed : 4.2;
    // console.log(images);

    let form = useForm({
        hiking_speed: hiking_speed,
    });

    function submit() {
        console.log($form.hiking_speed);
        $form.post(route("profile.setHikingSpeed"), {
            preserveScroll: true,
        });
    }
</script>

<AuthenticatedLayout {auth}>
    <h1 class="py-10 text-2xl font-semibold text-center">Einstellungen</h1>
    <div
        class="p-4 sm:p-8 bg-white shadow sm:rounded-lg flex gap-2 justify-start items-center"
    >
        <div class="shrink-0">
            <Avatar name={auth.user.name} />
        </div>
        <div class="grow truncate">
            <p class="truncate"><strong>{auth.user.name}</strong></p>
            <p class="truncate">{auth.user.email}</p>
        </div>
        <div>
            <!-- svelte-ignore missing-declaration -->
            <Button
                color="primary"
                on:click={() => router.post(route("logout"))}
            >
                <ArrowRightToBracketOutline />
            </Button>
        </div>
    </div>
    <div>
        <!-- Add Link to Edit.svelte-Page -->
        <!-- svelte-ignore missing-declaration -->
        <Button
            outline
            color="primary"
            class="w-full"
            on:click={() => router.visit(route("profile.edit"))}
        >
            Profil bearbeiten
        </Button>
    </div>
    <div
        class="p-4 sm:p-8 bg-white shadow sm:rounded-lg flex flex-col gap-2 justify-start items-center"
    >
        <h2 class="font-bold text-lg">Gehgeschwindigkeit</h2>
        <div class="w-full flex">
            <Range
                on:change={submit}
                min="3"
                max="6"
                step="0.1"
                bind:value={$form.hiking_speed}
            />
        </div>
        <p class="text-center">
            {$form.hiking_speed} km/h
        </p>
    </div>
    <h2 class="text-xl font-bold text-center">Hochgeladene Bilder</h2>
    <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 gap-2">
        {#each images as image}
            <!-- svelte-ignore missing-declaration -->
            <a href={route("tracks.show", image.track_id)} use:inertia>
                <img
                    class="rounded-lg object-cover w-full aspect-square"
                    src={image.url}
                    alt={image.title}
                />
            </a>
        {/each}
    </div>
    <div class="h-10"></div>
</AuthenticatedLayout>
