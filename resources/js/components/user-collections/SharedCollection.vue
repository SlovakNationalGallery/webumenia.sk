<template>
    <div>
    {{ name }} ({{ itemsCount }})
    <a :href="shareUrl">View</a>
    <a :href="editUrl">Edit</a>
    </div>
</template>

<script>
export default {
    props: ['publicId', 'updateToken', 'apiUrlTemplate', 'shareUrlTemplate', 'editUrlTemplate'],
    data() {
        return {
            name: null,
            itemsCount: null,
        }
    },
    mounted() {
        fetch(this.apiUrl)
            .then(response => response.json())
            .then(response => {
                this.name = response.name;
                this.itemsCount = response.items_count;
            });
    },
    computed: {
        apiUrl() {
            return this.getUrlFromTemplate(this.apiUrlTemplate);
        },
        shareUrl() {
            return this.getUrlFromTemplate(this.shareUrlTemplate);
        },
        editUrl() {
            return this.getUrlFromTemplate(this.editUrlTemplate);
        },
    },
    methods: {
        getUrlFromTemplate(template) {
            return template
                .replace('__PUBLIC_ID__', this.publicId)
                .replace('__UPDATE_TOKEN__', this.updateToken);
        }
    }
}

</script>
