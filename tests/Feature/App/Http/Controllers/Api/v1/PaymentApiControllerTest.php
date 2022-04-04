<?php

namespace Tests\Feature\App\Http\Controllers\Api\v1;

use App\Models\Buyer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentApiControllerTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Running the necessary setup for tests
     *
     * @return void
     */
    public function setUp() : void
    {
        parent::setUp();
    }

    /**
     * test setup indent with correct email
     *
     * @return void
     */
    public function test_setup_indent_with_correct_email()
    {
        $samplePayload = [
            'name' => 'Thiha Kyaw',
            'email' => 'example.dev@gmail.com'
        ];

        $response = $this->post('/api/v1/payments', $samplePayload);

        $response->assertJsonStructure([
            'data' => [
                'intent'
            ],
            'success' => [
                'message'
            ]
        ])
        ->assertStatus(201);
    }

    /**
     * test make payment without buyer
     *
     * @return void
     */
    public function test_make_payment_without_buyer()
    {
        $samplePayload = [
            'name_on_card' => 'Thiha Kyaw',
            'email' => 'example.dev@gmail.com',
            'amount' => 100,
            'payment_method' => 'pm_card_visa',
            'description' => 'Product #1'
        ];

        $response = $this->put('/api/v1/payments', $samplePayload);

        $response->assertJsonStructure([
            'errors'
        ])
        ->assertStatus(404);
    }

    /**
     * test successful make payment
     *
     * @return void
     */
    public function test_make_payment_successfully()
    {
        $samplePayloadSetupIntent = [
            'name' => 'Thiha Kyaw',
            'email' => 'example.dev@gmail.com'
        ];

        $response = $this->post('/api/v1/payments', $samplePayloadSetupIntent);

        $response->assertJsonStructure([
            'data' => [
                'intent'
            ],
            'success' => [
                'message'
            ]
        ])
        ->assertStatus(201);

        $samplePayloadMakePayment = [
            'name_on_card' => 'Thiha Kyaw',
            'email' => 'example.dev@gmail.com',
            'amount' => 100,
            'payment_method' => 'pm_card_visa',
            'description' => 'Product #1'
        ];

        $response = $this->put('/api/v1/payments', $samplePayloadMakePayment);

        $response->assertJsonStructure([
            'data' => [
                'buyer' => [
                    'name',
                    'email',
                ],
                'card' => [
                    'stripe_charge_id'
                ],
            ],
            'success' => [
                'message'
            ]
        ])
        ->assertStatus(200);
    }

    /**
     * test get non existing stripe charge
     *
     * @return void
     */
    public function test_get_non_existing_stripe_charge()
    {
        $response = $this->get('/api/v1/payments/'.$this->generateRandomString());

        $response->assertJsonStructure([
            'errors'
        ])
        ->assertStatus(404);
    }

    /**
     * test get the payment information
     *
     * @return void
     */
    public function test_get_the_payment_information()
    {
        $samplePayloadSetupIntent = [
            'name' => 'Thiha Kyaw',
            'email' => 'example.dev@gmail.com'
        ];

        $response = $this->post('/api/v1/payments', $samplePayloadSetupIntent);

        $response->assertJsonStructure([
            'data' => [
                'intent'
            ],
            'success' => [
                'message'
            ]
        ])
        ->assertStatus(201);

        $samplePayloadMakePayment = [
            'name_on_card' => 'Thiha Kyaw',
            'email' => 'example.dev@gmail.com',
            'amount' => 100,
            'payment_method' => 'pm_card_visa',
            'description' => 'Product #1'
        ];

        $response = $this->put('/api/v1/payments', $samplePayloadMakePayment);

        $response->assertJsonStructure([
            'data' => [
                'buyer' => [
                    'name',
                    'email',
                ],
                'card' => [
                    'stripe_charge_id'
                ],
            ],
            'success' => [
                'message'
            ]
        ])
        ->assertStatus(200);
        
        $stripeChargeId = data_get($response, 'data.card.stripe_charge_id');

        $response = $this->get('/api/v1/payments/'. $stripeChargeId);

        $response->assertJsonStructure([
            'data' => [
                'payment' => [
                    'amount',
                    'description',
                    'paid',
                    'name_on_card',
                    'card_brand',
                    'card_last_four_number',
                    'card_expiry_month',
                    'card_expiry_year'
                ]
                ],
                'success' => [
                    'message'
                ]
        ])
        ->assertStatus(200);
    }

    /**
     * Generate random string for non existing stripe charge id
     *
     * 
     * @return string
     */
    private function generateRandomString() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 30; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}