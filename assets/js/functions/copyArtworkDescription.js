import axios from 'axios'

const button = document.getElementById('js-copy-artwork-description-button')

if (button) {
  // const artworkId = button.getAttribute('artwork-id')
  const selectArtwork = document.getElementById('Product_artwork')
  const textarea = document.getElementById('Product_description')

  button.addEventListener('click', async () => {
    try {
      const artworkId = selectArtwork.options[selectArtwork.selectedIndex].value
      const res = await axios.get(`/artwork/${artworkId}`)
      textarea.value = res.data.description
    } catch (e) {
      console.error(e)
    }
  })
}
