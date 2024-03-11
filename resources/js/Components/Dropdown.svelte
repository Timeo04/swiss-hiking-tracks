<script>
    // Lifecycle-Methoden importieren
    import { onDestroy, onMount } from "svelte";
    // Transitions importieren
    import { sineIn, sineOut } from "svelte/easing";
    import { fade } from "svelte/transition";

    export let align = "right";
    export let width = "48";
    export let contentClasses = "py-1 bg-white";
    export let open = false;

    // Funktion, um das Dropdown bei Drücken von Escape zu schliessen
    const closeOnEscape = (e) => {
        if (open && e.key === "Escape") {
            open = false;
        }
    };

    // Event-Listener für das Schliessen des Dropdowns hinzufügen
    onMount(() => document.addEventListener("keydown", closeOnEscape));
    // Event-Listener für das Schliessen des Dropdowns entfernen
    onDestroy(() => document.removeEventListener("keydown", closeOnEscape));

    const widthClass = {
        48: "w-48",
    }[width.toString()];

    let alignmentClasses = "";
    if (align === "left") {
        alignmentClasses = "ltr:origin-top-left rtl:origin-top-right start-0";
    } else if (align === "right") {
        alignmentClasses = "ltr:origin-top-right rtl:origin-top-left end-0";
    } else {
        alignmentClasses = "origin-top";
    }
</script>

<div class="relative">
    <div
        role="button"
        aria-haspopup="true"
        aria-expanded={open}
        tabindex="0"
        on:click={() => (open = !open)}
        on:keydown={(e) => {
            if (e.key === "Enter" || e.key === " ") {
                open = !open;
            }
        }}
    >
        <slot name="trigger" />
    </div>

    <!-- Full Screen Dropdown Overlay -->
    {#if open}
        <div
            role="presentation"
            class="fixed inset-0 z-40"
            on:click={() => (open = false)}
        ></div>

        <div
            role="menu"
            tabindex="0"
            in:fade={{ duration: 200, easing: sineOut }}
            out:fade={{ duration: 75, easing: sineIn }}
            class="absolute z-50 mt-2 rounded-md shadow-lg {widthClass} {alignmentClasses}"
            on:click={() => (open = false)}
            on:keydown={(e) => {
                if (e.key === "Enter" || e.key === " ") {
                    open = false;
                }
            }}
        >
            <div
                class="rounded-md ring-1 ring-black ring-opacity-5 {contentClasses}"
            >
                <slot name="content" />
            </div>
        </div>
    {/if}
</div>
