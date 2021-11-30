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

                    this.post('/filemanager?saveFile', {
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
        const response = await fetch(url, {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: {
              'Content-Type': 'application/json'
            },
            redirect: 'follow',
            referrerPolicy: 'no-referrer',
            body: JSON.stringify(data)
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