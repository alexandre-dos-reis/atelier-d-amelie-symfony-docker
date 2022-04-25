// https://github.com/henrygd/bigpicture
import BigPicture from 'bigpicture'

export default class BigPictureLightBox extends HTMLElement {

    constructor() {
        super()
        this.launch = this.launch.bind(this)
    }

    connectedCallback() {
        this.addEventListener('click', this.launch)
    }

    disconnectedCallback() {
        this.removeEventListener('click', this.launch)
    }

    launch(e) {
        e.preventDefault()
        BigPicture({
            el: e.target,
            gallery: `#${this.id}`,
            noLoader: true,
        })
    }
}