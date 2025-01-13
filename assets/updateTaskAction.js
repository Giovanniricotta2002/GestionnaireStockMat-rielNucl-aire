document.addEventListener('DOMContentLoaded', () => {
    const button = document.getElementById('get-values-button');
    const selections = document.getElementsByClassName('statusSelector')

    window.selections = selections
    Array.prototype.slice.call(selections).forEach(selection => {
        selection.addEventListener('change', async function () {
            const taskId = this.dataset.taskId
            const statusSelectedOption = this.value
            console.log(taskId, statusSelectedOption);

            const request = await fetch('/task/update/' + taskId, {
                method: 'PUT',
                body: JSON.stringify({'status': statusSelectedOption})
            })
            
        });
    });

});