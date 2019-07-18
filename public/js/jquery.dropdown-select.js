(function ($) {
    $.fn.dropdownSelect = function () {
        var init = function (elSel) {
            var $select = $(elSel),
                $group = $select.parent(),
                $label = $('label[for=' + elSel.id + ']', $group),
                $form = $(elSel.form),
                $selected = $('option:selected', $select),
                $notSelected = $('option:not(:selected)', $select),
                $anchor = $('<a>').attr({
                    class: 'dropdown-toggle',
                    id: 'dropdownSortBy',
                    'data-toggle': 'dropdown',
                    'aria-expanded': 'false'
                }).text(
                    $label.text() + ' ' + $selected.text()
                ).append(
                    ' <span class="caret"></span>'
                ).dropdown(),
                $items = $notSelected.map(function () {
                    var $item = $(this);
                    return $('<li>').attr({
                        role: 'presentation'
                    }).append(
                        $('<a>').attr({
                            role: 'menuitem',
                            tabindex: -1,
                            href: '#',
                        }).text(
                            $(this).text()
                        ).click(function (e) {
                            e.preventDefault();
                            $select.val($item.val());
                            $form.submit();
                        })
                    );
                }).get(),
                $list = $('<ul>').attr({
                    class: 'dropdown-menu dropdown-menu-right dropdown-menu-sort',
                    role: 'menu',
                    ariaLabelledby: 'dropdownSortBy'
                }).append($items),
                $dropdown = $('<div>').attr({
                    class: 'dropdown'
                }).append($anchor, $list);

            $group.hide();
            $group.parent().append($dropdown);
        };

        return this.each(function () {
            init(this);
        })
    }
})(jQuery);