<template>
    <div>
        <span class="tw-font-semibold"
            >Zobrazujem <span class="tw-font-bold tw-underline-offset-8">12000</span> diel, zoradené
            podľa
            <select
                name="sort"
                id="sort"
                :value="controller.sort"
                @change="controller.handleSort($event)"
            >
                <option value="updated_at">podľa poslednej zmeny</option>
                <option value="created_at">dátumu pridania</option>
                <option value="title">názvu</option>
                <option value="author">autora</option>
                <option value="newest">dotovania - od najnovšieho</option>
                <option value="oldest">dotovania - od najstaršieho</option>
                <option value="view_count">počtu videní</option>
                <option value="random">náhodného poradia</option>
            </select>
            . Alebo skús aj
            <a class="tw-font-bold tw-underline tw-decoration-2 tw-underline-offset-4"
                >náhodný výber</a
            ></span
        >
    </div>
</template>

<script>
export default {
    inject: {
        controller: {
            from: 'filterController',
        },
    },
}
</script>
