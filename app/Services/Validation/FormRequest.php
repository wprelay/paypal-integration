<?php

namespace WPRelay\Paypal\App\Services\Validation;

use WPRelay\Paypal\App\Services\Request\Request;

interface FormRequest
{
    public function rules(Request $request);

    public function messages(): array;
}