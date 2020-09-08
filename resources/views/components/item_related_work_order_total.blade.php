@if ($item->related_work_order)
    ({{ $item->related_work_order }}@if ($item->related_work_total)/{{ $item->related_work_total }}@endif)
@endif