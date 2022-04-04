<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\MakePaymentRequest;
use App\Http\Requests\SetUpIntentRequest;
use App\Models\Buyer;
use App\Models\Card;
use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaymentApiController extends ApiController
{
    /**
     * Create a setup intent
     *
     * @param  SetUpIntentRequest  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(SetUpIntentRequest $request)
    {
        $data = $request->validated();

        $buyer = Buyer::firstOrCreate(
            ['email' => data_get($data, 'email')],
            [
                'name' => data_get($data, 'name'),
                'email' => data_get($data, 'email')
            ]
        );

        $buyer->createOrGetStripeCustomer();

        return $this->respondCreated(
            ['intent' => $buyer->createSetupIntent()->client_secret],
            'User setup intent created');
    }

    /**
     * Make payment
     *
     * @param  MakePaymentRequest  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(MakePaymentRequest $request)
    {
        $data = $request->validated();

        try {
            $buyer = Buyer::where('email', data_get($data, 'email'))->firstOrFail();
        }
        catch(ModelNotFoundException $e) {
            return $this->respondNotFound('User not found');
        }

        $buyer->createOrGetStripeCustomer(); 
        $buyer->addPaymentMethod(data_get($data, 'payment_method'));

        $stripeCharge = $buyer->charge(
            data_get($data, 'amount'), 
            data_get($data, 'payment_method'),
            ['description' => data_get($data, 'description')]
        )->toArray();
        

        $card = Card::create([
            'buyer_id' => $buyer->id,
            'name_on_card' => data_get($data, 'name_on_card'),
            'card_brand' => data_get($stripeCharge, 'charges.data.0.payment_method_details.card.brand'),
            'card_last_four_number' => data_get($stripeCharge, 'charges.data.0.payment_method_details.card.last4'),
            'card_expiry_month' => data_get($stripeCharge, 'charges.data.0.payment_method_details.card.exp_month'),
            'card_expiry_year' => data_get($stripeCharge, 'charges.data.0.payment_method_details.card.exp_year'),
            'stripe_charge_id' => data_get($stripeCharge, 'charges.data.0.id')
        ]);

        Product::create([
            'card_id' => $card->id,
            'amount' => data_get($data, 'amount'),
            'description' => data_get($data, 'description'),
        ]);

        return $this->respondSuccess(
            ['buyer' => $buyer, 'card' => ['stripe_charge_id' => $card->stripe_charge_id]],
            'Payment completed.'
        );
    }

    /**
     * Display the payment status.
     *
     * @param  string  $stripeChargeId
     * 
     * @return \Illuminate\Http\Response
     */
    public function show($stripeChargeId)
    {
        try {
            $card = Card::where('stripe_charge_id', $stripeChargeId)->firstOrFail();
        }
        catch(ModelNotFoundException $e) {
            return $this->respondNotFound('Payment information not found');
        }

        $stripe = new \Stripe\StripeClient(config('stripe.stripe_secret'));

        $charge = $stripe->charges->retrieve(
            $stripeChargeId,
            []
        )->toArray();
        
        return $this->respondSuccess(
            ['payment' => [
                'amount' =>  data_get($charge, 'amount') / 100,
                'description' => data_get($charge, 'description'),
                'paid' => data_get($charge, 'paid'),
                'name_on_card' => data_get($card, 'name_on_card'),
                'card_brand' => data_get($charge, 'payment_method_details.card.brand'),
                'card_last_four_number' => data_get($charge, 'payment_method_details.card.last4'),
                'card_expiry_month' => data_get($charge, 'payment_method_details.card.exp_month'),
                'card_expiry_year' => data_get($charge, 'payment_method_details.card.exp_year'),
            ]],
            'Payment information retrieved.'
        );
    }
}
