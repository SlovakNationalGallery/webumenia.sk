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
    const store = require('./store')

    export default {
        props: ['active', 'baseHref', 'label'],
        data() {
            return {
                store
            }
        },
        computed: {
            href() {
                const url = new URL(this.baseHref)
                const ids = this.store.getItems().map(({id}) => id)

                ids.forEach(id => url.searchParams.append('ids[]', id))
                return url.toString()
            },
            count() {
                return this.store.getItems().length
            }
        }
    }
</script>
