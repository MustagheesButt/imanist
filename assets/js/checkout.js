/*
 * Customization for Checkout page
 * This should be loaded in CheckoutWC (plugin) > Advanced > Scripts > Checkout Header scripts
 * Like this:
 * <script src="https://cdnjs.cloudflare.com/ajax/libs/google-libphonenumber/3.2.29/libphonenumber.js"></script>
 * <script src="/wp-content/themes/imanistudio/assets/js/checkout.js"></script>
**/

const EMAIL_REGEX = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/ // yikes!
const { PhoneNumberUtil } = libphonenumber
const phoneUtil = PhoneNumberUtil.getInstance()

window.onload = () => {
  const emailWrapperEle = document.querySelector('p.cfw-email-input')
  const emailLabel = emailWrapperEle.querySelector('label')
  const emailInput = emailWrapperEle.querySelector('input')
  const phoneEle = document.querySelector('input[name=shipping_phone]')

  emailLabel.innerHTML = "Email or Phone Number"
  emailInput.placeholder = "Email/Phone Number"
  emailInput.type = "text"

  for (let d in emailInput.dataset) {
    if (d.includes('parsley')) {
      delete emailInput.dataset[d]
    }
  }

  emailPhoneErrorHandling(emailWrapperEle)

  magic()
  emailInput.oninput = magic

  function magic() {
    const emailPhoneError = document.querySelector('#email-phone-error')
    emailPhoneError.style.display = 'none'

    let number
    try {
      number = phoneUtil.parseAndKeepRawInput(emailInput.value, 'GB')
      if (phoneUtil.isValidNumber(number)) {
        phoneEle.parentElement.parentElement.parentElement.style.display = 'none'
        phoneEle.required = false
        phoneEle.value = emailInput.value

        // if phone number, create an optional input for billing_email
        emailInput.name = "shipping_phone"
        createOptionalEmailField(emailWrapperEle)
      } else {
        resetPhoneEle()
        emailPhoneError.style.display = 'block'
        emailInput.name = "billing_email"
        destroyOptionalEmail()
      }
    } catch (error) {
      if (!EMAIL_REGEX.test(emailInput.value) && emailInput.value !== '') {
        emailPhoneError.style.display = 'block'
      }
      // if is email then keep phone field visible
      resetPhoneEle()
      destroyOptionalEmail()
      emailInput.name = "billing_email"
    }
  }

  function emailPhoneErrorHandling(emailWrapper) {
    const emailPhoneError = document.createElement('div')
    emailPhoneError.id = 'email-phone-error'
    emailPhoneError.classList.add('parsley-errors-list')
    emailPhoneError.style.marginTop = '10px'
    emailPhoneError.innerHTML = 'Please enter a valid email or phone number (eg +1 123 123 1234).'
    emailPhoneError.style.display = 'none'
    emailWrapper.appendChild(emailPhoneError)
  }

  function resetPhoneEle() {
    phoneEle.parentElement.parentElement.parentElement.style.display = 'block'
    phoneEle.required = true
    phoneEle.value = ''
  }

  function createOptionalEmailField(appendTo) {
    if (document.querySelector('input#optional-email')) return

    const emailEle = document.createElement('input')
    emailEle.name = 'billing_email'
    emailEle.type = 'email'
    emailEle.id = 'optional-email'
    emailEle.placeholder = "Optional Email"
    emailEle.classList.add('mt-3')
    appendTo.appendChild(emailEle)
    // handleDummyEmail()

    // const fNameEle = document.querySelector('input[name=shipping_first_name]')
    // const lNameEle = document.querySelector('input[name=shipping_last_name]')
    // fNameEle.onchange = handleDummyEmail
    // lNameEle.onchange = handleDummyEmail
  }

  function getDummyEmail() {
    const fNameEle = document.querySelector('input[name=shipping_first_name]')
    const lNameEle = document.querySelector('input[name=shipping_last_name]')
    const fName = fNameEle.value.replace(/[^a-zA-Z]/g, "").toLowerCase()
    const lName = lNameEle.value.replace(/[^a-zA-Z]/g, "").toLowerCase()

    let uuid = ''
    if (crypto && crypto.randomUUID) {
      uuid = crypto.randomUUID().split('-')[0]
    }

    return `${fName}.${lName}${uuid}@imanistudio.com`
  }

  function destroyOptionalEmail() {
    document.querySelector('#optional-email')?.remove()
  }

  // on checkout form submit, if billing_email is empty
  const checkoutForm = document.querySelector('form#checkout')
  const acceptTermsBtn = document.querySelector('input[name=terms]')
  acceptTermsBtn.onclick = (e) => {
    const formData = new FormData(checkoutForm)
    if (formData.get('billing_email').length === 0) {
      // tried removing billing_email field, but it doesnt work
      // formData.delete('billing_email')
      // document.querySelector('input[name=billing_email]').remove()

      // use a dummy email
      const de = getDummyEmail()
      const be = document.querySelector('input[name=billing_email]')
      formData.set('billing_email', de)
      be.value = de
    }
  }
}