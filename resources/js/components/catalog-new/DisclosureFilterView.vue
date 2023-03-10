<template>
    <div class="bg-white tw-h-screen">
        <div class="tw-mx-4 tw-my-6 tw-flex tw-justify-between tw-items-end">
            <slot name="header"></slot>
            <div class="tw-flex">
                <button
                    class="tw-min-w-max tw-flex tw-items-center tw-bg-white tw-px-1.5 tw-py-1 tw-mr-3 tw-font-semibold tw-text-sm tw-border tw-border-gray-300 hover:tw-border-gray-800"
                    @click="$emit('reset')"
                >
                    <svg
                        class="tw-mr-1.5 tw-w-4 tw-h-4 tw-fill-current"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 256 256"
                    >
                        <path
                            d="M228,128a100,100,0,0,1-98.66,100H128a99.39,99.39,0,0,1-68.62-27.29,12,12,0,0,1,16.48-17.45,76,76,0,1,0-1.57-109c-.13.13-.25.25-.39.37L54.89,92H72a12,12,0,0,1,0,24H24a12,12,0,0,1-12-12V56a12,12,0,0,1,24,0V76.72L57.48,57.06A100,100,0,0,1,228,128Z"
                        ></path>
                    </svg>
                    <span>zrušiť výber</span>
                </button>
                <button @click="$emit('close')">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="tw-h-6 tw-w-6 tw-fill-current"
                        viewBox="0 0 256 256"
                    >
                        <path
                            d="M208.49,191.51a12,12,0,0,1-17,17L128,145,64.49,208.49a12,12,0,0,1-17-17L111,128,47.51,64.49a12,12,0,0,1,17-17L128,111l63.51-63.52a12,12,0,0,1,17,17L145,128Z"
                        ></path>
                    </svg>
                </button>
            </div>
        </div>
        <slot name="body"></slot>
    </div>
</template>
