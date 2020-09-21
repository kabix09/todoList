const tasksList = document.getElementById("mainBlock");
const searchBar = document.getElementById("searchBar");
let tasks = [];

searchBar.addEventListener('keyup', (e) => {
    const searchedString = e.target.value.toLowerCase();
    const filteredTasks = tasks.filter((task) => {
        return (
            task.title.toLowerCase().includes(searchedString) ||
            task.content.toLowerCase().includes(searchedString)
        )
    });
    displayTasks(filteredTasks);
});


const loadTasks = async() => {
    try{
        const result = await fetch('http://todolist.localhost:8000/src/JSON/tasks.php')
        tasks = await result.json();

    }catch (error) {
        console.error(error);
    }

    return tasks;
}

const displayTasks = (tasks) => {
    const htmlTasks = tasks
        .map(
            (task) => {
                return '<div class="card">' +
                            '<span><b>Title:</b> ' + task.title + '</span><br>' +
                            '<span><b>Author:</b> ' + task.author + '</span><br>' +
                            '<span><b>Create Date:</b> ' + task.create_date + '</span><br>' +
                            '<span><b>Content:</b></span><br>' +
                            '<div style="margin:5px;">' + task.content + '</div><br>' +
                            '<br>' +
                            '<div style="width: 100%; display: flex; justify-content: space-around;">' +
                                '<a href="">finish</a>'+
                                '<a href="./scripts/editTask.php?id=' + task.id + '&owner=' + task.owner + '">edit</a>' + /* bug - after click window is refresh and input value is deleted */
                                '<a href="./scripts/removeTask.php?id=' + task.id +'&owner=' + task.owner + '">remove</a>' +
                            '</div>' +
                        '</div>';
            }
        )
        .join('');
    tasksList.innerHTML = htmlTasks;
};

loadTasks().then(
    response => displayTasks(response),
    reason => console.log(reason)
);