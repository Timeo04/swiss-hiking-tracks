<script>
    // Router für Buttons importieren
    import { router } from "@inertiajs/svelte";
    import Mountains from "@/assets/img/mountains.svg";

    export let className = "";

    // Icons für Bottom Navigation importieren
    import {
        HomeSolid,
        UserSettingsOutline,
        MapLocationOutline,
    } from "flowbite-svelte-icons";
</script>

<div>
    <div
        class="min-h-screen bg-gray-100 flex flex-col justify-start items-center"
    >
        <!-- Page Content -->
        <main
            class="{className} flex flex-col gap-2 p-2 max-w-4xl xl:max-w-5xl w-full snap-proximity snap-y"
        >
            <slot />
        </main>
        <!-- Add Spacer to prevent content behind navbar -->
        <!-- <div class="h-10"></div> -->

        <!-- Bottom Navigation -->
        <div
            class="fixed bottom-0 left-0 z-50 w-full h-16"
        >
            <img src={Mountains} role="presentation" alt="Berge" />
        </div>
    </div>
</div>
