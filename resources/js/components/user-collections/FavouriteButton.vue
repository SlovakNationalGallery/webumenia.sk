<template>
    <a v-on:click="store.toggleItem(id)" :class="isDetail ? 'btn btn-cta btn-default btn-outline sans w-100' : ''" data-toggle="tooltip" data-placement="left" :title="label">
        <i class="fa" :class="[store.hasItem(id) ? 'fa-star' : 'fa-star-o']"></i>
        <span v-if="isDetail">{{ label }}</span>
    </a>
</template>
<script>
    export default {
        props: ['label', 'id', 'isDetail'],
        data() {
            return {
                store: this.$root.$data.userCollectionsStore
            }
        },
    }
</script>
