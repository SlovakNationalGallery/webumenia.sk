<template>
    <form ref="form" :action="action" method="POST" v-on:submit.prevent="onSubmit">
        <slot :editing="editing" :setEditing="setEditing"></slot>
    </form>
</template>

<script>
export default {
    props: {
        action: String,
    },
    data() {
        return {
            editing: false,
        }
    },
    methods: {
        setEditing(editing) {
            this.editing = editing
        },
        onSubmit() {
            const form = this.$refs.form;

            fetch(this.action, {
                method: 'POST',
                body: new FormData(form),
            })

            this.editing = false;
        }
    }
}
</script>
