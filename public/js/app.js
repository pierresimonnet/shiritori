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
// Counter
const counter =  document.querySelector('#counter')

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
            alert.classList.remove('alert')
            alert.innerText = responseData.success
            alert.classList.add(responseData.type)
            alertSection.innerText = ""
            alertSection.appendChild(alert)
            input.value = ''
            counter.innerText = responseData.count

            let word = document.createElement('p')
            word.style.background = 'rgba(65,240,133,0.5)'
            word.innerText = responseData.word
            string.appendChild(word)

            window.setTimeout(function () {
                alertSection.innerText = ""
                word.style.removeProperty('background')
            }, 3000)

            scroll()
        }
    }catch (e) {
        console.error(e)
    }
    button.disabled = false
    button.innerText = "Envoyer"
    return false
}, false)

let words = document.getElementsByClassName('shiritori-word')
const info = document.getElementById('shiritori-word__info')
info.innerText = "Cliquez sur un mot pour voir sa lecture et sa définition"

async function getData(wordId) {
    try {
        let response = await fetch("word/"+ wordId, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
        })
        let responseData = await response.json()
        return info.innerText = 'reading : ' + responseData.reading + ' | sense : ' + responseData.sense
    } catch (e) {
        console.error(e)
    }
}

for (let i = 0; i < words.length; i++){
    let wordId = words[i].dataset.wordId
    words[i].addEventListener('click', function (e) {
        if(words[i].classList.contains('active')) return false

        info.innerText = "loading..."
        if (string.querySelector('.active')) string.querySelector('.active').classList.remove('active')
        words[i].classList.add('active')
        info.classList.add('info-active')
        getData(wordId).then(r => console.log('data ok'))
    }, false)

    info.addEventListener('click', function (e) {
        if (string.querySelector('.active')) string.querySelector('.active').classList.remove('active')
        info.classList.remove('info-active')
        info.innerText = 'Cliquez sur un mot pour voir sa lecture et sa définition'
    }, false)
}
