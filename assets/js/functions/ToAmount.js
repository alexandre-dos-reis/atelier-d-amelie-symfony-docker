export default function ToAmount(unformatedAmount) {
        return new Intl
                .NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' })
                .format(unformatedAmount / 100)
}