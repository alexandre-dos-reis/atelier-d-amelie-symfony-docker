import axios from 'axios'

export default class AddProduct extends HTMLElement {
  constructor() {
    super()
    this.targetObject = document.getElementById(this.getAttribute('idTarget'))
  }

  connectedCallback() {
    this.addEventListener('click', this.addProductToCart)
  }

  disconnectedCallback() {
    this.removeEventListener('click', this.launch)
  }

  async addProductToCart() {
    const res = await this.apiCall()
    this.updateCountProduct(res.data.numberOfProducts)
    this.showFlashMessage(res)
  }

  apiCall() {
    try {
      const id = this.targetObject.getAttribute('idProduct')
      const value = this.targetObject.getAttribute('value')
      return axios.post(`/panier/${id}/${value}`)
    } catch (error) {
      console.log(error)
    }
  }

  updateCountProduct(updatedNumber) {
    let countProduct = document.getElementById('countProducts')

    if (countProduct === null) {
      document
        .getElementById('cart-icon')
        .insertAdjacentHTML('afterend', '<span id="countProducts"></span>')
      countProduct = document.getElementById('countProducts')
    }

    countProduct.textContent = updatedNumber
  }

  showFlashMessage({ data }) {
    const flashBox = document.getElementById('message-flash')
    const shopCat = data.product.shopSubCategory.shopCategory.name
    const shopSubCat = data.product.shopSubCategory.name
    const artwork = data.product.artwork.name
    const product = shopCat + ' - ' + shopSubCat + ' - ' + artwork
    flashBox.innerHTML = `Le produit <span class="bold">${product}</span> a été ajouté au panier.`
    flashBox.style.display = 'block'

    setTimeout(() => {
      flashBox.style.display = 'none'
    }, 5 * 1000)
  }
}
