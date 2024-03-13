<script>
    import { Avatar } from "flowbite-svelte";
    export let name = "";
</script>

<Avatar>
    <!-- If Name has more than one word or has more than 2 Characters -->
    {#if name.split(" ").length > 1}
        <!-- Take Initials -->
        {name[0] + name.split(" ")[1][0]}
    {:else if name != "" && name.length > 1}
        <!-- Take First Two Characters -->
        {name[0] + name[1]}
    {/if}
</Avatar>
