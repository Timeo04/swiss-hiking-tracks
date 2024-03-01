<script>
    import Avatar from "@/Components/Avatar.svelte";
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.svelte";
    import { router } from "@inertiajs/svelte";
    import { Button } from "flowbite-svelte";
    import { ArrowRightToBracketOutline } from "flowbite-svelte-icons";
    export let auth;
</script>

<AuthenticatedLayout {auth}>
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
            <Button
                color="primary"
                on:click={() => router.post(route("logout"))}
            >
                <ArrowRightToBracketOutline />
            </Button>
        </div>
    </div>
    <div>
        <Button
            outline
            color="primary"
            on:click={() => router.visit(route("profile.edit"))}
        >
            Profil bearbeiten
        </Button>
    </div>
</AuthenticatedLayout>
