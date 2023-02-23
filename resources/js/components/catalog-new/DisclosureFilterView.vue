<template>
    <div class="bg-white tw-h-screen">
        <div class="tw-ml-4 tw-mr-6 tw-mb-5 tw-mt-6">
            <slot name="header"></slot>
            <button @click="$emit('close')">
                <i class="fa fa-close"></i>
            </button>
        </div>
        <slot name="body"></slot>
    </div>
</template>
