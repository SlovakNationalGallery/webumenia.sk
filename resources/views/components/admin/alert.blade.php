@props([
    'danger' => false,
    'warning' => false,
    'info' => false,
    'dismissable' => false,
])

<div
    {{ $attributes->class(['alert', 'alert-danger' => $danger, 'alert-info' => $info, 'alert-warning' => $warning]) }}>
    @if ($dismissable)
        <a href="#" class="close" data-dismiss="alert">&times;</a>
    @endif
    {{ $slot }}
</div>
