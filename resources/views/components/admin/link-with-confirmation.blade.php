<form action="{{ $action }}" method="POST" data-confirm="{{ $message }}">
    @method($method)
    @csrf

    <button type="submit" class="{{ $class }}">
        {{ $slot }}
    </button>
</form>

@once
    @section('script')
        @parent

        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                document
                    .querySelectorAll("form[data-confirm]")
                    .forEach(form => {
                        form.addEventListener('submit', function(event) {
                            if (!confirm(event.target.dataset.confirm)) {
                                event.preventDefault()
                            }
                        })
                    })
            })
        </script>
    @endsection
@endonce