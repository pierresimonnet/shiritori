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
// Loader (button)
let span = document.createElement('span')
span.className = 'btn-loader'
span.innerText = "loading"

// Scroll String
const scroll = function(){
    if(string && string.scroll !== 0){
        string.scrollLeft = string.scrollWidth - string.clientWidth
    }
}

window.addEventListener('load', function(){
    scroll()
}, false)

// POST NEW WORD
postForm.addEventListener('submit', async function (e) {
    e.preventDefault();
    button.disabled = true
    button.innerText = ''
    button.appendChild(span)
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
            word.id = responseData.id
            word.className = "shiritori-word"
            word.setAttribute('data-word-id', responseData.id)
            word.innerText = responseData.word
            string.appendChild(word)

            if(responseData.next) {
                let next = document.createElement('p')
                next.style.background = 'rgba(54,146,240,0.5)'
                next.id = responseData.nextId
                next.className = "shiritori-word app-word"
                next.setAttribute('data-word-id', responseData.nextId)
                next.innerText = responseData.next
                string.appendChild(next)
            }

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

// List of words
let words = document.getElementsByClassName('shiritori-word')
// Word info
const info = document.getElementById('shiritori-word__info')
let infoPara = document.createElement('p')
info.appendChild(infoPara)
infoPara.innerText = "Cliquez sur un mot pour voir sa lecture et sa définition"

// GET WORD INFO
async function getData(wordId) {
    try {
        let response = await fetch("word/"+ wordId, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
        })
        let responseData = await response.json()
        return infoPara.innerText = 'reading : ' + responseData.reading + ' | sense : ' + responseData.sense
    } catch (e) {
        console.error(e)
    }
}

// DISPLAY INFO ON CLICK
string.addEventListener('click', function (e) {
    if(e.target && e.target.nodeName === 'P'){
        let wordId = e.target.dataset.wordId
        if(e.target.classList.contains('active')) return false
        infoPara.innerText = ''
        infoPara.appendChild(span)
        if (string.querySelector('.active')) string.querySelector('.active').classList.remove('active')
        e.target.classList.add('active')
        info.classList.add('info-active')
        getData(wordId).then(r => console.log('data ok'))
    }
}, false)

// HIDE INFO ON CLICK
info.addEventListener('click', function (e) {
    if (string.querySelector('.active')) string.querySelector('.active').classList.remove('active')
    info.classList.remove('info-active')
    infoPara.innerText = 'Cliquez sur un mot pour voir sa lecture et sa définition'
}, false)