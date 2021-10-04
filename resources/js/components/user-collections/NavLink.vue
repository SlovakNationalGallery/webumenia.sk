<template>
    <transition
        enter-active-class="animated bounceIn"
        leave-active-class="animated fadeOut"
    >
        <li v-if="count > 0 || active" v-bind:class="{ active }">
            <a :href="href" :title="label">
                <i class="fa fa-star"></i>
                <transition enter-active-class="animated heartBeat" mode="out-in">
                    <span :key="count" class="badge badge-sup badge-info">{{ count }}</span>
                </transition>
            </a>
        </li>
    </transition>
</template>

<script>
    export default {
        mixins: [require('./link-mixin')],
        props: ['active', 'label'],

        computed: {
            count() {
                return this.store.getItems().length
            }
        }
    }
</script>
