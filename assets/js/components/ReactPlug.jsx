import Cart from './Cart/Cart'
import ImageSlider from './ImageSlider/ImageSlider'
import { render } from 'react-dom'
import React from 'react'

document.addEventListener('turbo:load', () => {
  const cart = document.getElementById('react-cart')
  const slider = document.getElementById('react-image-slider')

  if (cart) render(<Cart />, cart)
  if (slider) render(<ImageSlider />, slider)
})
