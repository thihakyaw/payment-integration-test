<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Payment Integration Test</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body class="antialiased">
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-4">
                <div class="card">
                    <img src="/assets/img/wh-1000xm4.jpeg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title" id='description'>Sony WH-1000XM4</h5>
                        <div class="mb-3 pt-2">
                            <label for="amount">Set your price (Starting from 349$)</label><br>
                            <small><strong>*this is for test purpose to adjust the price</strong></small>
                            <input type="number" class="form-control" id="amount" required min=349 value=349>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="name" placeholder="Name" required>
                        </div>

                        <div class="mb-3">
                            <input type="email" class="form-control" id="email" placeholder="Email" required>
                        </div>

                        <button id="checkout-button" class="btn btn-primary btn-md mt-3 float-end">
                            Checkout
                        </button>
                    </div>
                </div>

                <div class="spinner-border mt-4" id="make-payment-spinner" role="status" style="display:none;">
                    <span class="visually-hidden float-center">Loading...</span>
                </div>

                <div class="mt-3" id="make-payment" style="display:none;">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="card-holder-name" placeholder="Name on card">
                    </div>
                    <!-- Stripe Elements Placeholder -->
                    <div id="card-element" class="mt-2"></div>

                    <button id="card-button" class="btn btn-primary btn-sm mt-3 float-end">
                        Make Payment
                    </button>
                </div>
            </div>
            <div class="col-lg-8 mt-2">
                <div class="spinner-border" id="payment-summary-spinner" role="status" style="display:none;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="card" id="payment-summary" style="display:none;">
                    <div class="card-body">
                        <h5 class="card-title">Payment Summary</h5>
                        <div class="row">
                            <div class="col-lg-6">
                                <h6 class="pt-4">Description</h6>
                                <p id='product-summary-description'></p>
                            </div>

                            <div class="col-lg-6">
                                <h6 class="pt-4">Amount</h6>
                                <p id='product-summary-amount'></p>
                            </div>

                            <div class="col-lg-6">
                                <h6 class="pt-4">Payment Status</h6>
                                <span class="badge rounded-pill" id='product-summary-status'>Paid</span>
                            </div>

                            <div class="col-lg-6">
                                <h6 class="pt-4">Name On Card</h6>
                                <p id='product-summary-name-on-card'></p>
                            </div>

                            <div class="col-lg-6">
                                <h6 class="pt-4">Card Brand</h6>
                                <p id='product-summary-card-brand'></p>
                            </div>

                            <div class="col-lg-6">
                                <h6 class="pt-4">Card Last Four Number</h6>
                                <p id='product-summary-card-last-four-number'></p>
                            </div>

                            <div class="col-lg-6">
                                <h6 class="pt-4">Card Expire At</h6>
                                <p id='product-summary-card-expire-at'></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script src="https://js.stripe.com/v3/"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="/js/app.js"></script>

</html>
