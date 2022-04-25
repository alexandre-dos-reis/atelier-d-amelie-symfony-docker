import QuantityCounter from './elements/QuantityCounter'
import BigPicture from './elements/BigPicture'
import AddProduct from './elements/AddProduct'
import VisitTimer from './elements/VisitTimer'

customElements.define('quantity-counter', QuantityCounter)
customElements.define('big-picture', BigPicture)
customElements.define('add-product', AddProduct)
customElements.define('visit-timer', VisitTimer)

// Gallery : MagicGrid + Big Picture
import './functions/magicGrid'

// Load React components
import './components/ReactPlug'

// Check if CGV were accepted
import './functions/allowPaymentLinkClickable'

// Manage the form when billing address is choosen
import './functions/manageDeliveryAddress'

// Stripe API
import './functions/payWithStripe'
