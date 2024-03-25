<script>
    // Lifecycle-Methoden importieren
    import { onDestroy, onMount } from "svelte";
    // Event-Dispatcher importieren
    import { createEventDispatcher } from "svelte";
    // Transitions importieren
    import { fade, slide } from "svelte/transition";
    import { sineOut, sineIn } from "svelte/easing";

    // Event-Dispatcher erstellen
    const dispatch = createEventDispatcher();

    export let open = false;
    export let maxWidth = "2xl";
    export let closeable = true;

    $: {
        if (open) {
            document.body.style.overflow = "hidden";
        } else {
            document.body.style.overflow = null;
        }
    }

    // Funktion, um das Modal zu schliessen
    function close() {
        if (closeable) {
            open = false;
            dispatch("close");
        }
    }

    // Funktion, um das Modal bei Drücken der Escape-Taste zu schliessen
    const closeOnEscape = (e) => {
        if (e.key === "Escape" && open) {
            close();
        }
    };

    // Event-Listener für das Schliessen des Modals hinzufügen
    onMount(() => document.addEventListener("keydown", closeOnEscape));

    // Event-Listener für das Schliessen des Modals entfernen
    onDestroy(() => {
        document.removeEventListener("keydown", closeOnEscape);
        document.body.style.overflow = null;
    });

    function maxWidthClass() {
        return {
            sm: "sm:max-w-sm",
            md: "sm:max-w-md",
            lg: "sm:max-w-lg",
            xl: "sm:max-w-xl",
            "2xl": "sm:max-w-2xl",
        }[maxWidth];
    }
</script>

{#if open}
    <div
        class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-[60]"
        scroll-region
        out:fade={{ duration: 200 }}
    >
        <div
            role="presentation"
            in:fade={{ duration: 300, easing: sineOut }}
            out:fade={{ duration: 200, easing: sineIn }}
            class="fixed inset-0 transform transition-all"
            on:click={close}
        >
            <div class="absolute inset-0 bg-gray-500 opacity-75" />
        </div>

        <div
            class="fixed bottom-0 left-0 w-full sm:relative sm:mb-6 bg-white rounded-t-lg sm:rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto {maxWidthClass()}"
            in:slide={{ duration: 300, easing: sineOut, axis: "y" }}
        >
            <slot />
        </div>
    </div>
{/if}
