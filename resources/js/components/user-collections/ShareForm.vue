<template>
    <form :action="action" method="POST">
        <slot :values="values" :editing="editing" :setEditing="setEditing"></slot>
    </form>
</template>

<script>
export default {
    props: {
        action: String,
        initialValueName: String,
        initialValueAuthor: String,
        initialValueDescription: String,
    },
    data() {
        return {
            editing: false,
            values: {
                name: this.initialValueName,
                author: this.initialValueAuthor,
                description: this.initialValueDescription,
            },
        }
    },
    methods: {
        setEditing(editing) {
            this.editing = editing
            console.log(this.editing)
        },
        save() {
            console.log('save')
        },
    }
}
</script>
