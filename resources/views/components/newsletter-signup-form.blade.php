@props(['trackingVariant' => 'inline'])

<newsletter-signup-form-controller url="{{ route('api.newsletter-subscriptions.store') }}"
    tracking-variant="{{ $trackingVariant }}" v-slot="c">
    <div class="row text-black">
        <div class="col-md-6">
            <template v-if="c.success">
                <h3 class="text-5xl mt-4 pt-1 font-semibold animated fadeInDown visible-xs-block">
                    {{ __('general.newsletter_sign_up.success') }}</h3>
                <h3 class="text-center mt-5 text-6xl font-semibold animated tada fast hidden-xs">
                    {{ __('general.newsletter_sign_up.success') }}</h3>
            </template>
            <template v-else-if="c.error">
                <h3 class="text-4xl font-semibold animated headShake">
                    {{ __('general.newsletter_sign_up.error') }}</h3>
            </template>
            <template v-else>
                <h3 class="mt-2 text-3xl font-semibold visible-xs-block">
                    {{ __('general.newsletter_sign_up.title') }}</h3>
                <h3 class="text-4xl font-semibold hidden-xs">
                    {{ __('general.newsletter_sign_up.title') }}</h3>
            </template>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-11 col-md-push-1">
                    <div class="text-lg pb-md-1">{!! __('general.newsletter_sign_up.subtitle') !!}</div>

                    <form @submit.prevent="c.submit" class="input-group mt-4">
                        <input name="email" type="email"
                            class="form-control bg-light no-border placeholder-black"
                            :class="{'text-gray-500': c.success}" placeholder="@" required
                            :readonly="c.success">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-black font-light"
                                :disabled="c.success">
                                <template v-if="c.loading">
                                    {{ __('general.newsletter_sign_up.button.loading') }}
                                </template>
                                <template v-else-if="c.success">
                                    <i class="fa fa-check text-success"></i>
                                    {{ __('general.newsletter_sign_up.button.success') }}
                                </template>
                                <template v-else>
                                    {{ __('general.newsletter_sign_up.button.initial') }}
                                </template>
                                </template>
                            </button>
                        </span>
                    </form>
                    <div class="text-sm mt-3 mb-md-0 text-dark">
                        {!! __('general.newsletter_sign_up.privacy_policy.blurb') !!}
                        <a href="https://sng.sk/sk/sng-bratislava/stranka/gdpr" target="_blank"
                            class="underline">{{ __('general.newsletter_sign_up.privacy_policy.link') }}</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
</newsletter-signup-form-controller>
