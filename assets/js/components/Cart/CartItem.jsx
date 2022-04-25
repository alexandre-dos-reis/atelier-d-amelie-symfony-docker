import React from 'react'
import './CartItem.scss'
import ToAmount from '../../functions/ToAmount'

const getImagePath = cartItem => {
  const productFolder = 'products/'
  const firstProductImage = cartItem.product.imageProducts[0]
  if(firstProductImage){
    if (firstProductImage.imageWatermarked) return productFolder + firstProductImage.imageWatermarked
    if (firstProductImage.imageOriginal) return productFolder + firstProductImage.imageOriginal
  }

  const artworkFolder = 'artworks/'
  const watermarkedArtworkImage = cartItem.product.artwork.imageWatermarked
  if (watermarkedArtworkImage) return artworkFolder + watermarkedArtworkImage

  console.log(cartItem.product.artwork.imageWatermarked)

  const originalArtworkImage = cartItem.product.artwork.imageOriginal
  if (originalArtworkImage) return artworkFolder + originalArtworkImage
}

export default function CartItem({ cartItem, deleteCartItem, handleNewQty }) {
  const id = cartItem.product.id
  const stock = cartItem.product.stock
  const qty = cartItem.qty
  const price = cartItem.product.price
  const artwork = cartItem.product.artwork
  const shopCategory = cartItem.product.shopSubCategory.shopCategory
  const shopSubCategory = cartItem.product.shopSubCategory
  const cacheImageBasePath = '/media/cache/boutique_mini/uploads/'
  const imagePath = getImagePath(cartItem)
  const link = `/boutique/${shopCategory.slug}/${shopSubCategory.slug}/${artwork.slug}_${id}`
  return (
    <div className="cartItem">
      <img src={cacheImageBasePath + imagePath} />
      <div className="category">
        {shopCategory.name} - {shopSubCategory.name}
      </div>
      <div className="name">{artwork.name}</div>
      <div className="price">{ToAmount(price)}</div>
      <div className="qty-group">
        <button
          onClick={() => handleNewQty(id, qty - 1)}
          disabled={qty <= 1 ? true : false}
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            fill="currentColor"
            viewBox="0 0 16 16"
          >
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
            <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z" />
          </svg>
        </button>

        <div>{qty}</div>
        <button
          onClick={() => handleNewQty(id, qty + 1)}
          disabled={qty >= stock ? true : false}
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            fill="currentColor"
            viewBox="0 0 16 16"
          >
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
          </svg>
        </button>
      </div>
      <div className="sub-total">{ToAmount(price * qty)}</div>
      <div className="show-product">
        <a href={link}>Voir l'article</a>
      </div>
      <div className="delete-cart-item" onClick={() => deleteCartItem(id)}>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          fill="currentColor"
          viewBox="0 0 16 16"
        >
          <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
          <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
        </svg>
      </div>
    </div>
  )
}
