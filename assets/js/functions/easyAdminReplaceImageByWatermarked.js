// window.addEventListener('load', e => {
//   const img = document.querySelector('a.ea-lightbox-thumbnail').children.item(0)
//   if (img) {
//     const originalSrc = img.src
//     const arraySrc = originalSrc.split('/')
//     const watermarkedName = 'watermarked-' + arraySrc.at(-1)
//     arraySrc[arraySrc.length - 1] = watermarkedName
//     img.src = arraySrc.join('/')

//     if (!img.complete && img.naturalHeight !== 0) {
//       img.src = originalSrc
//     }
//   }
// })
