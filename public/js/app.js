// GET
function ajaxGet(url, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', url);
    xhr.onreadystatechange = function () {
        if(this.status >= 200 && this.status < 400){
            callback(this.responseText);
            console.log('get ' + this.status);
        }else{
            console.error(xhr.status + this.statusText);
        }
    };
    xhr.onerror = function () {
        console.error("Erreur réseau avec l'URL " + url);
    };
    xhr.setRequestHeader('X-Requested-With', 'xmlhttprequest');
    xhr.send(null);
}

// Get List
function getList(){
    ajaxGet('index.php', function (response) {
        string.innerHTML = "";
        const data = JSON.parse(response);
        for(let i = 0; i < data.length; i++){
            const entry = document.createElement('p')
            entry.innerText = data[i]['word'] + " >";
            string.appendChild(entry)
        }
        string.scrollLeft = document.getElementsByClassName('string')[0].scrollLeftMax;
    })
}

// POST
function ajaxPost(url, data, callback, isJson){
    const xhr = new XMLHttpRequest();
    xhr.open('POST', url);
    xhr.onreadystatechange =  function () {
        if(this.readyState === 4){
            if(this.status >= 200 && this.status <=  400){
                callback(this.responseText);
                console.log('post ' + this.status);
            }else{
                console.error(this.status + this.statusText);
            }
        }
    };
    xhr.onerror = function () {
        console.error("Erreur réseau avec l'URL " + url);
    };
    if(isJson){
        xhr.setRequestHeader('Content-Type', 'application/json');
        data = JSON.stringify(data);
    }
    xhr.setRequestHeader('X-Requested-With', 'xmlhttprequest');
    xhr.send(data);
}

// DOM
const stringSection = document.getElementById('string-section');
const alertSection = document.getElementById('alert-section');
const postForm = document.getElementById('post-form');
const input = document.getElementById('input');
const string = document.getElementById('string');
const alert = document.createElement('div');

// CREATE ALERT
function query(response){
    const notif = JSON.parse(response);
    alert.innerText = notif.message;
    alert.classList.add(notif.status);
    alertSection.innerText = "";
    alertSection.appendChild(alert);

    if(notif.status === "success"){
        input.value = "";
        getList();
    }
}

// POST NEW
postForm.addEventListener('submit', function (e) {
    e.preventDefault();
    /*const loader = document.createElement('img');
    loader.src = "img/loader.gif";
    alertSection.appendChild(loader);*/
    alertSection.innerText = "loading...";
    const data = new FormData(this);
    ajaxPost(this.getAttribute('action'), data, function (response) {
        query(response);
    }, false);
    return false;
});

// RESET
const resetForm = document.getElementById('reset-form');
if(resetForm !== null){
    resetForm.addEventListener('submit', function (e) {
        e.preventDefault();
        alertSection.innerText = "loading...";
        const data = new FormData(this);
        ajaxPost(this.getAttribute('action'),data ,function(response){
            query(response);
            stringSection.innerHTML = "";
            const startNotif = document.createElement('p');
            startNotif.classList.add('start');
            startNotif.innerText = "Envoyez un mot pour commencer un nouveau shiritori.";
            stringSection.appendChild(startNotif);
        }, false);
        return false;
    });
}
