(function (blocks, element, blockEditor) {
	const el = element.createElement;
	const useBlockProps = blockEditor.useBlockProps;
	const { Fragment, useState } = element;

	blocks.registerBlockType('freda-custom-widgets/freda-post-poll', {
		title: 'Post Poll',
		icon: 'analytics',
		category: 'widgets',
		attributes: {
			pollData: {
				type: 'array',
				default: []
			}
		},

		edit({ attributes, setAttributes }) {
            const blockProps = useBlockProps();
            const [questionInput, setQuestionInput] = useState('');
            const [pollData, setPollData] = useState(attributes.pollData || []);
        
            const updateState = (newData) => {
                setPollData(newData);
                setAttributes({ pollData: newData });
            };
        
            const addQuestion = () => {
                if (!questionInput.trim()) return;
                const newData = [
                    ...pollData,
                    {
                        id: 'poll_' + Date.now(),
                        question: questionInput,
                        options: [''],
                        votes: [0]
                    }
                ];
                setQuestionInput('');
                updateState(newData);
            };
        
            const updateOption = (qIndex, oIndex, value) => {
                const newData = [...pollData];
                newData[qIndex].options[oIndex] = value;
                updateState(newData);
            };
        
            const addOption = (qIndex) => {
                const newData = [...pollData];
                newData[qIndex].options.push('');
                newData[qIndex].votes.push(0);
                updateState(newData);
            };
        
            const removeOption = (qIndex, oIndex) => {
                const newData = [...pollData];
                if (newData[qIndex].options.length <= 1) return; // Prevent empty question
                newData[qIndex].options.splice(oIndex, 1);
                newData[qIndex].votes.splice(oIndex, 1);
                updateState(newData);
            };
        
            const removeQuestion = (qIndex) => {
                const newData = [...pollData];
                newData.splice(qIndex, 1);
                updateState(newData);
            };
        
            return el(Fragment, {},
                el('div', blockProps,
                    el('h3', {}, 'Poll Editor'),
        
                    // Add question input
                    el('div', { className: 'add-question' },
                        el('input', {
                            type: 'text',
                            placeholder: 'Enter a new question',
                            value: questionInput,
                            onInput: (e) => setQuestionInput(e.target.value)
                        }),
                        el('button', {
                            className: 'add-question-button',
                            onClick: addQuestion
                        }, 'Add Question')
                    ),
        
                    // Existing questions
                    el('div', { className: 'questions-container' },
                        pollData.map((item, qIndex) =>
                            el('div', { className: 'poll-question', key: qIndex },
                                el('h4', {},
                                    item.question,
                                    el('button', {
                                        style: { marginLeft: '10px' },
                                        onClick: () => removeQuestion(qIndex)
                                    }, 'X')
                                ),
        
                                el('div', { className: 'poll-options' },
                                    item.options.map((opt, oIndex) =>
                                        el('div', { className: 'poll-option', key: oIndex },
                                            el('input', {
                                                type: 'text',
                                                value: opt,
                                                placeholder: `Option ${oIndex + 1}`,
                                                onInput: (e) => updateOption(qIndex, oIndex, e.target.value)
                                            }),
                                            el('button', {
                                                style: { marginLeft: '8px' },
                                                onClick: () => removeOption(qIndex, oIndex)
                                            }, 'X')
                                        )
                                    ),
        
                                    el('button', {
                                        className: 'add-option-button',
                                        onClick: () => addOption(qIndex)
                                    }, 'Add Option')
                                )
                            )
                        )
                    )
                )
            );
        },        

		save({ attributes }) {
            const blockProps = useBlockProps.save();
            const pollData = attributes.pollData || [];
        
            return wp.element.createElement('div', {
                ...blockProps,
                className: `${blockProps.className} poll-frontend`,
                'data-poll': JSON.stringify(pollData)
            },
                pollData.map((item, qIndex) =>
                    wp.element.createElement('div', { className: 'poll-question', key: qIndex },
                        wp.element.createElement('h4', {}, item.question),
                        wp.element.createElement('form', {
                            className: 'poll-form',
                            'data-q-index': qIndex
                        },
                            item.options.map((opt, oIndex) =>
                                wp.element.createElement('div', {
                                    className: 'poll-option',
                                    style: { display: 'flex', alignItems: 'center', gap: '10px' }
                                },
                                    wp.element.createElement('label', {},
                                        wp.element.createElement('input', {
                                            type: 'radio',
                                            name: `poll-question-${qIndex}`,
                                            value: oIndex
                                        }),
                                        ' ',
                                        opt
                                    ),
                                    wp.element.createElement('span', {
                                        className: 'poll-percentage',
                                        'data-q-index': qIndex,
                                        'data-o-index': oIndex,
                                        style: { display: 'none' }
                                    }, '0%')
                                )
                            )
                        ),
                        wp.element.createElement('div', {
                            className: 'poll-spinner',
                            'data-q-index': qIndex,
                            style: { display: 'none', marginTop: '10px' }
                        },
                            wp.element.createElement('svg', {
                                xmlns: "http://www.w3.org/2000/svg",
                                width: "24",
                                height: "24",
                                viewBox: "0 0 24 24",
                                fill: "none",
                                stroke: "currentColor",
                                'stroke-width': "2",
                                'stroke-linecap': "round",
                                'stroke-linejoin': "round",
                                className: "spinner-icon"
                            },
                                wp.element.createElement('circle', {
                                    cx: "12", cy: "12", r: "10", stroke: "#ccc", 'stroke-dasharray': "60", 'stroke-dashoffset': "10"
                                }),
                                wp.element.createElement('path', {
                                    d: "M12 2 a10 10 0 0 1 0 20", stroke: "#333"
                                })
                            )
                        ),
                        wp.element.createElement('div', {
                            className: 'poll-thankyou',
                            'data-q-index': qIndex,
                            style: { display: 'none', marginTop: '10px', fontStyle: 'italic' }
                        }, 'Thank you for voting!')
                        
                    )
                )
            );
        }
        
        
        
	});
})(window.wp.blocks, window.wp.element, window.wp.blockEditor);
