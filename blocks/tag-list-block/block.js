(function (blocks, element) {
    const { registerBlockType } = blocks;
    const { createElement: el } = element;

    registerBlockType('freda/tag-list-block', {
        title: 'Freda Tag List',
        icon: 'tag',
        category: 'freda-category',
        edit: function () {
            return el(
                'div',
                { className: 'tag-list-block-editor' },
                el('p', {}, 'Post tags will be displayed here.')
            );
        },
        save: function () {
            return null;
        }
    });
})(window.wp.blocks, window.wp.element);
