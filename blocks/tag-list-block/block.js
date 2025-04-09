(function (blocks, element) {
    const { registerBlockType } = blocks;
    const { createElement: el } = element;

    registerBlockType('freda/tag-list-block', {
        title: 'Tag List',
        icon: 'tag',
        category: 'widgets',
        edit: function () {
            return el(
                'div',
                { className: 'tag-list-block-preview' },
                el('span', { className: 'quote-icon' }, '🏷️'),
                el('p', {}, 'Tags will be shown on the frontend.')
            );
        },
        save: function () {
            return null; // Rendered dynamically in PHP
        }
    });
})(window.wp.blocks, window.wp.element);