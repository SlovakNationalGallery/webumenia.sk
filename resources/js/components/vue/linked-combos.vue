<template>
  <div class="linked-inputs">
    <div class="linked-combos">
      <button type="button" @click="selected.push({})">+</button>
      <div v-for="(item, index) of selected" class="row">
        <v-select
          :options="options"
          @search="onSearch"
          taggable
          label="value"
          v-model="item.item"
          :reduce="getKey"
        >
          <div slot="no-options">¯\_(ツ)_/¯</div>
        </v-select>
        <v-select
          :options="relationTypes"
          label="value"
          v-model="item.relation"
          :reduce="getKey"
        >
          <div slot="no-options">¯\_(ツ)_/¯</div>
        </v-select>
        <button type="button" @click="remove(index)">-</button>
      </div>
    </div>
    <input type="hidden" :name="name" :value="JSON.stringify(resValue)" />
  </div>
</template>

<script>
import VueSelect from 'vue-select'

export default {
  name: "linked-combos",
  components: {
    "v-select": VueSelect
  },
  props: [
    "value",
    "name",
    "defaultOptions",
    "optionsSearchUrl",
    "relationTypes",
    "optionsSearchTransform"
  ],
  data: function() {
    return {
      selected: Object.keys(this.value).map(key => ({
        item: key,
        relation: this.value[key] || ""
      })),
      options: this.defaultOptions
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
    getKey($object) {
      return $object.key;
    },
    onSearch(search, loading) {
      loading(true);
      this.debouncedSearch(loading, search, this);
    },
    search(loading, search, vm) {
      fetch(this.optionsSearchUrl.replace("%QUERY", search))
        .then(res => res.json())
        .then(json => {
          const res = json.map(this.optionsSearchTransform);
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
