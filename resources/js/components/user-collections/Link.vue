<template>
    <a :href="href">
        <slot></slot>
    </a>
</template>

<script>
export default {
    mixins: [require('./link-mixin')],
}
</script>
