<template>
    <div>
        <slot
            :recordDismissed="recordDismissed"
            :wasPreviouslyDismissed="wasPreviouslyDismissed"
        ></slot>
    </div>
</template>

<script>
import differenceInDays from 'date-fns/differenceInDays'
import parseISO from 'date-fns/parseISO'
import differenceInSeconds from 'date-fns/differenceInSeconds'

const DismissExpirationInDays = 30

export default {
    methods: {
        recordDismissed(event) {
            localStorage.setItem('newsletterSignupModalDismissedAt', new Date().toISOString());
        },
    },
    computed: {
        wasPreviouslyDismissed() {
            const dismissedAt = localStorage.getItem('newsletterSignupModalDismissedAt')
            if (!dismissedAt) return false

            // Dismissal expires after 30 days
            return differenceInDays(Date.now(), parseISO(dismissedAt)) < DismissExpirationInDays;
        }
    }
}
</script>