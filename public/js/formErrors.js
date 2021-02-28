var list;
var snackBarList = [];
var request = new XMLHttpRequest();

request.open("GET", path, true);

request.onreadystatechange = function() {

    if(this.readyState === 4 && this.status === 200)
    {
        list = JSON.parse(this.responseText);

        Array.from(Object.keys(list)).forEach(function(key) {
            snackBarList.push(
                new SnackBar({
                    message: key + ": " + list[key][0],
                    status: "error"
                })
            );
        });
    }
};
request.send();