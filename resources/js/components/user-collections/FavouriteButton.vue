<template>
    <a v-if="isDetail" v-on:click="store.toggleItem(id)" class="btn btn-cta btn-default btn-outline sans w-100">
        <slot name="icon-detail" :isFavourite="store.hasItem(id)">
            <i class="fa" :class="[store.hasItem(id) ? 'fa-star' : 'fa-star-o']"></i>
            {{ label }}
        </slot>
    </a>
    <a v-else v-on:click="store.toggleItem(id)" class="cursor-pointer" data-toggle="tooltip" data-placement="left" :title="label">
        <slot name="icon" :isFavourite="store.hasItem(id)">
            <i class="fa" :class="[store.hasItem(id) ? 'fa-star' : 'fa-star-o']"></i>
        </slot>
    </a>
</template>
<script>
    const store = require('./store')

    export default {
        props: ['labelAdd', 'labelRemove', 'id', 'isDetail'],
        data() {
            return {
                store
            }
        },
        computed: {
            label: function () {
              return this.store.hasItem(this.id) ? this.labelRemove : this.labelAdd
            }
        }
    }
</script>
