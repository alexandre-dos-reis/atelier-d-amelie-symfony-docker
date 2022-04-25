import ImageGallery from 'react-image-gallery'
import './ImageSlider.scss'
import React, { useEffect, useState } from 'react'
import axios from 'axios'

// product-id-5090

export default function ImageSlider() {
  const [images, setImages] = useState([])

  useEffect(() => {
    const { url, id } = document.getElementById('product').dataset
    axios.get(url + id).then(res => setImages(res.data))
  }, [])

  return (
    <ImageGallery
      items={images}
      showPlayButton={false}
      useBrowserFullscreen={false}
      showFullscreenButton={false}
      thumbnailPosition="left"
      showNav={false}
    />
  )
}
