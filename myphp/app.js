function toggleGoal(id, checkbox) {
    fetch('api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=toggle&id=' + id + '&status=' +
              (checkbox.checked ? 'Completed' : 'Pending')
    });
}

function deleteGoal(id) {
    if (!confirm('Delete this goal?')) return;

    fetch('api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=delete&id=' + id
    }).then(() => location.reload());
}

