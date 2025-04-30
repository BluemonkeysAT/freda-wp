(function (blocks, element, editor) {
    const { registerBlockType } = blocks;
    const { createElement: el } = element;
    const { RichText } = editor;

    registerBlockType('freda/dark-box', {
        title: 'Freda Dark Box',
        icon: 'excerpt-view',
        category: 'freda-category',
        attributes: {
            content: { type: 'string' }
        },

        edit: function (props) {
            return el(
                'div',
                { className: 'freda-dark-box' },
                el(RichText, {
                    tagName: 'p',
                    className: 'freda-dark-box-text',
                    value: props.attributes.content,
                    onChange: function (val) {
                        props.setAttributes({ content: val });
                    },
                    placeholder: 'Text inside dark box...'
                })
            );
        },

        save: function (props) {
            return el(
                'div',
                { className: 'freda-dark-box' },
                el('p', { className: 'freda-dark-box-text' }, props.attributes.content)
            );
        }
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor || window.wp.editor);