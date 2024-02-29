<script>
    import ApplicationLogo from "@/Components/ApplicationLogo.svelte";
    import Dropdown from "@/Components/Dropdown.svelte";
    import DropdownLink from "@/Components/DropdownLink.svelte";
    import NavLink from "@/Components/NavLink.svelte";
    import ResponsiveNavLink from "@/Components/ResponsiveNavLink.svelte";
    import { inertia } from "@inertiajs/svelte";
    import { router } from "@inertiajs/svelte";

    import { BottomNav, BottomNavItem } from "flowbite-svelte";
    import {
        HomeSolid,
        WalletSolid,
        UserSettingsOutline,
        UserCircleSolid,
        MapLocationOutline,
    } from "flowbite-svelte-icons";

    // export let page;
    export let auth;
    // let auth = page.props.auth;

    $: console.log(route().current("dashboard"));

    let showingNavigationDropdown = false;
</script>

<div>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-100">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a use:inertia href={route("dashboard")}>
                                <ApplicationLogo
                                    className="block h-9 w-auto fill-current text-gray-800"
                                />
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div
                            class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                        >
                            <NavLink
                                href={route("dashboard")}
                                active={route().current("dashboard")}
                            >
                                Dashboard
                            </NavLink>
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <!-- Settings Dropdown -->
                        <div class="ms-3 relative">
                            <Dropdown align="right" width="48">
                                <div slot="trigger">
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                        >
                                            {auth.user.name}

                                            <svg
                                                class="ms-2 -me-0.5 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </div>

                                <div slot="content">
                                    <DropdownLink href={route("profile.edit")}>
                                        Profile
                                    </DropdownLink>
                                    <DropdownLink
                                        href={route("logout")}
                                        method="post"
                                        as="button"
                                    >
                                        Log Out
                                    </DropdownLink>
                                </div>
                            </Dropdown>
                        </div>
                    </div>

                    <!-- Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button
                            on:click={() =>
                                (showingNavigationDropdown =
                                    !showingNavigationDropdown)}
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                        >
                            <svg
                                class="h-6 w-6"
                                stroke="currentColor"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    class:hidden={showingNavigationDropdown}
                                    class:inline-flex={!showingNavigationDropdown}
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    class:hidden={!showingNavigationDropdown}
                                    class:inline-flex={showingNavigationDropdown}
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div
                class:block={showingNavigationDropdown}
                hidden={!showingNavigationDropdown}
                class="sm:hidden"
            >
                <div class="pt-2 pb-3 space-y-1">
                    <ResponsiveNavLink
                        href={route("dashboard")}
                        active={route().current("dashboard")}
                    >
                        Dashboard
                    </ResponsiveNavLink>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">
                            {auth.user.name}
                        </div>
                        <div class="font-medium text-sm text-gray-500">
                            {auth.user.email}
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink href={route("profile.edit")}>
                            Profile
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            href={route("logout")}
                            method="post"
                            as="button"
                        >
                            Log Out
                        </ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header class="bg-white shadow" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Page Content -->
        <main>
            <slot />
        </main>
        <div class="h-10"></div>

        <div
            class="fixed bottom-0 left-0 z-50 w-full h-16 bg-white border-t border-gray-200 dark:bg-gray-700 dark:border-gray-600"
        >
            <div class="grid h-full max-w-lg grid-cols-3 mx-auto font-medium">
                <button
                    on:click={() => router.visit(route("dashboard"))}
                    type="button"
                    class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group"
                >
                    <HomeSolid
                        class="{route().current('dashboard')
                            ? 'text-primary-600 dark:text-primary-500'
                            : 'text-gray-500 dark:text-gray-400'} w-5 h-5 mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-500"
                    />
                    <span
                        class="text-sm {route().current('dashboard')
                            ? 'text-primary-600 dark:text-primary-500'
                            : 'text-gray-500 dark:text-gray-400'} group-hover:text-primary-600 dark:group-hover:text-primary-500"
                        >Home</span
                    >
                </button>
                <button
                    on:click={() => router.visit(route("tracks.index"))}
                    type="button"
                    class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group"
                >
                    <MapLocationOutline
                        size="xl"
                        class=" {route().current('tracks.*')
                            ? 'text-primary-600 dark:text-primary-500'
                            : 'text-gray-500 dark:text-gray-400'} w-6 h-6 mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-500"
                    />
                    <!-- <svg
                        class="w-5 h-5 mb-2 text-gray-500 dark:text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-500"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M11.074 4 8.442.408A.95.95 0 0 0 7.014.254L2.926 4h8.148ZM9 13v-1a4 4 0 0 1 4-4h6V6a1 1 0 0 0-1-1H1a1 1 0 0 0-1 1v13a1 1 0 0 0 1 1h17a1 1 0 0 0 1-1v-2h-6a4 4 0 0 1-4-4Z"
                        />
                        <path
                            d="M19 10h-6a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1Zm-4.5 3.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2ZM12.62 4h2.78L12.539.41a1.086 1.086 0 1 0-1.7 1.352L12.62 4Z"
                        />
                    </svg> -->
                    <span
                        class="text-sm {route().current('tracks.*')
                            ? 'text-primary-600 dark:text-primary-500'
                            : 'text-gray-500 dark:text-gray-400'} group-hover:text-primary-600 dark:group-hover:text-primary-500"
                        >Routen</span
                    >
                </button>
                <button
                    on:click={() => router.visit(route("profile.edit"))}
                    type="button"
                    class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group"
                >
                    <UserSettingsOutline
                        size="xl"
                        class=" {route().current('profile.edit')
                            ? 'text-primary-600 dark:text-primary-500'
                            : 'text-gray-500 dark:text-gray-400'} w-6 h-6 mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-500"
                    />
                    <!-- <svg
                        class="w-5 h-5 mb-2 text-gray-500 dark:text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-500"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 20 20"
                    >
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 12.25V1m0 11.25a2.25 2.25 0 0 0 0 4.5m0-4.5a2.25 2.25 0 0 1 0 4.5M4 19v-2.25m6-13.5V1m0 2.25a2.25 2.25 0 0 0 0 4.5m0-4.5a2.25 2.25 0 0 1 0 4.5M10 19V7.75m6 4.5V1m0 11.25a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5ZM16 19v-2"
                        />
                    </svg> -->
                    <span
                        class="text-sm {route().current('profile.edit')
                            ? 'text-primary-600 dark:text-primary-500'
                            : 'text-gray-500 dark:text-gray-400'}  group-hover:text-primary-600 dark:group-hover:text-primary-500"
                        >Einstellungen</span
                    >
                </button>
                <!-- <button
                    type="button"
                    class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group"
                >
                    <svg
                        class="w-5 h-5 mb-2 text-gray-500 dark:text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-500"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"
                        />
                    </svg>
                    <span
                        class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-500"
                        >Profile</span
                    >
                </button> -->
            </div>
        </div>

        <!-- <BottomNav position="fixed" classInner="grid-cols-4">
            <BottomNavItem
            activeClass="text-red-500"
            class="text-red-500"
                btnName="Dashboard"
                on:click={() => router.visit(route("dashboard"))}
                active={route().current("dashboard")}
            >
                <HomeSolid
                    class="w-5 h-5 mb-1 text-gray-500 dark:text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-500"
                />
            </BottomNavItem>
            <BottomNavItem btnName="Wallet">
                <WalletSolid
                    class="w-5 h-5 mb-1 text-gray-500 dark:text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-500"
                />
            </BottomNavItem>
            <BottomNavItem btnName="Settings">
                <UserSettingsOutline
                    class="w-5 h-5 mb-1 text-gray-500 dark:text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-500"
                />
            </BottomNavItem>
            <BottomNavItem btnName="Profile">
                <UserCircleSolid
                    class="w-5 h-5 mb-1 text-gray-500 dark:text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-500"
                />
            </BottomNavItem>
        </BottomNav> -->
    </div>
</div>
