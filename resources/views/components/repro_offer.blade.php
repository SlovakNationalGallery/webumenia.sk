<div class="wu-repro-offer">
    <h4 class="text-uppercase">{!! $title !!}</h4>
    <img class="w-100" src="{{$img_url}}">
    <div>{!! $description !!}</div>
    <table>
        <tbody>
            @foreach ($pricing_options as $pricing_option)
                <tr>
                    <td>{!!$pricing_option[0]!!}</strong></td>
                    <td>{!!$pricing_option[1]!!}</td>
                    <td><strong>{!!$pricing_option[2]!!}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>