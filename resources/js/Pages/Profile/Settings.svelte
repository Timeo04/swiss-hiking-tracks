<script>
    // UI-Komponenten importieren
    import Avatar from "@/Components/Avatar.svelte";
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.svelte";
    import { Button } from "flowbite-svelte";
    // Icon importieren
    import { ArrowRightToBracketOutline } from "flowbite-svelte-icons";
    // Funktion für Netzwerk-Requests importieren
    import { router } from "@inertiajs/svelte";
    
    export let auth;
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
</AuthenticatedLayout>
