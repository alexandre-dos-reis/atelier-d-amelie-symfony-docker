export default class ModalLink extends HTMLElement {
    constructor() {
        super()

        this.root = this.attachShadow({mode: 'open'})
        this.modal = this.children.namedItem('modal')
        this.closeBtn = document.getElementById('js-close-modal')
        this.isModalOpen = false

        this.openModal = this.openModal.bind(this)
        this.closeModal = this.closeModal.bind(this)
        this.handleKeys = this.handleKeys.bind(this)
    }

    connectedCallback() {
        this.addEventListener('click', this.openModal)
        this.closeBtn.addEventListener('click', this.closeModal)
        window.addEventListener('keydown', this.handleKeys)
    }

    disconnectedCallback() {
        this.removeEventListener('click', this.openModal)
        this.closeBtn.removeEventListener('click', this.closeModal)
        window.removeEventListener('keydown', this.handleKeys)
    }

    handleKeys(e) {
        if (this.isModalOpen === true && (e.key === 'Escape' || e.key === 'Esc')) {
            this.closeModal(e)
        }
    }

    openModal(e) {
        e.preventDefault()
        this.isModalOpen = true
        this.modal.style.display = 'flex'
    }

    closeModal(e) {
        e.preventDefault()
        this.isModalOpen = false
        this.modal.style.display = 'none'
    }
}