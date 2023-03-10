<template>
    <div class="tw-min-w-max">
        <button
            @click="toggleIsExtendedOpen"
            class="tw-border tw-w-full tw-justify-center hover:tw-border-gray-800 tw-border-gray-300 tw-text-lg tw-font-bold tw-py-3.5 tw-px-4 tw-flex tw-items-center"
        >
            <div class="tw-pr-4 tw-flex tw-items-center">
                <svg
                    v-if="isExtendedOpen"
                    xmlns="http://www.w3.org/2000/svg"
                    class="tw-w-6 tw-h-6 tw-fill-current"
                    viewBox="0 0 256 256"
                >
                    <path
                        d="M228,128a12,12,0,0,1-12,12H40a12,12,0,0,1,0-24H216A12,12,0,0,1,228,128Z"
                    ></path>
                </svg>
                <svg
                    v-else
                    xmlns="http://www.w3.org/2000/svg"
                    class="tw-w-6 tw-h-6 tw-fill-current"
                    viewBox="0 0 256 256"
                >
                    <path
                        d="M40,92H70.06a36,36,0,0,0,67.88,0H216a12,12,0,0,0,0-24H137.94a36,36,0,0,0-67.88,0H40a12,12,0,0,0,0,24Zm64-24A12,12,0,1,1,92,80,12,12,0,0,1,104,68Zm112,96H201.94a36,36,0,0,0-67.88,0H40a12,12,0,0,0,0,24h94.06a36,36,0,0,0,67.88,0H216a12,12,0,0,0,0-24Zm-48,24a12,12,0,1,1,12-12A12,12,0,0,1,168,188Z"
                    ></path>
                </svg>
            </div>
            <span class="tw-font-semibold">{{
                isExtendedOpen ? 'skryť ďalšie filtre' : 'všetky filtre'
            }}</span>
        </button>
    </div>
</template>

<script>
export default {
    props: { isExtendedOpen: Boolean, toggleIsExtendedOpen: Function },
}
</script>
