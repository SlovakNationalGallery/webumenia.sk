@if ($paginator->lastPage() > 1)
    @if ($paginator->currentPage() > 1)
        <link rel="previous" href="{!! $paginator->appends(@Request::except('page'))->url($paginator->currentPage() - 1) !!}"/>
    @endif
    @if ($paginator->currentPage() < $paginator->lastPage())
        <link rel="next" href="{!! $paginator->appends(@Request::except('page'))->url($paginator->currentPage() + 1) !!}"/>
    @endif
@endif
