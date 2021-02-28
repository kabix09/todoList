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

const parseJSONtasks = async() => {
    return await JSON.parse(localStorage.getItem("userTasks"));
}

const loadTasks = async() => {
    try{
        const result = await fetch('http://todolist.localhost:8000/public/endpoints/tasks.php')
        tasks = await result.json();

        localStorage.setItem("userTasks", JSON.stringify(tasks));

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
                            '<span class="title"><b>' + task.title + '</b></span><br>' +
                            '<span><b>Status: </b>' + task.status + '</span><br>' +
                            '<span><b>Content: </b></span><br>' +
                            '<div class="content">' + task.content + '</div>' +
                            '<div class="boxFlex">' +

                                (task.status === "prepared" ?
                                        '<a href="/public/scripts/task/changeStatus.php?id=' + task.id + '&owner=' + encodeURIComponent(task.owner) +'&new=planned">plan</a>':
                                    (task.status === "planned" || task.status === "paused" ?
                                        '<a href="/public/scripts/task/changeStatus.php?id=' + task.id + '&owner=' + encodeURIComponent(task.owner) +'&new=started">activate</a>':
                                        (task.status === 'started' ?
                                            '<a href="/public/scripts/task/changeStatus.php?id=' + task.id + '&owner=' + encodeURIComponent(task.owner) +'&new=finished">finish</a>' +
                                            '<a href="/public/scripts/task/changeStatus.php?id=' + task.id + '&owner=' + encodeURIComponent(task.owner) +'&new=paused">stop</a>':
                                            '')))
                                +'<a href="/public/scripts/task/remove.php?id=' + task.id +'&owner=' + encodeURIComponent(task.owner) + '">remove</a>' +
                            '</div>' +
                            '<div class="boxFlex cardFooter">' +
                                '<a href="#id=' + task.id + '" onclick="modalCard(' + task.id + ')">info</a>' +
                            '</div>' +
                        '</div>';
            }
        ).join('');

        tasksList.innerHTML = htmlTasks;
};

loadTasks().then(
    response => displayTasks(response),
    reason => console.log(reason)
);