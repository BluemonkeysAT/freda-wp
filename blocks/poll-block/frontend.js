document.addEventListener('DOMContentLoaded', function () {
	const polls = document.querySelectorAll('.poll-frontend');

	polls.forEach(poll => {
		const pollData = JSON.parse(poll.getAttribute('data-poll'));
		const postId = poll.getAttribute('data-post-id');

		pollData.forEach((item, qIndex) => {
			const form = poll.querySelector(`form[data-q-index="${qIndex}"]`);
			const inputs = form.querySelectorAll(`input[name="poll-question-${qIndex}"]`);

			if (item.voted) {
				showPercentages(item.votes, qIndex, poll);
				inputs.forEach(input => {
					input.disabled = true;
					if (parseInt(input.value) === item.votedIndex) {
						input.closest('.poll-option')?.classList.add('voted-option');
					}
				});
				return;
			}

			inputs.forEach(input => {
				input.addEventListener('change', function () {
					const oIndex = parseInt(this.value);
					const spinner = poll.querySelector(`.poll-spinner[data-q-index="${qIndex}"]`);
					const thankyou = poll.querySelector(`.poll-thankyou[data-q-index="${qIndex}"]`);
				
					if (spinner) spinner.style.display = 'block';
					inputs.forEach(input => input.disabled = true);
				
					fetch(fredaPollAjax.ajax_url, {
						method: 'POST',
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
						body: `action=freda_poll_vote&post_id=${postId}&data=${encodeURIComponent(JSON.stringify({
							id: item.id,
							optionIndex: oIndex
						}))}`
					})
					.then(r => r.json())
					.then(resp => {
						console.log('[AJAX] Full response:', resp);

						if (spinner) spinner.style.display = 'none';

						if (!resp || !resp.success || !Array.isArray(resp.votes)) {
							alert(resp.data?.message || 'Already voted!');
							inputs.forEach(input => input.disabled = false);
							return;
						}
				
						item.votes = resp.data.votes;
						item.voted = true;
						item.votedIndex = resp.data.votedIndex;
				
						showPercentages(item.votes, qIndex, poll);
				
						inputs.forEach(input => {
							input.disabled = true;
							if (parseInt(input.value) === item.votedIndex) {
								input.closest('.poll-option')?.classList.add('voted-option');
							}
						});
				
						if (thankyou) thankyou.style.display = 'block';
					})
					.catch(err => {
						console.error('Vote failed:', err);
						if (spinner) spinner.style.display = 'none';
						alert('Something went wrong.');
						inputs.forEach(input => input.disabled = false);
					});
				});
				
			});
		});
	});
});

function showPercentages(votesArray, qIndex, poll) {
	const totalVotes = votesArray.reduce((sum, v) => sum + v, 0);

	votesArray.forEach((count, oIndex) => {
		const percent = totalVotes > 0
			? ((count / totalVotes) * 100).toFixed(1)
			: '0.0';

		const span = poll.querySelector(`.poll-percentage[data-q-index="${qIndex}"][data-o-index="${oIndex}"]`);
		if (span) {
			span.style.display = 'inline';
			span.textContent = `${percent}%`;
		}
	});
}
