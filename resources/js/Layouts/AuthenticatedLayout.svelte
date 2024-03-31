<script>
    // Router für Buttons importieren
    import { router } from "@inertiajs/svelte";

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
            class="{className} flex flex-col gap-2 p-2 max-w-4xl xl:max-w-5xl w-full overflow-hidden"
        >
            <slot />
        </main>
        <!-- Add Spacer to prevent content behind navbar -->
        <div class="h-10"></div>

        <!-- Bottom Navigation -->
        <div
            class="fixed bottom-0 left-0 z-50 w-full h-16 bg-white border-t border-gray-200 dark:bg-gray-700 dark:border-gray-600"
        >
            <div class="grid h-full max-w-lg grid-cols-3 mx-auto font-medium">
                <!-- svelte-ignore missing-declaration -->
                <button
                    on:click={() => router.visit(route("dashboard"))}
                    type="button"
                    class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group"
                >
                    <!-- svelte-ignore missing-declaration -->
                    <HomeSolid
                        tabindex="-1"
                        size="xl"
                        class="{route().current('dashboard')
                            ? 'text-primary-600 dark:text-primary-500'
                            : 'text-gray-500 dark:text-gray-400'} w-6 h-6 mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-500"
                    />
                    <!-- svelte-ignore missing-declaration -->
                    <span
                        class="text-sm {route().current('dashboard')
                            ? 'text-primary-600 dark:text-primary-500'
                            : 'text-gray-500 dark:text-gray-400'} group-hover:text-primary-600 dark:group-hover:text-primary-500"
                        >Karte</span
                    >
                </button>
                <!-- svelte-ignore missing-declaration -->
                <button
                    on:click={() => router.visit(route("tracks.index"))}
                    type="button"
                    class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group"
                >
                    <!-- svelte-ignore missing-declaration -->
                    <MapLocationOutline
                        tabindex="-1"
                        size="xl"
                        class=" {route().current('tracks.*')
                            ? 'text-primary-600 dark:text-primary-500'
                            : 'text-gray-500 dark:text-gray-400'} w-6 h-6 mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-500"
                    />
                    <!-- svelte-ignore missing-declaration -->
                    <span
                        class="text-sm {route().current('tracks.*')
                            ? 'text-primary-600 dark:text-primary-500'
                            : 'text-gray-500 dark:text-gray-400'} group-hover:text-primary-600 dark:group-hover:text-primary-500"
                        >Routen</span
                    >
                </button>
                <!-- svelte-ignore missing-declaration -->
                <button
                    on:click={() => router.visit(route("settings"))}
                    type="button"
                    class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group"
                >
                    <!-- svelte-ignore missing-declaration -->
                    <UserSettingsOutline
                        tabindex="-1"
                        size="xl"
                        class=" {route().current('settings')
                            ? 'text-primary-600 dark:text-primary-500'
                            : 'text-gray-500 dark:text-gray-400'} w-6 h-6 mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-500"
                    />
                    <!-- svelte-ignore missing-declaration -->
                    <span
                        class="text-sm {route().current('settings')
                            ? 'text-primary-600 dark:text-primary-500'
                            : 'text-gray-500 dark:text-gray-400'}  group-hover:text-primary-600 dark:group-hover:text-primary-500"
                        >Einstellungen</span
                    >
                </button>
            </div>
        </div>
    </div>
</div>
