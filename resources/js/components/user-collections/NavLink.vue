<template>
    <a :href="href">
        <i class="fa fa-star"></i>
        <transition enter-active-class="animated bounceIn" leave-active-class="animated bounceOut">
            <div v-if="count > 0" class="tw-inline-block">
                <transition enter-active-class="animated heartBeat" mode="out-in">
                    <span :key="count" class="badge badge-sup badge-info">{{ count }}</span>
                </transition>
            </div>
        </transition>
    </a>
</template>

<script>
    export default {
        mixins: [require('./link-mixin')],
        computed: {
            count() {
                return this.store.getItems().length
            }
        }
    }
</script>
