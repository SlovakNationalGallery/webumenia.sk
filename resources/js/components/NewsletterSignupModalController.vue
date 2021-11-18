<template>
    <div>
        <slot
            :show="show"
            :trackClosed="trackClosed"
        ></slot>
    </div>
</template>

<script>
import add from 'date-fns/add'


export default {
    props: {
        interactionThresholdMet: {
            type: Boolean,
            default: false,
        },
    },
    mounted() {
        // Triggered from NewsletterSignupForm
        window.addEventListener('newsletterSignupModal:subscribed', this.trackSubscribed);
    },
    beforeDestroy() {
        window.removeEventListener('newsletterSignupModal:subscribed', this.trackSubscribed);
    },
    methods: {
        trackClosed() {
            console.log('TODO Closed')
            this._setCookie('newsletterSignupModalDismissedAt', new Date().toISOString(), { days: 30 })
        },
        trackSubscribed() {
            console.log('TODO Subscribed!')
            this._setCookie('newsletterSignupSuccessAt', new Date().toISOString(), { years: 10 })
        },
        _setCookie(name, value, expiresIn = {}) {
            const expires = add(new Date(), expiresIn).toISOString();
            document.cookie = `${name}=${value};expires=${expires}`
        },
        _getCookie(name) {
            const cookie = document.cookie
                .split('; ')
                .map(cookie => cookie.split('='))
                .find(cookie => cookie[0] === name)

            return cookie ? cookie[1] : undefined
        }
    },
    computed: {
        show() {
            return this.interactionThresholdMet && !this._getCookie('newsletterSignupModalDismissedAt')
        }
    }
}
</script>