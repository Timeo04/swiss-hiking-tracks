<svelte:options accessors />

<script>
    import { onMount } from "svelte";
    export let type;
    export let value = null;
    export let autofocus = false;
    export let autocomplete = "off";
    export let required = false;
    export let className = "";
    export let id = null;
    export let placeholder = "";

    export let input;

    // const model = defineModel({
    //     type: String,
    //     required: true,
    // });

    // const input = ref(null);

    onMount(() => {
        if (autofocus) {
            input.focus();
        }
    });

    // defineExpose({ focus: () => input.value.focus() });
</script>

{#if type === "text"}
    <input
        {id}
        class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm {className}"
        type="text"
        bind:value
        bind:this={input}
        {autocomplete}
        {required}
        {placeholder}
    />
{:else if type === "number"}
    <input
        {id}
        class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm {className}"
        type="number"
        bind:value
        bind:this={input}
        {autocomplete}
        {required}
        {placeholder}
    />
{:else if type === "password"}
    <input
        {id}
        class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm {className}"
        type="password"
        bind:value
        bind:this={input}
        {autocomplete}
        {required}
        {placeholder}
    />
{:else if type === "email"}
    <input
        {id}
        class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm {className}"
        type="email"
        bind:value
        bind:this={input}
        {autocomplete}
        {required}
        {placeholder}
    />
{/if}
