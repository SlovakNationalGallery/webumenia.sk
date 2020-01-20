<!-- user input for new tags -->
<div>
    <button id="btn-add-tags" class="btn btn-default btn-outline sans">
        <i class="fa fa-plus"></i> pridať ďalšie tagy
    </button>
</div>

<div class="ui-adding-user-tags" style="display: none">
    <!-- open Form to update item -->
    {{ Form::open(['url' => 'dielo/'.$item->id.'/addTags']) }}
    <div class="form-group">
    {{ Form::select(
        'tags[]',
        \App\Item::existingTags()->pluck('name','name'),
        [],
        ['id' => 'tags', 'multiple' => 'multiple', 'class' => 'testclass', 'data-test' => 'test']
    ) }}
    </div>

    <!-- ReCapcha -->
    {{-- <div class="g-recaptcha" data-sitekey="6LeGGxwTAAAAABFMcXQSw187zEhZBh8R5Jw6HplV"></div> --}}

    <!-- form submit button to save user tags -->
    {{ Form::submit('Uložiť svoje tagy', array('class' => 'btn btn-default')) }}
    {{ Form::close() }}
</div>
