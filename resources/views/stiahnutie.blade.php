@extends('layouts.master')

@section('og')
@stop

@section('title')
{{ trans('download.title') }} |
@parent
@stop

@section('content')

<section class="order content-section top-section">
    <div class="order-body">
        <div class="container">
            <div class="row">
                @if (Session::has('message'))
                    <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>{!! Session::get('message') !!}</div>
                @endif
                <div class="col-md-8 col-md-offset-2 text-center">
                    {!! trans('download.content') !!}
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content-section" id="downloadForm">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">

            {!!
              Former::open()->route('objednavka.download')->class('form-bordered form-horizontal')->id('download')->rules(App\Download::$rules);
            !!}

            <div class="form-group required has-feedback"><label for="pids" class="control-label col-lg-2 col-sm-4">{{ trans('download.form_title') }}</label>
                <div class="col-lg-10 col-sm-8">
                        @if ($items->count() == 0)
                            <p class="text-center">{{ trans('download.none') }}</p>
                        @endif

                        @foreach ($items as $i=>$item)
                            <div class="media">
                                <a href="{!! $item->getUrl() !!}" class="pull-left">
                                    <img src="{!! $item->getImagePath() !!}" class="media-object" style="max-width: 80px;">
                                </a>
                                <div class="media-body">
                                    <a href="{!! $item->getUrl() !!}">
                                        <em>{!! implode(', ', $item->authors) !!}</em> <br> <strong>{!! $item->title !!}</strong> (<em>{!! $item->getDatingFormated() !!}</em>)
                                    </a><br>
                                    <p class="item"><a href="{!! URL::to('dielo/' . $item->id . '/odstranit') !!}" class="underline"><i class="fa fa-times"></i> {{ trans('download.remove') }}</a></span>
                                </div>
                            </div>
                        @endforeach

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
                            </div>
                        @endif

                </div>
            </div>

            @if ($items->count() > 0)

            <div class="form-group required">
                <label for="type" class="control-label col-lg-2 col-sm-4">{{ trans('download.choose_type') }}<sup>*</sup></label>
                <div class="col-lg-10 col-sm-8">
                    <div class="radio">
                        <input required="" id="private" value="private" type="radio" name="type">
                        <label for="private" class="">{{ utrans('download.private') }}</label>
                    </div>
                    <div class="radio">
                        <input required="" id="publication" value="publication" type="radio" name="type">
                        <label for="publication" class="">{{ utrans('download.publication') }}</label>
                    </div>
                    <div class="radio">
                        <input required="" id="commercial" value="commercial" type="radio" name="type">
                        <label for="commercial" class="">{{ utrans('download.commercial') }}</label>
                    </div>
                </div>
            </div>

            <div class="form-group required">
                {!! Former::hidden('pids')->value(implode(', ', Session::get('cart',array()))); !!}
            </div>

            {{-- commercial + publication --}}
            <div class="publication commercial form-hide">
                {!! Former::text('company')->label(trans('download.form_company'))->required(); !!}
                {!! Former::text('address')->label(trans('download.form_address'))->required(); !!}
                {!! Former::text('country')->label(trans('download.form_country'))->required(); !!}
                {!! Former::text('contact_person')->label(trans('download.form_contact_person'))->required(); !!}
                {!! Former::text('email')->label(trans('download.form_email'))->required(); !!}
                {!! Former::text('phone')->label(trans('download.form_phone')); !!}
            </div>

            {{-- commercial --}}
            <div class="commercial form-hide">
                {!! Former::text('purpose')->placeholder(trans('download.form_purpose_placeholder'))->label(trans('download.form_purpose'))->required(); !!}
            </div>

            {{-- publication --}}
            <div class="publication form-hide">
                {!! Former::text('publication_name')->label(trans('download.form_publication_name'))->required(); !!}
                {!! Former::text('publication_author')->label(trans('download.form_publication_author')); !!}
                {!! Former::text('publication_year')->label(trans('download.form_publication_year'))->required(); !!}
                {!! Former::text('publication_print_run')->label(trans('download.form_publication_print_run')); !!}

                <div class="form-group">
                    <div class="col-lg-2 col-sm-4">&nbsp;</div>
                    <div class="col-lg-10 col-sm-8">
                        <div class="checkbox">
                            <input id="confirm_sending_prints" name="confirm_sending_prints" type="checkbox" value="1" required>
                            <label for="confirm_sending_prints">
                              {!! trans('download.form_confirm_sending_prints') !!}<br>
                              <address style="display: inline">{!! trans('download.form_confirm_sending_prints_address') !!}</address>
                              <sup>*</sup>
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            <div class="publication commercial form-hide">
                {!! Former::textarea('note')->label(trans('download.form_note')); !!}

                <div class="form-group">
                    <div class="col-lg-2 col-sm-4">&nbsp;</div>
                    <div class="col-lg-10 col-sm-8">
                        <div class="checkbox">
                            <input id="terms_and_conditions" name="terms_and_conditions" type="checkbox" value="1" required>
                            <label for="terms_and_conditions">
                              {!! trans('download.form_terms_and_conditions') !!}
                              <sup>*</sup>
                            </label>
                        </div>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                    <button type="submit" class="btn btn-primary uppercase sans" data-label="{{ trans('dielo.item_download') }}" disabled="disabled">{{ trans('dielo.item_download') }}</button>
                </div>
            </div>

            @endif

            {!! Former::close() !!}

            </div>
        </div>
    </div>
</section>


@stop

@section('javascript')

@include('components.download_js')

@stop
