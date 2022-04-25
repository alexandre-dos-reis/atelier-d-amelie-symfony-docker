export default class QuantityCounter extends HTMLElement {
  constructor() {
    super()

    this.add = this.add.bind(this)
    this.remove = this.remove.bind(this)

    this.max = this.getAttribute('max')
    this.value = this.getAttribute('value')

    this.decrement = document.createElement('button')
    this.input = document.createElement('input')
    this.increment = document.createElement('button')

    this.decrement.innerHTML = '-'
    this.increment.innerHTML = '+'
    this.increment.classList.add('increment')
    this.decrement.classList.add('decrement')
    this.input.value = this.value
    this.input.setAttribute('name', this.getAttribute('name'))
    this.input.setAttribute('type', 'text')

    this.appendChild(this.decrement)
    this.appendChild(this.input)
    this.appendChild(this.increment)

    if (parseInt(this.value) === 1) {
      this.decrement.setAttribute('disabled', 'disabled')
    }

    if (parseInt(this.max) <= 0) {
      this.input.value = this.value = 0
      this.decrement.setAttribute('disabled', 'disabled')
      this.increment.setAttribute('disabled', 'disabled')
    }

    if (parseInt(this.max) === 1) {
        this.increment.setAttribute('disabled', 'disabled')
        this.decrement.setAttribute('disabled', 'disabled')
      }
  }

  connectedCallback() {
    this.decrement.addEventListener('click', this.remove)
    this.increment.addEventListener('click', this.add)
  }

  disconnectedCallback() {
    this.decrement.removeEventListener('click', this.remove)
    this.increment.removeEventListener('click', this.add)
  }

  add(e) {
    e.preventDefault()
    this.input.value++
    if (this.input.value >= this.max) {
      this.input.value = this.max
      this.increment.setAttribute('disabled', 'disabled')
    }
    if (this.decrement.hasAttribute('disabled')) {
      this.decrement.removeAttribute('disabled')
    }
    this.setAttribute('value', this.input.value)
  }

  remove(e) {
    e.preventDefault()
    this.input.value--
    if (parseInt(this.input.value) <= 1) {
      this.input.value = 1
      this.decrement.setAttribute('disabled', 'disabled')
    }
    if (this.increment.hasAttribute('disabled')) {
      this.increment.removeAttribute('disabled')
    }
    this.setAttribute('value', this.input.value)
  }
}
