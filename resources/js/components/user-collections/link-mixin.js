const store = require('./store')

module.exports = {
    props: ['baseHref'],
    data() {
        return {
            store
        }
    },
    computed: {
        href() {
            const url = new URL(this.baseHref)
            const ids = this.store.getItems().map(({id}) => id)

            ids.forEach(id => url.searchParams.append('ids[]', id))
            return url.toString()
        },
    },
}
