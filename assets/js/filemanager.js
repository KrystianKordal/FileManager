class Filemanager {
    constructor() {
        this.init();
    }

    init() {
        this.loadFiles();
        this.initEventListeners();
    }

    loadFiles() {
        this.get("/filemanager")
            .then(function(res) {
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

        document.addEventListener('click',(e) => {
            let files = document.querySelectorAll('.file');
            if(e.target) {
                    let saveFileButton = document.getElementById('save_file');
                if(e.target == saveFileButton) {
                    let content = document.getElementById('save_file').value;
                    let file = saveFileButton.dataset.file;

                    this.post('/filemanager/', {
                        saveFile: true,
                        content: content,
                        file: file
                    });
                }
            }
        });
    }

    loadContent(element) {
        if(element.dataset.editable == true) {
            let filename = element.dataset.file;

            this.get(`/filemanager?loadContent=${filename}`)
                .then(function(res) {
                    document.querySelector('.filemanager-content').innerHTML = res.content;
                });
        }
    }

    async get(url) {
        const response = await fetch(url)
        .then(res => res.json())
        .then(res => {
            if(res.error) {
                console.error(res.error);
            }

            return res;
        });

        if(!response.error) {
            return response;
        } else {
            return false;
        }
    }

    async post(url, data = {}) {
        let formData = new FormData();
        for (const [key, value] of Object.entries(data)) {
            formData.append(key, value);
        }

        const response = await fetch(url, {
            method: 'POST',
            body: formData
          })
        .then(res => res.json())
        .then(res => {
            if(res.error) {
                console.error(res.error);
            }

            return res;
        });

        if(!response.error) {
            return response;
        } else {
            return false;
        }
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const filemanager = new Filemanager();
});
