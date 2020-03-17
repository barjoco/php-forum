// Alias to get elements by id
const $ = x => document.getElementById(x);

// Used to make AJAX requests and run callback
function x(method, url, async, callback) {
    const x = new XMLHttpRequest();
    x.onreadystatechange = () => {
        if (x.readyState == 4 && x.status == 200) callback(x.responseText);
    }
    x.open(method, url, async);
    x.send();
}

const themeColour = localStorage.getItem("theme") || "default";
$("theme").href = `static/css/${themeColour}_theme.css`;