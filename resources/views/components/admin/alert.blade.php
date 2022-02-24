@props([
    'danger' => false,
    'info' => false,
])

<div {{ $attributes->class(['alert', 'alert-danger' => $danger, 'alert-info' => $info]) }}>
    <a href="#" class="close" data-dismiss="alert">&times;</a>
    {{ $slot }}
</div>
