<template>
    <form ref="form" :action="action" method="POST" v-on:submit.prevent="onSubmit">
        <slot :editing="editing" :setEditing="setEditing"></slot>
    </form>
</template>

<script>
const store = require('./store')

export default {
    props: {
        action: String,
        addToUserCollections: Boolean,
        creating: Boolean,
        publicId: String,
        updateToken: String,
    },
    data() {
        return {
            editing: false,
        }
    },
    mounted() {
        if (!this.publicId || !this.updateToken) return

        if (this.addToUserCollections && !store.hasSharedCollection(this.publicId)) {
            store.addSharedCollection(this.publicId, this.updateToken)
        }
    },
    methods: {
        setEditing(editing) {
            this.editing = editing
        },
        onSubmit() {
            const form = this.$refs.form

            // Do a full reload submission on create
            if (this.creating) {
                form.submit()
                return
            }

            fetch(this.action, {
                method: 'POST',
                body: new FormData(form),
            })

            this.editing = false
        }
    }
}
</script>
