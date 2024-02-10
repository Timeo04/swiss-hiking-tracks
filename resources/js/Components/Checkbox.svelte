<script>
    export let checked;
    export let name = null;
    export let id = null;
    // export let value = null;

    // const props = defineProps({
    //     checked: {
    //         type: [Array, Boolean],
    //         required: true,
    //     },
    //     value: {
    //         default: null,
    //     },
    // });

    // const proxyChecked = computed({
    //     get() {
    //         return props.checked;
    //     },

    //     set(val) {
    //         emit('update:checked', val);
    //     },
    // });
</script>

<input
    type="checkbox"
    bind:checked
    {name}
    {id}
    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
/>
