<div class="bg-blue">
    <div class="row text-black p-4 p-md-5">
        <div class="col-md-6">
            @if ($success)
                <h3 class="text-5xl mt-4 pt-1 font-semibold animated fadeInDown visible-xs-block">Hotovo!</h3>
                <h3 class="text-center mt-5 text-6xl font-semibold animated tada fast hidden-xs">Hotovo!</h3>
            @elseif ($errors->any())
                <h3 class="text-4xl font-semibold">Hups! Vyskytla sa neočakávaná chyba.</h3>
            @else
                <h3 class="mt-2 text-3xl font-semibold visible-xs-block">Chceš umenie do mailovej schránky?</h3>
                <h3 class="text-4xl font-semibold hidden-xs">Chceš umenie do mailovej schránky?</h3>
            @endif
            </h3>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-11 col-md-push-1">
                    <p class="text-lg pb-md-1">Nové kolekcie, články, projekty a&nbsp;tipy na&nbsp;výtvarné diela <strong>2x mesačne</strong>.</p>

                    <form wire:submit.prevent="subscribe" class="input-group mt-4">
                        <input
                            type="email"
                            class="form-control bg-light no-border placeholder-black {{ $success ? 'text-gray-500' : '' }}"
                            placeholder="@"
                            required
                            @if ($success) readonly @endif
                            wire:model.defer="email"
                        >
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-black font-light" @if ($success) disabled @endif>
                                <span wire:loading.remove>
                                    @if ($success)
                                        <i class="fa fa-check text-success"></i> Je to tam
                                    @else
                                        Pridajte ma
                                    @endif
                                </span>
                                <span wire:loading>Moment...</span>
                            </button>
                        </span>
                    </form>
                    <p class="text-sm mt-3 mb-md-0 text-dark">Odoslaním súhlasím so&nbsp;spracovaním <a href="https://www.sng.sk/sk/o-galerii/dokumenty/gdpr">osobných údajov</a>.</p class="text-sm">
                </div>
            </div>
        </div>
    </div>
</div>