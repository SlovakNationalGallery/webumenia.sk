<template>
    <a data-toggle="tooltip" data-placement="left" :title="label" v-on:click="store.toggleItem(id)">
        <i class="fa" :class="[store.hasItem(id) ? 'fa-star' : 'fa-star-o']"></i>
    </a>
</template>

<script>
    export default {
        props: ['label', 'id'],
        data() {
            return {
                store: this.$root.$data.userCollectionsStore
            }
        },
    }
</script>
