<div>
    <h4 class="tw-mb-2.5 tw-text-lg">{{ $title }}</h4>
    <a href="{{ $imgFullUrl }}" class="popup">
        <img src="{{ $imgUrl }}" alt="{{ $title }}">
    </a>
    <div class="tw-mt-4 tw-text-sm prose-p:tw-mb-2.5">{!! $description !!}</div>
    <table class="tw-w-full tw-text-sm">
        <tbody class="tw-divide-y tw-divide-dashed">
            @foreach ($pricingOptions as $po)
                <tr>
                    <td class="tw-p-2">{!! $po[0] !!}</td>
                    <td class="tw-p-2">{!! $po[1] !!}</td>
                    <td class="tw-p-2">
                        <strong>{!! $po[2] !!}{{ trans('reprodukcie.print_offer_per-piece') }}
                        </strong>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
