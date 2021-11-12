<div class="row text-black">
    <div class="col-md-6">
        @if ($success)
            <h3 class="text-5xl mt-4 pt-1 font-semibold animated fadeInDown visible-xs-block">{{ __('general.newsletter_sign_up.success') }}</h3>
            <h3 class="text-center mt-5 text-6xl font-semibold animated tada fast hidden-xs">{{ __('general.newsletter_sign_up.success') }}</h3>
        @elseif ($errors->any())
            <h3 class="text-4xl font-semibold animated headShake">{{ __('general.newsletter_sign_up.error') }}</h3>
        @else
            <h3 class="mt-2 text-3xl font-semibold visible-xs-block">{{ __('general.newsletter_sign_up.title') }}</h3>
            <h3 class="text-4xl font-semibold hidden-xs">{{ __('general.newsletter_sign_up.title') }}</h3>
        @endif
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-11 col-md-push-1">
                <div class="text-lg pb-md-1">{!! __('general.newsletter_sign_up.subtitle') !!}</div>

                <form wire:submit.prevent="subscribe" class="input-group mt-4">
                    <input
                        type="email"
                        class="form-control bg-light no-border placeholder-black {{ $success ? 'text-gray-500' : '' }}"
                        placeholder="@"
                        required
                        @if ($success) readonly @endif
                        wire:model.defer="email"
                    >
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-black font-light" @if ($success) disabled @endif>
                            <span wire:loading.remove>
                                @if ($success)
                                    <i class="fa fa-check text-success"></i> {{ __('general.newsletter_sign_up.button.success') }}
                                @else
                                    {{ __('general.newsletter_sign_up.button.initial') }}
                                @endif
                            </span>
                            <span wire:loading>{{ __('general.newsletter_sign_up.button.loading') }}</span>
                        </button>
                    </span>
                </form>
                <div class="text-sm mt-3 mb-md-0 text-dark">
                    {!! __('general.newsletter_sign_up.privacy_policy.blurb') !!}
                    <a href="https://www.sng.sk/sk/o-galerii/dokumenty/gdpr" target="_blank" class="underline">{{ __('general.newsletter_sign_up.privacy_policy.link') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>