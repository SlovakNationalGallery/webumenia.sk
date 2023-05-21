<template>
    <div @click.stop v-on-clickaway="onClickaway">
        <slot></slot>
    </div>
</template>
<script>
import { directive as onClickaway } from 'vue-clickaway'

export default {
    directives: {
        onClickaway: onClickaway,
    },
    props: {
        onClickaway: Function,
    },
}
</script>
