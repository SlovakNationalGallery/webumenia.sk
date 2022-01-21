<user-collections-share-form
        action="{{ $action }}"
        public-id="{{ optional($collection)->public_id }}"
        update-token="{{ optional($collection)->update_token }}"
        :creating="@json($creating)"
        :add-to-user-collections="@json(session('user-collection-created') === true)"
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
                class="text-4xl text-center"
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
                class="mt-5 pb-2 text-xl"
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
                class="mt-5 pb-2 text-lg font-serif text-center"
                :disabled="@json($disabled)"
                spellcheck="false"
                v-on:focus="form.setEditing(true)"
            /></inline-input>
        </div>
    </div>
    @if (!$disabled)
        <div class="row mt-5" style="height:110px" v-cloak>
            <div class="col-sm-6 col-sm-offset-3 text-center">
                <transition
                    enter-active-class="animated fadeInDown faster"
                    leave-active-class="animated fadeOut faster"
                    mode="out-in"
                >
                    <button v-if="form.editing" type="submit" class="btn btn-dark font-light p-3 px-5 mt-5" key="save">
                        uložiť úpravy {{-- TODO i18n --}}
                    </button>
                    @if (!$creating)
                    <div v-else class="row bg-gray-300 py-5">
                        <h5 class="text-xl font-semibold mt-0 mb-4">
                            @if (session('user-collection-created'))Výborne! @endif
                            Zdieľaj svoj výber s ostatnými:
                        </h5>

                        <div class="col-md-10 col-md-offset-1 mt-3">
                            <copy-to-clipboard-group
                                value="{{ route('frontend.shared-user-collections.show', $collection) }}"
                            ></copy-to-clipboard-group>
                        </div>
                    </div>
                    @endif
                </transition>
            </div>
        </div>
    @endif
    <div class="row grid mt-5 pt-5" style="max-width: 800px; margin: auto">
        <div id="column-sizer" class="col-xs-12 col-sm-6"></div>
        @foreach ($items as $index => $item)
            <input type="hidden" name="items[][id]" value="{{ $item->id }}" />
            @include('components.artwork_grid_item', [
                'item' => $item,
                'isotope_item_selector_class' => 'item',
                'class_names' => 'grid-item col-xs-12 ' . (function () use ($index, $item) {
                    // First image should be full-width, if possible
                    if ($index === 0 && $item->image_ratio < 1) return 'col-sm-6';

                    // Latter images can go into multiple columns
                    if ($item->image_ratio < 1.2) return 'col-sm-6';
                })()
            ])
        @endforeach
    </div>
</user-collections-share-form>

@section('javascript')
<script type="text/javascript">
    $('.grid').masonry({
        itemSelector: '.grid-item',
        columnWidth: '#column-sizer',
        percentPosition: true
    })
</script>
@endsection
