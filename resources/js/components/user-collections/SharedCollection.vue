<template>
    <div v-if="name">
        <div class="flex font-semibold underline">
            <h4 class="text-lg m-0 flex-grow bg-gray-400 mr-2 font-semibold">
                <a :href="shareUrl"><strong>{{ name }}</strong></a>
            </h4>
            <div class="hidden-xs">
                <a :href="editUrl" class="mr-3">upraviť</a>
                <copy-to-clipboard-link :value="shareUrl" :successText="copySuccessText">
                    kopírovať odkaz
                </copy-to-clipboard-link>
            </div>
        </div>
        <div class="text-gray-500 mt-3">
            <span class="hidden-xs">{{ createdAt }}</span>
            <span class="mx-1 hidden-xs"> · </span>
            {{ author }}
            <span v-if="author" class="mx-1"> · </span>
            {{ itemsCount }} {{ itemsCountWord }}
        </div>
        <div class="mt-2 underline font-semibold visible-xs-block">
            <a :href="editUrl" class="mr-3">upraviť</a>
            <copy-to-clipboard-link :value="shareUrl" :successText="copySuccessText">
                kopírovať odkaz
            </copy-to-clipboard-link>
        </div>
    </div>
</template>

<script>
import formatDate from 'date-fns/format'
import parseISODate from 'date-fns/parseISO'

export default {
    props: [
        'publicId',
        'updateToken',
        'apiUrlTemplate',
        'shareUrlTemplate',
        'editUrlTemplate',
        'copySuccessText'
    ],
    data() {
        return {
            name: null,
            author: null,
            createdAt: null,
            itemsCount: null,
        }
    },
    mounted() {
        fetch(this.apiUrl)
            .then(response => response.json())
            .then(response => {
                this.name = response.name;
                this.author = response.author;
                this.itemsCount = response.items_count;
                this.createdAt = formatDate(parseISODate(response.created_at), 'dd. MM. yyyy')
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
        itemsCountWord() {
            if (this.itemsCount === 1) return 'dielo'
            if (this.itemsCount < 5) return 'diela'
            return 'diel'
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
