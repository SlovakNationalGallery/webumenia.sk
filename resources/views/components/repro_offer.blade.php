<div class="wu-repro-offer top-space bottom-space">
    <h4 class="text-uppercase">{!! $title !!}</h4>
    <img class="w-100 bottom-space" src="{{$img_url}}">
    <div>{!! $description !!}</div>
    <table class="table">
        <tbody>
            @foreach ($pricing_options as $pricing_option)
                <tr>
                    <td>{!!$pricing_option[0]!!}</td>
                    <td>{!!$pricing_option[1]!!}</td>
                    <td><strong>{!!$pricing_option[2]!!} {{trans('reprodukcie.print_offer_per-piece')}}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>