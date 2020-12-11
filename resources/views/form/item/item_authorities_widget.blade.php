<div class="linked-inputs" id="{{ $id }}_vue">
  <linked-combos v-model="items" :item_options="item_options" :relation_types="relation_types"
                 v-on:input="onInput($event)" />
</div>
<input type="hidden" id="{{ $id }}" name="item[{{ $name }}]" />
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="{{ asset('/js/vue/vue-linked-combos.min.js') }}"></script>
{!! Html::style('css/vue/vue-linked-combos.css') !!}


<script defer>
  new Vue({
  el: '#{{ $id }}_vue',
  data: function () {
    return {
      items: {!! $items  ?: '{}' !!},
      item_options: {!! $authorities_choices ?: '[]' !!},
      relation_types: {!! $roles_choices ?: '[]' !!}
    };
  },
  mounted: function() {
    document.getElementById('{{ $id }}').value = JSON.stringify(this.items);
  },
  methods:{
    onInput: function(value) {
      document.getElementById('{{ $id }}').value = JSON.stringify(value);
    }
  }
})
</script>