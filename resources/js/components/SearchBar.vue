<template>
    <form method="GET" class="xukraine navbar-form tailwind-rules">
        <div class="tw-relative tw-flex tw-items-center">
            <i class="fa fa-search tw-absolute tw-right-3 tw-text-sm tw-pointer-events-none"></i>

            <input
                type="text"
                name="search"
                autocomplete="off"
                class="tw-peer tw-text-sm placeholder:tw-text-sky-300 tw-px-3 tw-py-1.5 tw-border tw-border-gray-600 tw-w-full focus:tw-outline-none tw-ring-0 tw-bg-white tw-z-10"
                :placeholder="$t('master.search_placeholder')"
            />

            <!-- Ukraine -->
            <div
                class="tw-flex tw-flex-col tw-opacity-0 tw-absolute tw-w-full tw-h-full peer-focus:tw-opacity-50 tw-transition-opacity"
            >
                <div
                    class="tw-shadow-[0px_0px_5px_2px_#0057b7] sm:tw-shadow-[0px_0px_10px_3px_#0057b7] tw-h-full"
                ></div>
                <div
                    class="tw-shadow-[0px_0px_5px_2px_#ffd700] sm:tw-shadow-[0px_0px_10px_3px_#ffd700] tw-h-full"
                ></div>
            </div>

            <!-- <input type="submit" value="submit" /> -->
        </div>
    </form>
</template>
