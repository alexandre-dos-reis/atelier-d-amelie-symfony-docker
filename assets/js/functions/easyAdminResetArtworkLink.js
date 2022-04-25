import axios from 'axios'

const link = document.getElementById('reset-artwork-link')

if (link) {
    link.addEventListener('click', () => {
        const resetLink = link.dataset.artworkResetLink
        axios.post(resetLink)
        window.location.reload()
    })
  
}
