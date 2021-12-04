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
                    let content = document.getElementById('file_content').value;
                    let file = saveFileButton.dataset.file;
                    saveFileButton.classList.add('loading');
                    this.post('/filemanager/', {
                        saveFile: true,
                        content: content,
                        file: file
                    }, () => { saveFileButton.classList.remove('loading')});
                }
            }
        });

        document.addEventListener('click',(e) => {
            if(e.target) {
                    let backButton = document.getElementById('back_from_edit');
                if(e.target == backButton) {
                    this.loadFiles();
                }
            }
        });
    }

    loadContent(element) {
        element.classList.add('loading');
        if(element.dataset.editable == true) {
            let filename = element.dataset.file;
            this.get(`/filemanager/?loadContent=${filename}`)
                .then((res) => {
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
