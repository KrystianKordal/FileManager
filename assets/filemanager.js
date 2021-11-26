document.addEventListener("DOMContentLoaded", function() {
    fetch("/filemanager")
        .then(res => res.json())
        .then(res => {
            console.log(res);
        });
});