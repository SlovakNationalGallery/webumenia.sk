<template>
    <div v-cloak v-show="controller.activeIndex === index">
        <slot></slot>
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
        this.index = this.controller.registerTabPanel()
    },
}
</script>
