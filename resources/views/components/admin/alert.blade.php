@props([
    'danger' => false,
    'warning' => false,
    'info' => false,
    'success' => false,
    'dismissable' => false,
])

<div
    {{ $attributes->class(['alert','alert-danger' => $danger,'alert-info' => $info,'alert-warning' => $warning,'alert-success' => $success]) }}>
    @if ($dismissable)
        <a href="#" class="close" data-dismiss="alert">&times;</a>
    @endif
    {{ $slot }}
</div>
