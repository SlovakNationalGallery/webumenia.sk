<template>
  <div class="linked-combos">
    <button type="button" @click="selected.push({})">+</button>
    <div v-for="(item, index) of selected" class="row">
      <v-select
        :options="options"
        @search="onSearch"
        taggable
        label="value"
        v-model="item.item"
        @input="emitValue()"
        :reduce="getKey"
      >
        <div slot="no-options">¯\_(ツ)_/¯</div>
      </v-select>
      <v-select
        :options="relation_types"
        label="value"
        v-model="item.relation"
        @input="emitValue()"
        :reduce="getKey"
      >
        <div slot="no-options">¯\_(ツ)_/¯</div>
      </v-select>
      <button type="button" @click="remove(index)">-</button>
    </div>
  </div>
</template>
<script>
export default {
  name: "linked-combos",
  props: [
    "value",
    "item_options",
    "relation_types",
    "item_search_url",
    "item_transform"
  ],
  data: function() {
    return {
      selected: Object.keys(this.value).map(key => ({
        item: key,
        relation: this.value[key] || ""
      })),
      options: this.item_options
    };
  },
  computed: {
    resValue: function() {
      const res = {};
      this.selected
        ? this.selected.forEach(i => (res[i.item] = i.relation || ""))
        : {};
      return res;
    }
  },
  created: function() {
    this.debouncedSearch = debounce(this.search, 300);
  },
  methods: {
    emitValue: function() {
      this.$emit("input", this.resValue);
    },
    getKey($object) {
      return $object.key;
    },
    onSearch(search, loading) {
      loading(true);
      this.debouncedSearch(loading, search, this);
    },
    search(loading, search, vm) {
      fetch(`${this.item_search_url}`.replace("%QUERY", search))
        .then(res => res.json())
        .then(json => {
          const res = json.map(this.item_transform);
          const values = JSON.parse(JSON.stringify(vm.options));
          const merged = [...res, ...values];
          vm.options = merged.filter(function(item, pos) {
            return merged.findIndex(i => item.key === i.key) == pos;
          });
          loading(false);
        })

        .catch(e => {
          loading(false);
        });
    },
    remove(index){ 
      this.selected.splice(index, 1); 
      this.emitValue();
    }
  }
};
</script>

<style lang="scss">
.linked-combos {
  width: 100%;
  .row {
    display: flex;
    justify-content: stretch;
    align-content: stretch;

    > div {
      flex: 1 1 47%;
      margin-right: 0.5em;
    }
    button {
      flex: 0 0 2ex;

      margin: auto;
    }

    .vs__actions {
      min-width: 3em;
    }
  }
}

.vs__dropdown-toggle {
  white-space: nowrap;
  height: 2.2em;
  margin: 0.3em 0;
}
</style>