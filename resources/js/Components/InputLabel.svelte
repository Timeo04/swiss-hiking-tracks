<script>
    export let value = null;
    export let forValue = null;
    export let className = "";
</script>

<label for={forValue} class="block font-medium text-sm text-gray-700 {className}">
    {#if value != null}
        <span>{value}</span>
    {:else}
        <span><slot /></span>
    {/if}
</label>
