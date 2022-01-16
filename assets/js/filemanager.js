class Filemanager {
    constructor() {
        this.spinner = `<div class="loading-spinner hidden"><div></div><div></div></div>`;
        this.init();
    }

    init() {
        this.initEventListeners();
        this.loadFiles();
    }

    showLoadingScreen() {
        document.querySelector('.filemanager-content').innerHTML = this.spinner;
        document.querySelector('.filemanager-content .loading-spinner').classList.remove('hidden');
    }

    hideLoadingScreen(callback) {
        document.querySelector('.filemanager-content .loading-spinner').classList.add('hidden');
        setTimeout(function() {
            callback()
        }, 300);
    }

    loadFiles() {
        this.showLoadingScreen();
        this.get("/filemanager/")
            .then((res) => {
                this.hideLoadingScreen(() => {
                    document.querySelector(".filemanager-content").innerHTML = res.rendered_content;
                });
            });
    }

    initEventListeners() {
        this.onClick('.file', (file) => {this.loadContent(file)});

        this.onClick('#save_file', () => {
            let saveFileButton = document.getElementById('save_file');
            let content = document.getElementById('file_content').value;
            let file = saveFileButton.dataset.file;
            saveFileButton.classList.add('loading');
            this.post('/filemanager/', {
                saveFile: true,
                content: content,
                file: file
            }, () => { saveFileButton.classList.remove('loading')});
        });

        this.onClick('#back_from_edit', () => {this.loadFiles()});

        this.onClick('#upload_file', () => {
            let upload_input = document.getElementById('upload_file_input');
            let file = upload_input.files[0];
            if(file) {
                this.post('/filemanager/', {
                    upload: 1,
                    file: file,
                }, (res) => {
                    if(res.success) {
                        this.hideUploadModal();
                        this.loadFiles();
                    }
                });
            }
        })

        this.onClick('#toolbar_upload', () => {this.showUploadModal()});
        this.onClick('.filemanager_overlay', () => {this.showUploadModal()});
        this.onClick('.upload-file-modal .modal-close-button', () => {this.hideUploadModal()});
    }

    onClick(selector, callback) {
        document.addEventListener('click',(e) => {
            if(e.target) {
                let elements = document.querySelectorAll(selector);
                elements.forEach(function(element) {
                    if(e.target == element || element.contains(e.target)) {
                        callback(element);
                    }
                })
            }
        });
    }

    loadContent(element) {
        element.classList.add('loading');
        let filename = element.dataset.file;
        this.get(`/filemanager/?loadContent=${filename}`)
            .then((res) => {
                document.querySelector('.filemanager-content').innerHTML = res.rendered_content;
            });
    }

    showUploadModal() {
        document.querySelector('.filemanager_overlay').classList.add('shown');
        document.querySelector('.upload-file-modal').classList.add('shown');
    }

    hideUploadModal() {
        document.querySelector('.filemanager_overlay').classList.remove('shown');
        document.querySelector('.upload-file-modal').classList.remove('shown');
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

    async post(url, data = {}, callback = () => {}) {
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
            callback(res);
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
