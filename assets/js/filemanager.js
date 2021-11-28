document.addEventListener("DOMContentLoaded", function() {
    fetch("/filemanager")
        .then(res => res.json())
        .then(res => {
            document.querySelector(".filemanager-content").innerHTML = res.rendered_content;
        });
});