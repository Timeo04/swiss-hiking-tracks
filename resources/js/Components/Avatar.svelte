<script>
    import { Avatar } from "flowbite-svelte";
    export let name = "";
</script>

<Avatar>
    {#if name.split(" ").length > 1}
        {name[0] + name.split(" ")[1][0]}
    {:else if name != ""}
        {name[0] + name[1]}
    {/if}
</Avatar>
