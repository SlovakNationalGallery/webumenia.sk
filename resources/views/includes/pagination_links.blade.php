@if ($paginator->getLastPage() > 1)
    @if ($paginator->getCurrentPage() > 1)
        <link rel="previous" href="{!! $paginator->appends(@Input::except('page'))->getUrl($paginator->getCurrentPage() - 1) !!}"/>
    @endif
    @if ($paginator->getCurrentPage() < $paginator->getLastPage())
        <link rel="next" href="{!! $paginator->appends(@Input::except('page'))->getUrl($paginator->getCurrentPage() + 1) !!}"/>
    @endif
@endif
