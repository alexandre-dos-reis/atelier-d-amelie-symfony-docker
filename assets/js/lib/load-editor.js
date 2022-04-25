import FilerobotImageEditor from 'filerobot-image-editor'
import axios from 'axios'

export async function loadEditor(
  sourceImageUrl,
  editorContainerId,
  loadUrl,
  saveUrl
) {
  const config = {
    source: sourceImageUrl,
    onSave: (editedImageObject, designState) => {
      axios.post(saveUrl, {
        editedImageObject,
        designState
      })
    },
    defaultSavedImageType: "jpeg",
    loadableDesignState: (await axios.get(loadUrl)).data,
    annotationsCommon: {
      fill: '#ff0000'
    },
    Text: { text: 'Filerobot...' },
    translations: {
      profile: 'Profil',
      coverPhoto: 'Cover photo',
      facebook: 'Facebook',
      socialMedia: 'Social Media',
      fbProfileSize: '180x180px',
      fbCoverPhotoSize: '820x312px'
    },
    Watermark: {
      gallery: [
        "static/watermark/3.png"
      ]
    },
    Crop: {
      presetsItems: [
        {
          titleKey: 'classicTv',
          descriptionKey: '4:3',
          ratio: 4 / 3
          // icon: CropClassicTv, // optional, CropClassicTv is a React Function component. Possible (React Function component, string or HTML Element)
        },
        {
          titleKey: 'cinemascope',
          descriptionKey: '21:9',
          ratio: 21 / 9
          // icon: CropCinemaScope, // optional, CropCinemaScope is a React Function component.  Possible (React Function component, string or HTML Element)
        }
      ],
      presetsFolders: [
        {
          titleKey: 'socialMedia', // will be translated into Social Media as backend contains this translation key
          // icon: Social, // optional, Social is a React Function component. Possible (React Function component, string or HTML Element)
          groups: [
            {
              titleKey: 'facebook',
              items: [
                {
                  titleKey: 'profile',
                  width: 180,
                  height: 180,
                  descriptionKey: 'fbProfileSize'
                },
                {
                  titleKey: 'coverPhoto',
                  width: 820,
                  height: 312,
                  descriptionKey: 'fbCoverPhotoSize'
                }
              ]
            }
          ]
        }
      ]
    },
    tabsIds: [
      FilerobotImageEditor.TABS.ADJUST,
      FilerobotImageEditor.TABS.WATERMARK
    ],
    defaultTabId: FilerobotImageEditor.TABS.ADJUST,
    defaultToolId: FilerobotImageEditor.TOOLS.TEXT
  }

  const filerobotImageEditor = new FilerobotImageEditor(
    editorContainerId,
    config
  )

  filerobotImageEditor.render()
}
