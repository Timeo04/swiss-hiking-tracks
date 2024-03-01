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
    <h2 slot="header" class="font-semibold text-xl text-gray-800 leading-tight">
        Profile
    </h2>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div
                class="p-4 sm:p-8 bg-white shadow sm:rounded-lg flex gap-2 justify-start items-center"
            >
                <div class="shrink-0">
                    <Avatar
                        >{auth.user.name[0] +
                            auth.user.name.split(" ")[1][0]}</Avatar
                    >
                </div>
                <div class="grow truncate">
                    <p class="truncate"><strong>{auth.user.name}</strong></p>
                    <p class="truncate">{auth.user.email}</p>
                </div>
                <div>
                    <Button
                        color="primary"
                        on:click={() => router.post(route("logout"))}
                    >
                        <ArrowRightToBracketOutline />
                    </Button>
                </div>
            </div>
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
