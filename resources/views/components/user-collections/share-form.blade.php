<user-collections-share-form
        action="{{ $action }}"
        :creating="@json($creating)"
        v-slot="form"
    >
    @method($method)
    @csrf

    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 text-center">
            <inline-input 
                name="name" 
                placeholder="Nazvi svoj výber" {{-- TODO i18n --}}
                value="{{ old('name', $collection->name ?? null) }}"
                :class="['text-4xl text-center', { border: form.editing }]"
                :disabled="@json($disabled)"
                :focused="@json($creating)"
                spellcheck="false"
                required
                v-on:focus="form.setEditing(true)"
            /></inline-input>

            <br />

            <inline-input 
                name="author"
                placeholder="Tvoje meno" {{-- TODO i18n --}}
                value="{{ old('author', $collection->author ?? null) }}"
                :class="['mt-5 pb-2 text-xl text-center dark-grey', { border: form.editing }]"
                :disabled="@json($disabled)"
                spellcheck="false"
                v-on:focus="form.setEditing(true)"
            ></inline-input>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <inline-input 
                name="description" 
                placeholder="Stručne popíš svoj výber. Môžeš priblížiť jeho tému, príbeh, súvislosti medzi dielami alebo emócie, ktoré ťa viedli práve k tejto selekcii." {{-- TODO i18n --}}
                value="{{ old('description', $collection->description ?? null) }}"
                :class="['mt-5 pb-2 text-lg  dark font-serif', { border: form.editing }]"
                :disabled="@json($disabled)"
                spellcheck="false"
                v-on:focus="form.setEditing(true)"
            /></inline-input>
        </div>
    </div>
    @if (session('created-message'))
        <div class="row mt-5">
            <div class="col-sm-6 col-sm-offset-3">
                <div class="alert alert-info alert-dismissable mb-0">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('created-message') }}
                </div>
            </div>
        </div>
    @endif
    @if (!$disabled)
        <div class="row mt-5" style="height:34px" v-cloak>
            <div class="col-sm-6 col-sm-offset-3 text-center">
                <transition
                    enter-active-class="animated fadeInDown faster"
                    leave-active-class="animated fadeOutUp faster"
                    mode="out-in"
                >
                    <button v-if="form.editing" type="submit" class="btn btn-secondary" key="save">
                        Uložiť {{-- TODO i18n --}}
                    </button>
                    @if (!$creating)
                        <button v-if="!form.editing" type="button" class="btn btn-info" key="share" data-toggle="modal" data-target="#confirm">
                            Zdieľať výber <i class='ml-1 fa fa-share-alt'></i> {{-- TODO i18n --}}
                        </button>
                    @endif
                </transition> 
            </div>
        </div>
    @endif
    <div class="row grid mt-5 pt-5" style="max-width: 800px; margin: auto">
        <div id="column-sizer" class="col-sm-6"></div>
        @foreach ($items as $index => $item)
            <input type="hidden" name="items[][id]" value="{{ $item->id }}" />
            @include('components.artwork_grid_item', [
                'item' => $item,
                'isotope_item_selector_class' => 'item',
                'class_names' => 'grid-item ' . (function () use ($index, $item) {
                    if ($index === 0) {
                        return $item->image_ratio > 1 ? 'col-sm-12' : 'col-sm-6';    
                    }

                    return $item->image_ratio > 1.2 ? 'col-sm-12' : 'col-sm-6';
                })()
            ])
        @endforeach
    </div>
</user-collections-share-form>
</div>

@section('javascript')
<script type="text/javascript">
    $('.grid').masonry({
        itemSelector: '.grid-item',
        columnWidth: '#column-sizer',
        percentPosition: true,
    })
</script>
@endsection
