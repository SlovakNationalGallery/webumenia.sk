@props(['openOnScrolledPercent'])

@unless(Cookie::get('newsletterSubscribedAt') || Cookie::get('newsletterSignupModalDismissedAt'))
    <newsletter-signup-form-bottom-modal-controller
        dismissal-url="{{ route('api.newsletter-subscriptions.dismiss') }}"
        :show-on-scrolled-percent="{{ $openOnScrolledPercent }}" v-slot="c">
        <bottom-modal :show="c.show" @open="c.onOpen" @close="c.onDismiss">
            <div class="row py-5">
                <div class="visible-lg-block col-lg-1 col-lg-offset-2 text-right pl-0 pt-4">
                    <svg viewBox="0 0 101 110" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="3" width="94.7368" height="104" stroke="black"
                            stroke-width="6" />
                        <rect x="21.5" y="20" width="40" height="40" fill="black" />
                        <rect x="47.8652" y="72.7769" width="20" height="20"
                            transform="rotate(-20 47.8652 72.7769)" fill="black" />
                    </svg>
                </div>
                <div class="col-lg-7 pr-lg-0 pl-lg-2">
                    <x-newsletter-signup-form tracking-variant="bottom-modal" />
                </div>
            </div>
        </bottom-modal>
    </newsletter-signup-form-bottom-modal-controller>
@endunless
