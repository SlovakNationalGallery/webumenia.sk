<template>
    <div class="tw-flex tw-space-x-3 tw-overflow-x-auto">
        <button
            class="tw-min-w-max tw-py-1 tw-px-1.5 tw-bg-gray-300 tw-flex tw-items-center"
            v-for="option in selectedOptionsAsLabels"
            @click="removeSelection(option)"
        >
            <span
                v-if="option.filterName === 'color'"
                class="tw-text-xs tw-font-semibold tw-pr-1.5 tw-flex tw-items-center tw-uppercase"
                ><div
                    class="tw-h-4 tw-w-4 tw-inline-block tw-mr-1.5"
                    :style="{ 'background-color': `#${option.value}`, 'border-radius': '30px' }"
                ></div>
                #{{ option.value }}</span
            >
            <span
                v-else-if="option.filterName === 'yearRange'"
                class="tw-text-xs tw-font-semibold tw-pr-1.5"
                >{{ option.value.from }} - {{ option.value.to }}</span
            >
            <span v-else class="tw-text-xs tw-font-semibold tw-pr-1.5">{{ option.value }}</span>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="tw-w-4 tw-h-4 tw-fill-current"
                viewBox="0 0 256 256"
            >
                <path
                    d="M202.83,197.17a4,4,0,0,1-5.66,5.66L128,133.66,58.83,202.83a4,4,0,0,1-5.66-5.66L122.34,128,53.17,58.83a4,4,0,0,1,5.66-5.66L128,122.34l69.17-69.17a4,4,0,1,1,5.66,5.66L133.66,128Z"
                ></path>
            </svg>
        </button>
        <button
            v-if="isAnythingSelected"
            class="tw-min-w-max tw-flex tw-items-center tw-bg-white tw-leading-none tw-px-1.5 tw-font-normal tw-py-1.5 tw-text-sm tw-border tw-border-gray-300 hover:tw-border-gray-800"
            @click="clearAllSelections()"
        >
            <svg
                class="tw-mr-1.5"
                width="12"
                height="11"
                viewBox="0 0 12 11"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <g clip-path="url(#clip0_4429_21389)">
                    <path
                        d="M0.879761 1.83337V4.58337H3.62976"
                        stroke="black"
                        stroke-width="1.2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                    <path
                        d="M2.03018 6.875C2.32736 7.7185 2.89062 8.44258 3.6351 8.93813C4.37958 9.43368 5.26495 9.67385 6.1578 9.62246C7.05065 9.57107 7.90261 9.23091 8.58532 8.65322C9.26803 8.07553 9.7445 7.29161 9.94295 6.41958C10.1414 5.54755 10.0511 4.63464 9.68555 3.81842C9.32004 3.00219 8.69917 2.32686 7.91648 1.89418C7.13378 1.4615 6.23167 1.29491 5.34607 1.41951C4.46047 1.54411 3.63935 1.95315 3.00643 2.585L0.879761 4.58333"
                        stroke="black"
                        stroke-width="1.2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </g>
                <defs>
                    <clipPath id="clip0_4429_21389">
                        <rect width="11" height="11" fill="white" transform="translate(0.421387)" />
                    </clipPath>
                </defs>
            </svg>
            <span>resetovať</span>
        </button>
    </div>
</template>

<script>
export default {
    props: {
        selectedOptionsAsLabels: Array,
        selectedValues: Object,
        clearAllSelections: Function,
        removeSelection: Function,
        isAnythingSelected: Boolean
    },
}
</script>
