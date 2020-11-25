@if ($item->related_work_order !== null && $item->related_work_total)
    ({{ $item->related_work_order }}/{{ $item->related_work_total }})
@elseif ($item->related_work_order)
    ({{ $item->related_work_order }})
@endif