(function (blocks, element, editor) {
    const { registerBlockType } = blocks;
    const { createElement: el } = element;
    const { RichText } = editor;

    registerBlockType('freda/quote-block', {
        title: 'Quote Block',
        icon: 'format-quote',
        category: 'text',
        attributes: {
            quote: { type: 'string' },
            author: { type: 'string' }
        },

        edit: function (props) {
            return el(
                'div',
                { className: 'quote-block' },
                el('span', { className: 'quote-icon' }, '“'),
                el(RichText, {
                    tagName: 'p',
                    className: 'quote-text',
                    value: props.attributes.quote,
                    onChange: function (val) {
                        props.setAttributes({ quote: val });
                    },
                    placeholder: 'Insert quote...'
                }),
                el(RichText, {
                    tagName: 'p',
                    className: 'quote-author',
                    value: props.attributes.author,
                    onChange: function (val) {
                        props.setAttributes({ author: val });
                    },
                    placeholder: 'Firstname Lastname'
                })
            );
        },

        save: function (props) {
            return el(
                'div',
                { className: 'quote-block' },
                el('span', { className: 'quote-icon' }, '“'),
                el('p', { className: 'quote-text' }, props.attributes.quote),
                el('p', { className: 'quote-author' }, props.attributes.author)
            );
        }
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor || window.wp.editor);