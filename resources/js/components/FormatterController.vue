<script>
//WIP: Get importers to blade files with this Controller WDYT?
import { formatNum, formatAuthor } from "../../formatters" //Import formatters

export default {
    render() {
        return this.$scopedSlots.default({
            formatNum,
            formatAuthor
        })
    },
}
</script>
