<div class="linked-inputs" id="{{ $id }}_vue">
  <linked-combos v-model="items" :item_options="item_options" :relation_types="relation_types"
                 v-on:input="onInput($event)" :item_search_url="item_search_url" :item_transform="item_transform" />
</div>
<input type="hidden" id="{{ $id }}" name="item[{{ $name }}]" />

@section('script')
@parent

<script type="text/javascript">
  new Vue({
  el: '#{{ $id }}_vue',
  
  data: function () {
    return {
      items: {!! $items  ?: '{}' !!},
      item_options: {!! $authorities_choices ?: '[]' !!},
      item_search_url: "/autori/suggestions?search=%QUERY",
      relation_types: {!! $roles_choices ?: '[]' !!},
      item_transform: function(item){ return {key: item.id, value: item.name} },
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
});
</script>
@stop