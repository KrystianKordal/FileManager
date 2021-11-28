class Filemanager {
    constructor() {
        this.init();
    }

    init() {
        this.loadFiles();
        this.initEventListeners();
    }

    loadFiles() {
        fetch("/filemanager")
        .then(res => res.json())
        .then(res => {
            document.querySelector(".filemanager-content").innerHTML = res.rendered_content;
        });
    }

    initEventListeners() {
        let self = this;
        document.addEventListener('click',(e) => {
            let files = document.querySelectorAll('.file');
            if(e.target) {
                files.forEach(function(file) {
                    if(file.contains(e.target) || e.target == file) {
                        self.loadContent(file);
                    }
                });
            }
        });
    }

    loadContent(element) {
        if(element.dataset.editable == true) {
            let filename = element.dataset.file;

            fetch(`/filemanager?loadContent=${filename}`)
            .then(res => res.json())
            .then(res => {
                console.log(res);
            });
        }
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const filemanager = new Filemanager();
});