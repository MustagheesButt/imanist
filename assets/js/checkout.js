/*
 * Customization for Checkout page
 * This should be loaded in CheckoutWC (plugin) > Advanced > Scripts > Checkout Header scripts
 * Like this:
 * <script src="https://cdnjs.cloudflare.com/ajax/libs/google-libphonenumber/3.2.29/libphonenumber.js"></script>
 * <script src="/wp-content/themes/imanistudio/assets/js/checkout.js"></script>
**/

window.onload = () => {
  const shippingPhoneInput = document.querySelector('input[name=shipping_phone]')
  const toCopyAbove = shippingPhoneInput.parentElement.parentElement.parentElement.parentElement.cloneNode(true)
  
  const copyBefore = document.querySelector('#billing_email_field')
  
  const toCopyAboveInput = toCopyAbove.querySelector('input')
  toCopyAboveInput.name = 'billing_phone'
  toCopyAboveInput.id = 'billing_phone'
  toCopyAboveInput.removeAttribute('required')
  toCopyAboveInput.setAttribute('data-parsley-required', 'false')

  toCopyAboveInput.oninput = () => {
    if (shippingPhoneInput.value.length !== 0)
      shippingPhoneInput.value =  toCopyAboveInput.value
  }
  
  copyBefore.insertAdjacentElement('beforebegin', toCopyAbove)
}