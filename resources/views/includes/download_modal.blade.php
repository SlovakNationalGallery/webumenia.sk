<!-- Modal -->
<div tabindex="-1" class="modal fade" id="downloadForm" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {!!
              Former::open()->route('objednavka.download')->class('form-bordered form-horizontal')->id('order')->rules(App\Download::$rules);
            !!}

            <div class="modal-header text-center">
                <h2>{{ trans('dielo.item_download') }}</h2>
            </div>
            <div class="modal-body">

                <label for="download-type">{{ trans('download.choose_type') }}</label>
                <div class="btn-group btn-group-justified bottom-space" role="group" aria-label="chooseDownloadType" id="download-type">
                  <a href="#" role="button" class="btn btn-default" data-type="private">{{ utrans('download.private') }}</a>
                  <a href="#" role="button" class="btn btn-default" data-type="publication">{{ utrans('download.publication') }}</a>
                  <a href="#" role="button" class="btn btn-default" data-type="commercial">{{ utrans('download.commercial') }}</a>
                </div>

                {!! Former::hidden('item_id')->value($item->id); !!}
                {!! Former::hidden('type'); !!}

                {{-- comercial --}}
                <div class="publication commercial form-hide">
                {!! Former::text('company')->label(trans('download.form_company'))->required(); !!}
                {!! Former::text('address')->label(trans('download.form_address'))->required(); !!}
                {!! Former::text('country')->label(trans('download.form_country'))->required(); !!}
                {!! Former::text('contact_person')->label(trans('download.form_contact_person'))->required(); !!}
                {!! Former::text('email')->label(trans('download.form_email'))->required(); !!}
                {!! Former::text('phone')->label(trans('download.form_phone')); !!}
                </div>

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
                              {!! trans('download.form_confirm_sending_prints') !!}
                              <address class="dib">{!! trans('download.form_confirm_sending_prints_address') !!}</address>
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



            </div>
            <div class="modal-footer">
                <div class="text-center">
                    {!! Form::submit(trans('dielo.item_download'), [
                        'class'=>'btn btn-primary uppercase sans',
                        'disabled'=>'disabled',
                    ]) !!}
                    <button type="button" data-dismiss="modal" class="btn btn-default btn-outline uppercase sans">{{ trans('general.close') }}</button>
                </div>
            </div>
            {!!Former::close();!!}
        </div>
    </div>
</div>