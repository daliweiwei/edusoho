import LocalImageCrop from  'app/common/local-image/crop';

new LocalImageCrop({
  cropImg: '#avatar-crop',
  saveBtn: '#save-btn',
  selectBtn: '#select-btn',
  group: 'user',
  imgs: {
    large: [400, 400],
    medium: [120, 120],
    small: [48, 48]
  }
});
