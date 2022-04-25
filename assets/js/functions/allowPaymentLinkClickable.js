function allowPaymentLinkClickable() {
  const checkbox = document.getElementById('js-accept-cgv')
  const stripeLink = document.getElementById('js-stripe-pay-link')

  if (checkbox && stripeLink) {
    const stripeUrl = stripeLink.getAttribute('payment-url')
    const originalPaymentUrl = stripeLink.href

    checkbox.addEventListener('click', () => {
      if (checkbox.checked) {
        stripeLink.href = stripeUrl
      } else {
        stripeLink.href = originalPaymentUrl
      }
    })
  }
}

document.addEventListener('turbo:load', () => {
  allowPaymentLinkClickable()
})
