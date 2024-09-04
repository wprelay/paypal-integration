<?php

namespace RelayWP\Paypal\App\Services\Validation;

use RelayWP\Paypal\App\Services\Request\Request;

interface FormRequest
{
    public function rules(Request $request);

    public function messages(): array;
}