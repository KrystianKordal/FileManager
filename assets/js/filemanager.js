class Filemanager {
    constructor() {
        this.init();
    }

    init() {
        this.loadFiles();
        this.initEventListeners();
    }

    loadFiles() {
        this.request(function(res) {
            document.querySelector(".filemanager-content").innerHTML = res.rendered_content;
        }, "/filemanager");
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

            this.request( function(res) {
                console.log(res);
            }, `/filemanager?loadContent=${filename}`);
        }
    }

    request(callback, url) {
        fetch(url)
        .then(res => res.json())
        .then(res => {
            if(res.error) {
                console.error(res.error);
            } else {
                callback(res);
            }
        });
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const filemanager = new Filemanager();
});