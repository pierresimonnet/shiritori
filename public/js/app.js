// DOM
const stringSection = document.getElementById('string-section');
const alertSection = document.getElementById('alert-section');
const postForm = document.getElementById('post-form');
const input = document.getElementById('input');
const string = document.getElementById('string');
const alert = document.createElement('div');

// Get List
let getList = async function getList() {
    try {
        let response = await fetch('index.php',{
            headers: {
                'X-Requested-With': 'xmlhttprequest'
            },
        })
        if(response.ok){
            let responseData = await response.json()
            string.innerHTML = "";
            for(let i = 0; i < responseData.length; i++){
                let entry = document.createElement('p')
                entry.innerText = responseData[i]['word'] + " >";
                string.appendChild(entry)
            }
        }else{
            console.error(response.status)
        }
    } catch (e) {
        console.error(e)
    }
}

// CREATE ALERT
function alertNotif(response){
    alert.innerText = response.message;
    alert.classList.add(response.status);
    alertSection.innerText = "";
    alertSection.appendChild(alert);

    if(response.status === "success"){
        input.value = "";
        getList();
    }
}

// POST NEW
postForm.addEventListener('submit', async function (e) {
    e.preventDefault();
    /*const loader = document.createElement('img');
    loader.src = "img/loader.gif";
    alertSection.appendChild(loader);*/
    alertSection.innerText = "loading...";
    let data = new FormData(this);
    try{
        let response = await fetch(this.getAttribute('action'), {
            method: 'POST',
            headers: {
                'X-Requested-With': 'xmlhttprequest'
            },
            body: data
        })
        let responseData = await response.json()
        alertNotif(responseData)
    }catch (e) {
        alert(e)
    }
    return false;
});

// RESET
const resetForm = document.getElementById('reset-form');
if(resetForm !== null){
    resetForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        alertSection.innerText = "loading...";
        const data = new FormData(this);
        try{
            let response = await fetch(this.getAttribute('action'), {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'xmlhttprequest'
                },
                body: data
            })
            let responseData = await response.json()
            if(response.ok === false){
                console.log('erreur systeme')
            }else{
                alertNotif(responseData)
                stringSection.innerHTML = "";
                let startNotif = document.createElement('p');
                startNotif.classList.add('start');
                startNotif.innerText = "Envoyez un mot pour commencer un nouveau shiritori.";
                stringSection.appendChild(startNotif);
            }
        }catch (e) {
            alert(e)
        }
        return false;
    });
}
