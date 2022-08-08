// fix filter on scroll
const filterEle = document.querySelector('.category-filtering').parentElement

function fixFilters(reset = false) {
  if (!filterEle) return
  if (reset)
    filterEle.classList.remove('flying-f')
  else
    filterEle.classList.add('flying-f')
}

window.onscroll = function () {
  // if (window.scrollY > 100)
  if (filterEle.getBoundingClientRect().y <= 80)
    fixFilters()
  else
    fixFilters(true)
}

// function for instock hack
const instockPopup = (e) => {
  e.target.parentElement.parentElement.parentElement.parentElement.querySelector('.cwg_popup_submit').click()
}

document.addEventListener('click', (e) => {
  if (e.target.classList.contains('notify'))
    instockPopup(e)
})