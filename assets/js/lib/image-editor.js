import { loadEditor } from './load-editor'

const sourceImage = document.getElementById('image-source')

if (sourceImage) {
  const imageEditorElementId = document.getElementById('editor-container')
  // We need to adapt the height container for the Image Editor
  const imageEditorOffsetFromTop = Math.round(
    imageEditorElementId.getBoundingClientRect().top
  )
  const imageEditorHeight = window.innerHeight - imageEditorOffsetFromTop
  imageEditorElementId.style.height = `${imageEditorHeight}px`
  //

  const loadUrl = sourceImage.dataset.loadUrl
  const saveUrl = sourceImage.dataset.saveUrl
  const sourceImageUrl = sourceImage.dataset.imageSrc

  loadEditor(sourceImageUrl, imageEditorElementId, loadUrl, saveUrl)
}
