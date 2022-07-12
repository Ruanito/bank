<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as RequestValidator;
use Internal\Bank\Payment\BankPaymentRequest;
use Internal\Stripe\Exception\StripePaymentException;
use Internal\Stripe\Payment\StripePaymentService;

class PaymentsController extends Controller {
    /**
     * @throws \Exception
     */
    public function create(Request $request): mixed {
        $validator = $this->getValidator($request);
        if ($validator->fails()) {
            return response()
                ->json(['status' => 'error', 'message' => $validator->getMessageBag()], 400);
        }

        $params = $this->getParams($request);
        try {
            $payment = (new StripePaymentService())->create(...$params);
            return redirect()->away($payment->getRedirectUrl());
        } catch (StripePaymentException $e) {
            return response()
                ->json(['status' => 'error', 'message' => json_decode($e->getMessage())], 400);
        }
    }

    private function getValidator(Request $request): RequestValidator {
        return Validator::make($request->all(), [
            'items' => 'required',
            'items.*.product' => 'required',
            'items.*.quantity' => 'required|numeric',
        ]);
    }

    /**
     * @throws \Exception
     */
    private function getParams(Request $request): array {
        $bank_payment_request = [];
        foreach ($request->input('items') as $item) {
            $bank_payment_request[] = BankPaymentRequest::builder()
                ->withProduct($item['product'])
                ->withQuantity($item['quantity'])
                ->build();
        }
        return $bank_payment_request;
    }
}
