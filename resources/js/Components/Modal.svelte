<script>
    import { onDestroy, onMount } from "svelte";
    import { createEventDispatcher } from "svelte";
    import { fade } from "svelte/transition";
    import { sineOut, sineIn } from "svelte/easing";

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

    function close() {
        if (closeable) {
            open = false;
            dispatch("close");
        }
    }

    const closeOnEscape = (e) => {
        if (e.key === "Escape" && open) {
            close();
        }
    };

    onMount(() => document.addEventListener("keydown", closeOnEscape));

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

<!-- <Teleport to="body"> -->
<!-- <Transition leave-active-class="duration-200"> -->
{#if open}
    <div
        class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
        scroll-region
        out:fade={{ duration: 200 }}
    >
        <!-- <Transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                > -->
        <div role="presentation" in:fade={{duration: 300, easing: sineOut }} out:fade={{duration: 200, easing: sineIn }} class="fixed inset-0 transform transition-all" on:click={close}>
            <div class="absolute inset-0 bg-gray-500 opacity-75" />
        </div>
        <!-- </Transition> -->

        <!-- <Transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                > -->
        <div
            class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto {maxWidthClass()}"
        >
            <slot />
        </div>
        <!-- </Transition> -->
    </div>
{/if}
<!-- </Transition> -->
<!-- </Teleport> -->
