import React, { useState, useEffect } from 'react'
import axios from 'axios'
import CartItem from './CartItem'
import './Cart.scss'
import ToAmount from '../../functions/ToAmount'

export default function Cart() {
  const [isModalOpen, setIsModalOpen] = useState(false)
  const [cartItems, setCartItems] = useState(null)
  const [total, setTotal] = useState(0)
  const [outputUrl, setOutputUrl] = useState(null)
  const [shopLinks, setShopLinks] = useState([])

  const handleModal = () => setIsModalOpen(value => !value)

  const handleCloseModal = () => {
    if (total === 0 && outputUrl !== null) {
      handleModal()
      window.location.href = '/boutique/oeuvres-originales'
    }
    handleModal()
  }

  const countProductSpan = document.getElementById('countProducts')
  // TODO : Ajouter un loader

  // Run initialy
  useEffect(() => {
    const links = Array.from(document.querySelectorAll('.js-open-modal-cart'))
    links.forEach(link => {
      link.addEventListener('click', e => {
        handleModal()
        setOutputUrl(e.target.getAttribute('validate-url'))
      })
    })

    setShopLinks(
      Array.from(document.getElementById('js-shop-cats-list-for-cart').children)
    )

    return () => {
      links.forEach(link =>
        link.removeEventListener('click', e => {
          handleModal()
          setOutputUrl(e.target.getAttribute('validate-url'))
        })
      )
    }
  }, [])

  // Call the cart API.
  useEffect(() => {
    axios.get('/panier').then(res => {
      setCartItems(res.data.detailedCartItems)
    })
  }, [isModalOpen])

  useEffect(() => {
    if (cartItems !== null && cartItems.length !== 0) {
      // Update total
      const newTotal = cartItems
        .map(item => item.qty * item.product.price)
        .reduce((previousValue, currentValue) => previousValue + currentValue)
      setTotal(newTotal)

      // Update span countProducts
      const newCountProducts = cartItems
        .map(item => item.qty)
        .reduce((previousValue, currentValue) => previousValue + currentValue)
      countProductSpan.textContent = newCountProducts
    } else {
      setTotal(0)
    }
  }, [cartItems])

  // Handle new quantity for each cart item.
  const handleNewQty = (id, newQty) => {
    const oldCartItems = [...cartItems]
    const [cartItemToUpdate] = oldCartItems.filter(
      item => item.product.id === id
    )
    cartItemToUpdate.qty = newQty
    axios.post(`/panier/${id}/${newQty}`)
    setCartItems(oldCartItems)
  }

  // Delete item and send info to server.
  const deleteCartItem = async id => {
    const newCartItems = cartItems.filter(item => item.product.id !== id)

    const { data } = await axios.post(`/panier/supprimer/${id}`)
    // gérer les erreurs ici...
    const countProducts = document.getElementById('countProducts')
    if (data.numberOfProducts === 0) {
      countProducts.remove()
    } else {
      countProducts.textContent = data.numberOfProducts
    }
    setCartItems(newCartItems)
  }

  return (
    <>
      {isModalOpen && (
        <div id="react-cart-modal" onClick={handleModal}>
          <div className="modal-wrapper" onClick={e => e.stopPropagation()}>
            <button
              className="close-button"
              onClick={handleCloseModal}
              id="js-close-modal"
            >
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
            </button>
            {total !== 0 && (
              <h2>
                <span className="lettrine lettrine-title">P</span>anier
              </h2>
            )}
            <div id="js-cart-container">
              {cartItems !== null &&
                cartItems.map(cartItem => (
                  <CartItem
                    key={cartItem.product.id}
                    cartItem={cartItem}
                    deleteCartItem={deleteCartItem}
                    handleNewQty={handleNewQty}
                  />
                ))}
              {cartItems === null ||
                (cartItems.length === 0 && (
                  <div className="empty-cart">
                    <h2>Votre panier est vide !</h2>
                    <p>
                      Rendez-vous dans la boutique pour sélectionner des
                      articles :
                    </p>
                    <div className="shop-links">
                      <ul>
                        {shopLinks.map((l, i) => (
                          <li key={i}>
                            <a style={{ cursor: 'pointer' }} href={l.href}>
                              {l.innerHTML}
                            </a>
                          </li>
                        ))}
                      </ul>
                    </div>
                  </div>
                ))}
            </div>
            {total !== 0 && (
              <>
                <div className="total">
                  <div>Total</div>
                  <div>{ToAmount(total)}</div>
                </div>
                <div className="proceedToPaymentOrClose">
                  <a className='pay-button continue' href={shopLinks[0].href}>Continuer mes achats</a>
                  <a className='pay-button' href={outputUrl ?? '/commande/informations'}>
                    Procéder au paiement
                  </a>
                </div>
              </>
            )}
          </div>
        </div>
      )}
    </>
  )
}
