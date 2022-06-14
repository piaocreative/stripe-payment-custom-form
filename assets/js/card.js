var stripeKey = document.querySelector('#payment-box').dataset.consumerKey;
var createOrderUrl = document.querySelector('#payment-box').dataset.createOrderUrl;
var returnUrl = document.querySelector('#payment-box').dataset.returnUrl;
var stripe = Stripe(stripeKey);
var elements = stripe.elements();
var style = {
	base: {
		color: "#32325d",
		fontFamily: 'Arial, sans-serif',
		fontSmoothing: "antialiased",
		fontSize: "16px",
		"::placeholder": {
			color: "#32325d"
		}
	},
	invalid: {
		fontFamily: 'Arial, sans-serif',
		color: "#fa755a",
		iconColor: "#fa755a"
	}
};
var card = elements.create("card", {
	hidePostalCode: true,
	style: style
});
// Stripe injects an iframe into the DOM
card.mount("#card-element");
card
	.on(
		"change",
		function(event) {
			// Disable the Pay button if there are no card details in
			// the Element
			document.querySelector("button").disabled = event.empty;
			document.querySelector("#card-error").textContent = event.error ? event.error.message
				: "";
		});

function confirmOrder(event) {
	var valid = formValidate();
	if (valid) {
		var purchase = {
			email: document.getElementById("email").value,
			notes: document.getElementById("notes").value,
			unitPrice: document.getElementById("price").value,
			currency: document.getElementById("currency").value,
			name: document.getElementById("customer_name").value,
			address: document.getElementById("address").value,
			country: document.getElementById("country").value,
			postalCode: document.getElementById("postal_code").value
		};

		// Disable the button until we have Stripe set up on the page
		// document.querySelector("btnSubmit").disabled = true;
		fetch(createOrderUrl, {
			method: "POST",
			headers: {
				"Content-Type": "application/json",

			},
			body: JSON.stringify(purchase)
		}).then(function(result) {
			return result.json();
		}).then(function(data) {
			// Complete payment when the submit button is clicked
			payWithCard(stripe, card, data.clientSecret, data.orderHash);
		});
		// Calls stripe.confirmCardPayment
		// If the card requires authentication Stripe shows a pop-up modal
		// to
		// prompt the user to enter authentication details without leaving
		// your
		// page.
		var payWithCard = function(stripe, card, clientSecret, orderHash) {
			loading(true);
			stripe.confirmCardPayment(clientSecret, {
				payment_method: {
					card: card
				}
			}).then(function(result) {
				if (result.error) {
					// Show error to your customer
					showError(result.error.message);
				} else {
					// The payment succeeded!
					orderComplete(result.paymentIntent.id, orderHash);
				}
			});
		};
		/* ------- UI helpers ------- */
		// Shows a success message when the payment is complete
		var orderComplete = function(paymentIntentId, orderHash) {
			loading(false);
			window.location.href = returnUrl + "?orderId=" + orderHash;
		};
		// Show the customer the error from Stripe if their card fails to
		// charge
		var showError = function(errorMsgText) {
			loading(false);
			var errorMsg = document.querySelector("#card-error");
			errorMsg.textContent = errorMsgText;
			setTimeout(function() {
				errorMsg.textContent = "";
			}, 10000);
		};
		// Show a spinner on payment submission
		var loading = function(isLoading) {
			if (isLoading) {
				// Disable the button and show a spinner
				document.querySelector("button").disabled = true;
				document.querySelector("#spinner").classList.remove("hidden");
				document.querySelector("#button-text").classList.add("hidden");
			} else {
				document.querySelector("button").disabled = false;
				document.querySelector("#spinner").classList.add("hidden");
				document.querySelector("#button-text").classList
					.remove("hidden");
			}

		};
	}
}

function formValidate() {
	var valid = true;
	var email = document.getElementById("email").value;
	var unitPrice = document.getElementById("price").value;
	var emailField = document.getElementById("email");
	var priceField = document.getElementById("price");
	var currencyField = document.getElementById("currency");

	var nameField = document.getElementById("customer_name");
	var countryField = document.getElementById("country");
	var postalField = document.getElementById("postal_code");
	var addressField = document.getElementById("address");
	var notesField = document.getElementById("notes");

	var allInput = document.querySelectorAll(".input-box");
	for (i = 0; i < allInput.length; i++) {
		allInput[i].classList.remove('error-field');
	}

	var allErrorSpan = document.querySelectorAll(".error-msg");
	for (i = 0; i < allErrorSpan.length; i++) {
		allErrorSpan[i].innerText = "";
	}

	var currencyRegex = /^(?!0\.00)[1-9]\d{0,2}(,\d{3})*(\.\d\d)?$/;
	var emailRegex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (unitPrice.trim() == "") {
		priceField.classList.add("error-field");
		document.getElementById("price-error").innerText = "required.";
	} else if (!currencyRegex.test(unitPrice)) {
		priceField.classList.add("error-field");
		document.getElementById("price-error").innerText = "invalid.";
		valid = false;
	}
	if (nameField.value.trim() == "") {
		nameField.classList.add("error-field");
		document.getElementById("name-error").innerText = "required.";
	}
	if (email.trim() == "") {
		emailField.classList.add("error-field");
		document.getElementById("email-error").innerText = "required.";
	} else if (!emailRegex.test(email)) {
		emailField.classList.add("error-field");
		document.getElementById("email-error").innerText = "invalid.";
		valid = false;
	}
	if (addressField.value.trim() == "") {
		addressField.classList.add("error-field");
		document.getElementById("address-error").innerText = "required.";
	}
	if (countryField.value.trim() == "") {
		countryField.classList.add("error-field");
		document.getElementById("country-error").innerText = "required.";
	}
	if (postalField.value.trim() == "") {
		postalField.classList.add("error-field");
		document.getElementById("postal-error").innerText = "required.";
	}
	if (notesField.value.trim() == "") {
		notesField.classList.add("error-field");
		document.getElementById("notes-error").innerText = "required.";
	}
	if (currencyField.value.trim() == "") {
		currencyField.classList.add("error-field");
		document.getElementById("currency-error").innerText = "required.";
	}
	if ((email == "") || unitPrice == "" || currencyField == ""
		|| nameField.value == "" || countryField.value == ""
		|| notesField.value == "" || addressField.value == ""
		|| postalField.value == "") {
		var errorElement = document.getElementsByClassName("error-field");
		errorElement[0].focus();
		valid = false;
	}

	return valid;
}