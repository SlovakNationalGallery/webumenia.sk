<template>
    <li v-bind:class="{ active }">
        <a :href="href">{{ label }}</a>
    </li>
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
            }
        }
    }
</script>
