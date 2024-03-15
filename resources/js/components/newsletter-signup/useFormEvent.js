export function useFormEvent() {
    function track(action, label = undefined) {
        if (!window?.dataLayer) {
            return
        }

        window.dataLayer.push({
            event: 'NewsletterSignupFormEvent',
            eventCategory: 'NewsletterSignupForm',
            eventAction: action,
            eventLabel: label,
        })
    }

    return { track }
}
