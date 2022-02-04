<template>
    <div>
        <slot></slot>
    </div>
</template>

<script>
export default {
    data() {
        return {
            activeIndex: 0,
            tabCount: 0,
            tabPanelCount: 0,
        }
    },
    methods: {
        setActiveIndex(index) {
            this.activeIndex = index
        },
        registerTab() {
            return this.tabCount++
        },
        registerTabPanel() {
            return this.tabPanelCount++
        },
    },
    provide() {
        return {
            tabsController: this,
        }
    },
}
</script>
