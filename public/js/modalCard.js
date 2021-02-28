const modalElement = document.getElementById("modalContainer");

const getQueryHashValue = () => {
    var query = window.location.hash.substring(1);
    var vars = query.split("&");
    var list = [];

    for (var i=0; i<vars.length; i++)
    {
        var pair = vars[i].split("=");
        list[pair[0]] = pair[1];
    }
    return list;
}

const catchCard = (tasks, id) => {
    var handledTask = tasks.find(x => x.id == id);

    modalElement.innerHTML = "";
    modalElement.style.display = "block";

    modalElement.innerHTML = displayModal(handledTask);
}

function displayModal(task){
    var dateDiff = '';

    // if task status == planed && task has start date -> count time to start
    if(task.status === "planned" && task.start_date !== null)
        dateDiff = resultDateFormatter(
                        new Date().getTime(),
                        new Date(task.start_date).getTime()
                    );

    // if task status == started && task has end date -> count time to end
    else if(task.status === "started" && task.target_end_date !== null)
        dateDiff = resultDateFormatter(
                        new Date().getTime(),
                        new Date(task.target_end_date).getTime()
                    );

    var txt = task.status === "planned" ?
                'Time to start: ' :
                    task.status === "started" ?
                        'Time to end: ':
                        '';

    return '<div class="modal-content">' +
                '<span class="title">' + task.title + '</span>' +
                '<div><b>Status:</b> ' + task.status + '</div>'+
                
                '<div><b>Create Date:</b> ' + task.create_date + '</div>' +
                '<span><b>Start Date:</b> ' + task.start_date + '</span><br>'+
                '<span><b>' + txt +'</b>' + dateDiff +'</span><br>' +
                '<span><b>Author:</b> ' + task.author + '</span><br>' +
                '<div class="content">' + task.content + '</div><br>' +
                '<div class="boxFlex">' +
                    '<a href="/public/scripts/task/send.php?id=' + task.id + '&owner=' + encodeURIComponent(task.owner) + '">send</a>' +
                    '<a href="/public/scripts/task/remove.php?id=' + task.id +'&owner=' + encodeURIComponent(task.owner) + '">remove</a>' +
                '</div>' +
                '<div class="boxFlex cardFooter">' +
                    '<a href="/public/scripts/task/edit.php?id=' + task.id + '&owner=' + encodeURIComponent(task.owner) + '">edit</a>' + /* bug - after click window is refresh and input value is deleted */
                '</div>' +
           '</div>';
}

function resultDateFormatter(currentTime, targetEndDate){
    var milliseconds = targetEndDate - currentTime;

    if(milliseconds < 0 )
        return '';

    var minutes = Math.floor(milliseconds / 60000);
    var hours = Math.floor(minutes / 60);
    var days = Math.floor(hours / 24);
    var years = Math.floor(days / 365);

    if(years !== 0)
    {
        days = days % 365;
        return years + " years " + days + " days ";
    }else if(days !== 0)
    {
        minutes = minutes % 60;
        hours = hours % 24;
        return days + " days, " + hours + " hours, " + minutes + " minutes";
    }else if(hours !== 0)
    {
        minutes = minutes % 60;
        return hours + " hours, " + minutes + " minutes";
    }else if(minutes !== 0)
    {
        return minutes + " minutes";
    }else
        return "less than minute";
}

function modalCard(id) {
    parseJSONtasks().then(
        response => catchCard(response, id),
        reason => console.log(reason)
    );
}

window.onclick = (event) => {
    if (event.target !== modalElement && event.target.tagName !== "A")
    {
        modalElement.style.display = "none";
    }
}