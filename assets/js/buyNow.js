window.onload = () => {
  const addToCartBtn = document.querySelector('button.single_add_to_cart_button')
  const variationId = document.querySelector('input.variation_id')
  const quantityInput = document.querySelector('input.qty')
  const self = document.querySelector('a.buy_now')

  self.onclick = (e) => {
    if (self.classList.contains('disabled'))
      e.preventDefault()
  }

  const mObserver = new MutationObserver((mutationList, obs) => {
    for (const mutation of mutationList) {
      if (mutation.type === 'attributes') {
        syncWithAddToCartBtn()
      }
    }
  })
  mObserver.observe(addToCartBtn, { attributes: true })

  const syncWithAddToCartBtn = () => {
    if (addToCartBtn.classList.contains('disabled')) {
      self.classList.add('disabled')
    } else {
      self.classList.remove('disabled')
    }
  }
  syncWithAddToCartBtn()

  if (variationId) {
    variationId.onchange = () => {
      const url = new URL(self.href)
      url.searchParams.set('add-to-cart', variationId.value)
      self.href = url

      // update for ajax add to cart
      addToCartBtn.setAttribute('data-product_id', variationId.value)
    }
  }

  // update for ajax add to cart
  quantityInput.onchange = () => {
    addToCartBtn.setAttribute('data-quantity', quantityInput.value)
  }

  // disable out of stock/sold out variations
  const variations = JSON.parse(document.querySelector('form.variations_form').dataset.product_variations)
  variations.forEach(variation => {
    if (!variation.is_in_stock) {
      const attrNames = Object.keys(variation.attributes)
      const lastAttr = attrNames[attrNames.length - 1]
      const ele = document.querySelector(`div.ux-swatches[data-attribute_name=${lastAttr}] > div[data-value=${variation.attributes[lastAttr]}]`)
      ele.classList.add('out-of-stock')
    }
  })

  // select the first in-stock variation
  const attrs = document.querySelectorAll('table.variations td.value')
  attrs.forEach(attr => {
    const firstInStockSwatch = attr.querySelector('div.ux-swatch:not(.out-of-stock)')
    firstInStockSwatch?.click()
  })

  // fix add-to-cart-container
  const options = {
    root: null, // use default (browser viewport)
    rootMargin: '0px',
    threshold: 1.0
  }

  function fixAddToCartContainer(entries, observer) {
    const cartContainer = document.querySelector('.add-to-cart-container')
    entries.forEach((entry) => {
      if (!entry.isIntersecting) {
        cartContainer.classList.add('fix')
      } else {
        cartContainer.classList.remove('fix')
      }
    })
  }
  
  const iObserver = new IntersectionObserver(fixAddToCartContainer, options)
  const target = document.querySelector('div.quantity')

  iObserver.observe(target)

  // update main price on variation selected
  const priceEle = document.querySelector('.price-wrapper .product-page-price')
  const variationPriceContainer = document.querySelector('.single_variation_wrap')

  const pObserver = new MutationObserver((mutationList, obs) => {
    for (const mutation of mutationList) {
      if (mutation.type === 'childList') {
        priceEle.innerHTML = variationPriceContainer.querySelector('.woocommerce-variation-price').innerHTML
      }
    }
  })
  pObserver.observe(variationPriceContainer, { childList: true, subtree: true })
}