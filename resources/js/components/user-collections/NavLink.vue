<template>
    <transition
        enter-active-class="animated bounceIn"
        leave-active-class="animated fadeOut"
    >
        <li v-if="count > 0 || active" v-bind:class="{ active }">
            <a :href="href">{{ label }}
                <transition enter-active-class="animated heartBeat" mode="out-in">
                    <span :key="count" class="badge badge-notify">{{ count }}</span>
                </transition>
            </a>
        </li>
    </transition>
</template>

<script>
    export default {
        props: ['active', 'baseHref', 'label'],
        data() {
            return {
                store: this.$root.$data.userCollectionsStore
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
