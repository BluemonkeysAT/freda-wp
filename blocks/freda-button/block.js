(function (blocks, element, editor) {
    const { registerBlockType } = blocks;
    const { createElement: el } = element;
    const { RichText, URLInputButton } = editor;

    registerBlockType('freda/freda-button', {
        title: 'Freda Button',
        icon: 'button',
        category: 'common',
        attributes: {
            text: { type: 'string', default: 'Jetzt klicken' },
            url: { type: 'string', default: '#' }
        },

        edit: function (props) {
            return el(
                'div',
                { className: 'freda-button-wrapper' },
                el(RichText, {
                    tagName: 'a',
                    className: 'freda-button',
                    value: props.attributes.text,
                    onChange: function (val) {
                        props.setAttributes({ text: val });
                    },
                    placeholder: 'Button Text',
                    keepPlaceholderOnFocus: true
                }),
                el(URLInputButton, {
                    url: props.attributes.url,
                    onChange: function (url) {
                        props.setAttributes({ url: url });
                    }
                })
            );
        },

        save: function (props) {
            return el(
                'a',
                {
                    className: 'freda-button',
                    href: props.attributes.url
                },
                props.attributes.text
            );
        }
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor || window.wp.editor);