<script>
    // Layout importieren
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.svelte";
    // Komponenten importieren
    import DeleteUserForm from "./Partials/DeleteUserForm.svelte";
    import UpdatePasswordForm from "./Partials/UpdatePasswordForm.svelte";
    import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.svelte";
    // UI-Komponenten importieren
    import { Button } from "flowbite-svelte";
    // Funktionen für Netzwerk-Requests importieren
    import { router } from "@inertiajs/core";

    export let mustVerifyEmail;
    export let status;
    export let auth;
</script>

<svelte:head>
    <title>Profil</title>
</svelte:head>

<AuthenticatedLayout {auth}>
    <h1 class="py-10 text-2xl font-semibold text-center">Profil bearbeiten</h1>
    <div>
        <!-- Go to Settings page -->
        <!-- svelte-ignore missing-declaration -->
        <Button
            outline
            color="primary"
            class="w-full"
            on:click={() => router.visit(route("settings"))}
        >
            zurück
        </Button>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <UpdateProfileInformationForm
                    {mustVerifyEmail}
                    {status}
                    {auth}
                    className="max-w-xl"
                />
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <UpdatePasswordForm className="max-w-xl" />
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <DeleteUserForm className="max-w-xl" />
            </div>
        </div>
    </div>
</AuthenticatedLayout>
