<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <button v-on:click="store.addItem('SVK:SNG.P_196')">Add SVK:SNG.P_196</button>
                <button v-on:click="store.removeItem('SVK:SNG.P_196')">Remove SVK:SNG.P_196</button>
                <button v-on:click="store.toggleItem('SVK:SNG.K_45')">Toggle SVK:SNG.K_45 (added: {{ store.hasItem('SVK:SNG.K_45') ? 'yes' : 'no' }})</button>
                <div class="card-body">
                    Items in collection ({{ store.getItems().length }}):
                </div>
                <ul v-for="item in store.getItems()" :key="item.id">
                    <li>{{ item.id }}</li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                store: this.$root.$data.userCollectionsStore
            }
        },
    }
</script>
