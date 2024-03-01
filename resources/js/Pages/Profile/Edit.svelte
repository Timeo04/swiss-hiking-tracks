<script setup>
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.svelte";
    import DeleteUserForm from "./Partials/DeleteUserForm.svelte";
    import UpdatePasswordForm from "./Partials/UpdatePasswordForm.svelte";
    import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.svelte";
    import { Avatar, Button } from "flowbite-svelte";
    import {
        ArrowRightToBracketOutline,
        ArrowRightToBracketSolid,
    } from "flowbite-svelte-icons";
    import { router } from "@inertiajs/core";

    export let mustVerifyEmail;
    export let status;
    export let auth;
</script>

<svelte:head>
    <title>Profile</title>
</svelte:head>

<AuthenticatedLayout {auth}>
    <!-- <h2 slot="header" class="font-semibold text-xl text-gray-800 leading-tight">
        Profile
    </h2> -->
    <h1 class="py-10 text-2xl font-semibold text-center">Profil bearbeiten</h1>
    <div>
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
