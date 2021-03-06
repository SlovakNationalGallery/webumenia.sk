<linked-combos 
  name="item[{{ $name }}]"
  :value="{{ $items }}" 
  :default-options="{{ $authorities_choices }}"
  options-search-url="/autori/suggestions?search=%QUERY"
  :options-search-transform="function(item){ return {key: item.id, value: item.name} }"
  :relation-types="{{ $roles_choices }}"
/>
