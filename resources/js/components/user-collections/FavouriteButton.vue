<template>
    <a
        v-if="isDetail"
        v-on:click="store.toggleItem(id)"
        class="btn btn-cta btn-default btn-outline sans tw-w-full"
    >
        <i class="fa" :class="[store.hasItem(id) ? 'fa-star' : 'fa-star-o']"></i>
        {{ label }}
    </a>
    <a
        v-else
        v-on:click="store.toggleItem(id)"
        class="cursor-pointer"
        data-toggle="tooltip"
        data-placement="left"
        :title="label"
    >
        <i class="fa" :class="[store.hasItem(id) ? 'fa-star' : 'fa-star-o']"></i>
    </a>
</template>
<script>
const store = require('./store')

export default {
    props: ['labelAdd', 'labelRemove', 'id', 'isDetail'],
    data() {
        return {
            store,
        }
    },
    computed: {
        label: function () {
            return this.store.hasItem(this.id) ? this.labelRemove : this.labelAdd
        },
    },
}
</script>
