function manageDeliveryAddress() {
  const checkbox = document.getElementById('purchase_address_hasBillingAddress')

  if (checkbox) {
    checkbox.addEventListener('click', e => {
      const secondAddress = document.querySelector('.second-address')

      if (e.target.checked) {
        secondAddress.style.display = 'flex'
      } else {
        secondAddress.style.display = 'none'
      }
    })
  }
}

document.addEventListener('turbo:load', () => {
  manageDeliveryAddress()
})
document.addEventListener('turbo:render', () => {
  manageDeliveryAddress()
})
