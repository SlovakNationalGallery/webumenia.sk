window.addEventListener('touchstart', function onFirstTouch() {
  document.body.classList.add('user-using-touch');
  window.removeEventListener('touchstart', onFirstTouch, false);
}, false);