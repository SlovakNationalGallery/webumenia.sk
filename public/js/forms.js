$(function() {
    var collectionsSelector = '*[data-prototype]';

    var addClicked = function(e) {
        e.preventDefault();

        var $this = $(this);
        var collection = $(this).closest(collectionsSelector);

        var index = collection.data('index');
        collection.data('index', index + 1);

        var prototype = collection.data('prototype');
        prototype = prototype.replace(/__name__/g, index);
        var newForm = $(prototype).find('div[id]:first');
        $this.before(newForm);
    };

    $(collectionsSelector).each(function() {
        var $this = $(this);

        $this.data('index', $this.find(':input').length);

        var add = $('<input type="button" value="Add">');
        var collection = $this;
        $this.append(add);
        add.on('click', addClicked);
    });
});