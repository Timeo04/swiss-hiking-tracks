<script>
    export let checked;
    export let name = null;
    export let id = null;
</script>

<input
    type="checkbox"
    bind:checked
    {name}
    {id}
    class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500"
/>
