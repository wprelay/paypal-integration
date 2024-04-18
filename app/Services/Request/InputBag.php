<?php

namespace WPRelay\Paypal\App\Services\Request;

class InputBag extends ParameterBag
{

    public function get(string $key, $default = null)
    {

        $value = parent::get($key, $this);

        return $this === $value ? $default : $value;
    }

    /**
     * Replaces the current input values by a new set.
     */
    public function replace(array $inputs = []): void
    {
        $this->parameters = [];
        $this->add($inputs);
    }

    /**
     * Adds input values.
     */
    public function add(array $inputs = []): void
    {
        foreach ($inputs as $input => $value) {
            $this->set($input, $value);
        }
    }

    public function set(string $key, mixed $value): void
    {
        $this->parameters[$key] = $value;
    }

    /**
     * Returns the parameter value converted to string.
     */
    public function getString(string $key, string $default = ''): string
    {
        // Shortcuts the parent method because the validation on scalar is already done in get().
        return (string)$this->get($key, $default);
    }
}
