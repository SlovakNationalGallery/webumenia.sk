<livewire-vue-adaptor v-slot="lw">
    <user-interaction-context v-slot="interaction">
        <bottom-modal
            :show="
                interaction.maxScrolledPercent >= {{ $openOnScrolledPercent }}
                && interaction.timeSpentSeconds >= 30
            "
            v-on:open="lw.call('onOpen')"
            v-on:close="lw.call('onDismissed')"
        >
            <div class="row py-5">
                <div class="visible-lg-block col-lg-1 col-lg-offset-2 text-right pl-0 pt-4">
                    <svg viewBox="0 0 101 110" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="3" width="94.7368" height="104" stroke="black" stroke-width="6"/>
                        <rect x="21.5" y="20" width="40" height="40" fill="black"/>
                        <rect x="47.8652" y="72.7769" width="20" height="20" transform="rotate(-20 47.8652 72.7769)" fill="black"/>
                    </svg>
                </div>
                <div class="col-lg-7 lg:pr-0 lg:pl-2">
                    <livewire:newsletter-signup-form variant="bottom-modal" />
                </div>
            </div>
        </bottom-modal>
    </user-interaction-context>
</livewire-vue-adaptor>
