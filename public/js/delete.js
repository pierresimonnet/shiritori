const deleteForms = document.getElementsByClassName('delete-form')

for(let i = 0; i < deleteForms.length; i++){
    // DELETE
    deleteForms[i].addEventListener('submit', async function (e) {
        e.preventDefault()
        let data = new FormData(this);
        if (confirm('Le shiritori va être supprimé')){
            try {
                await fetch(this.getAttribute('action'), {
                    method: 'post',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: data
                })
                console.log("deleted")
                deleteForms[i].parentElement.remove()
            }catch (e) {
                console.error(e)
            }
        }

        return false
    })
}
