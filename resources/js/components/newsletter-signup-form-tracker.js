Livewire.on('trackNewsletterSignup', function (eventAction, eventLabel) {
    window.dataLayer.push({
        'event': 'NewsletterSignupFormEvent',
        'eventCategory': 'NewsletterSignupForm',
        'eventAction': eventAction,
        'eventLabel': eventLabel
    })
})