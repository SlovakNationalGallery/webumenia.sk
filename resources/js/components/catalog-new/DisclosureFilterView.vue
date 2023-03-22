<template>
    <div class="bg-white tw-h-screen">
        <div class="tw-mx-4 tw-my-6 tw-flex tw-justify-between tw-items-end">
            <slot name="header"></slot>
            <div class="tw-flex">
                <slot name="reset-button"></slot>
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
