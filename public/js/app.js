// DOM Elements
// String
const string = document.querySelector('#string')
// Alert Section
const alertSection = document.querySelector('#alert-section');
const alert = document.createElement('div');
// Form
const postForm = document.querySelector('#post-form')
const input = document.querySelector('#word_word')
const button = document.querySelector('button[type=submit]')
let inputDiv = document.querySelector('.form-section__input')
let shiritoriId = inputDiv.dataset.shiritoriId

// Scroll String
const scroll = function(){
    if(string && string.scroll !== 0){
        string.scrollLeft = string.scrollWidth - string.clientWidth
    }
}

window.addEventListener('load', function(){
    scroll()
}, false)

// POST NEW
postForm.addEventListener('submit', async function (e) {
    e.preventDefault();
    button.disabled = true
    button.innerText = "loading..."
    let data = new FormData(this);
    try{
        let response = await fetch(this.getAttribute('action'), {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: data
        })
        let responseData = await response.json()
        if(response.ok === false){
            alert.classList.remove('success')
            alert.innerText = responseData.errors.word[0]
            alert.classList.add(responseData.type)
            alertSection.innerText = ""
            alertSection.appendChild(alert)
        }else{
            console.log(responseData.word)
            alert.classList.remove('alert')
            alert.innerText = responseData.success
            alert.classList.add(responseData.type)
            alertSection.innerText = ""
            alertSection.appendChild(alert)
            input.value = ''
            let word = document.createElement('p')
            word.innerText = responseData.word + " > "
            string.appendChild(word)
            scroll()
        }
    }catch (e) {
        console.error(e)
    }
    button.disabled = false
    button.innerText = "Envoyer"
    return false;
});