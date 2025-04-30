(function (blocks, element, editor, components) {
    const { registerBlockType } = blocks;
    const { createElement: el } = element;
    const { RichText, InspectorControls } = editor;
    const { PanelBody, TextControl, Button, TextareaControl } = components;
    const { Fragment, useState } = wp.element;

    registerBlockType('freda/source-list-block', {
        title: 'Freda Quellenangabe',
        icon: 'admin-links',
        category: 'freda-category',
        attributes: {
            sources: {
                type: 'array',
                default: [],
            }
        },
        edit: function (props) {
            const { attributes, setAttributes } = props;
            const { sources } = attributes;

            const updateSource = (index, field, value) => {
                const newSources = [...sources];
                newSources[index][field] = value;
                setAttributes({ sources: newSources });
            };

            const addSource = () => {
                setAttributes({
                    sources: [...sources, { name: '', url: '' }]
                });
            };

            const removeSource = (index) => {
                const newSources = [...sources];
                newSources.splice(index, 1);
                setAttributes({ sources: newSources });
            };

            return el(Fragment, {},
                el('div', { className: 'source-list-editor' },
                    el('h4', {}, 'Quellenangabe'),
                    sources.map((item, index) => el('div', { key: index, style: { marginBottom: '10px' } },
                        el(TextControl, {
                            label: 'Name',
                            value: item.name,
                            onChange: (val) => updateSource(index, 'name', val)
                        }),
                        el(TextControl, {
                            label: 'URL',
                            value: item.url,
                            onChange: (val) => updateSource(index, 'url', val)
                        }),
                        el(Button, {
                            isDestructive: true,
                            onClick: () => removeSource(index)
                        }, 'Remove')
                    )),
                    el(Button, { isPrimary: true, onClick: addSource }, 'Add Source')
                )
            );
        },
        save: function (props) {
            const { attributes } = props;
            const { sources } = attributes;

            return el('div', { className: 'freda-source-list' },
                el('h4', {}, 'Quellenangabe'),
                el('ul', {},
                    sources.map((item, index) =>
                        el('li', { key: index },
                            el('a', {
                                href: item.url,
                                target: '_blank',
                                rel: 'noopener noreferrer'
                            }, item.name)
                        )
                    )
                )
            );
        }
    });
})(
    window.wp.blocks,
    window.wp.element,
    window.wp.blockEditor || window.wp.editor,
    window.wp.components
);