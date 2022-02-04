<template>
    <div v-on:click="controller.setActiveIndex(index)">
        <slot :active="this.controller.activeIndex == index"></slot>
    </div>
</template>

<script>

export default {
    inject: {
      controller: {
        from: 'tabsController'
      }
    },
    data() {
        return {
            index: null,
        }
    },
    created() {
        this.index = this.controller.registerTab()
    },
}
</script>
