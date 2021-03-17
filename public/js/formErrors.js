var list;
var snackBarList = [];
var request = new XMLHttpRequest();

request.open("GET", path, true);

request.onreadystatechange = function() {

    if(this.readyState === 4 && this.status === 200)
    {
        list = JSON.parse("[" + this.responseText + "]")[0];

        Array.from(Object.keys(list)).forEach(function(key) {
            Array.from(Object.values(list[key])).forEach(function(value) {
                snackBarList.push(
                    new SnackBar({
                        message: value,
                        status: "error"
                    })
                );
            });
        });
    }
};
request.send();