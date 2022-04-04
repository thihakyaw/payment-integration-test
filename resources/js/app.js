require('./bootstrap');

const stripe = Stripe(process.env.MIX_STRIPE_KEY);

const elements = stripe.elements();
const cardElement = elements.create('card');

cardElement.mount('#card-element');

let checkoutButton = document.getElementById('checkout-button');
let amount;
let clientSecret;

let makePayment = document.getElementById("make-payment");
let makePaymentSpinner = document.getElementById("make-payment-spinner");
let cardButton = document.getElementById('card-button');
let cardHolderName = document.getElementById('card-holder-name');

let paymentSummary = document.getElementById("payment-summary");
let paymentSummarySpinner = document.getElementById("payment-summary-spinner");

checkoutButton.addEventListener('click', async (e) => {
    checkoutButton.disabled = true;
    document.getElementById("amount").disabled = true;
    document.getElementById("name").disabled = true;
    document.getElementById("email").disabled = true;

    if (document.getElementById('name').value == '' || 
    document.getElementById('email').value == ''
    ) {
        alert('Please fill your name and email before checkout.');
        return;
    }

    makePaymentSpinner.style.display = "block";

    let response = await axios.post('/api/v1/payments', {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value
    });
    clientSecret = response.data.data.intent;
    amount = document.getElementById('amount').value;

    makePaymentSpinner.style.display = "none";
    makePayment.style.display = "block";
});

cardButton.addEventListener('click', async (e) => {
    cardButton.disabled = true;
    cardHolderName.disabled = true;

    if (cardHolderName.value == '') {
        alert('Please fill your name and card information before making payment.');
        return;
    }
    paymentSummarySpinner.style.display = "block";

    const { setupIntent, error } = await stripe.confirmCardSetup(
        clientSecret, {
            payment_method: {
                card: cardElement,
                billing_details: { name: cardHolderName.value }
            }
        }
    );
 
    if (error) {
        paymentSummarySpinner.style.display = "none";
        alert('Something went wrong. Please check your card information.');
        cardButton.disabled = false;
        cardHolderName.disabled = false;
        return;
    } 

    let makePayment = await axios.put('/api/v1/payments', {
        name_on_card: document.getElementById('card-holder-name').value,
        email: document.getElementById('email').value,
        payment_method: setupIntent.payment_method,
        amount: amount * 100,
        description: document.getElementById('description').innerHTML
    });

    let chargeInformation = makePayment.data.data.card.stripe_charge_id;

    let paymentInformation = await axios.get('/api/v1/payments/'+ chargeInformation);

    paymentInformation = paymentInformation.data.data.payment;

    document.getElementById('product-summary-description').innerHTML = paymentInformation.description;
    document.getElementById('product-summary-amount').innerHTML = paymentInformation.amount + '$';
    document.getElementById('product-summary-name-on-card').innerHTML = paymentInformation.name_on_card;
    document.getElementById('product-summary-card-brand').innerHTML = paymentInformation.card_brand;
    document.getElementById('product-summary-card-last-four-number').innerHTML = paymentInformation.card_last_four_number;
    document.getElementById('product-summary-card-expire-at').innerHTML = paymentInformation.card_expiry_month +'/'+ paymentInformation.card_expiry_year;

    if (paymentInformation.paid) {
        document.getElementById("product-summary-status").innerHTML = "Paid";
        document.getElementById("product-summary-status").classList.add('bg-success');
    }
    else {
        document.getElementById("product-summary-status").innerHTML = "Payment Failed";
        document.getElementById("product-summary-status").classList.add('bg-danger');
    }

    paymentSummarySpinner.style.display = "none";
    paymentSummary.style.display = "block";
});