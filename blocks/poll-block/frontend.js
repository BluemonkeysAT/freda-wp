document.addEventListener('DOMContentLoaded', function () {
	const polls = document.querySelectorAll('.poll-frontend');

	polls.forEach(poll => {
		const pollData = JSON.parse(poll.getAttribute('data-poll'));

		poll.querySelectorAll('.poll-vote-button').forEach(button => {
			button.addEventListener('click', function () {
				const qIndex = this.getAttribute('data-question-index');
				const selected = poll.querySelector(`input[name="poll-question-${qIndex}"]:checked`);
				if (!selected) return alert('Please select an option.');

				const vote = {
					question: pollData[qIndex].question,
					optionIndex: selected.value
				};

				fetch(fredaPollAjax.ajax_url, {
					method: 'POST',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
					body: `action=freda_poll_vote&data=${encodeURIComponent(JSON.stringify(vote))}`
				})
				.then(res => res.json())
				.then(data => {
					if (data.success) {
						alert('Vote submitted!');
					} else {
						alert('Something went wrong.');
					}
				});
			});
		});
	});
});
