const anchors = Array.from(document.querySelectorAll('.ea-vich-file-name'))

anchors.forEach(a => {
  const img = document.createElement('img')
  img.src = a.href
  img.style.width = '200px'
  img.style.height = 'auto'
  a.parentNode.insertBefore(img, a)
  a.textContent = ''
})
