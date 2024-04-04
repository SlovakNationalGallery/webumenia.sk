<template>
    <a :href="href">
        <slot></slot>
    </a>
</template>

<script>
import linkMixin from './link-mixin'
export default {
    mixins: [linkMixin],
}
</script>
