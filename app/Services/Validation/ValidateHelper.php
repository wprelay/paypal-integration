<?php

namespace WPRelay\Paypal\App\Services\Validation;

use WPRelay\Paypal\App\Services\Database;
use WPRelay\Paypal\App\Services\Request\Request;
use WPRelay\Paypal\App\Services\Request\Response;
use Valitron\Validator;

trait ValidateHelper
{
    public function validate($object, array $additionalRules = [])
    {

        if (is_object($object)) {
            $rules = array_merge($object->rules($this), $additionalRules);
        } else {
            $rules = array_merge($object, $additionalRules);
        }


        $validator = $this->getValidator();

        $validator->mapFieldsRules($rules);

//        $validator = $this->mapCustomErrorMessages($validator, $rules, $messages);

        if (!$validator->validate()) {
            $errors = $validator->errors();
            Response::success($errors, 422);
        }
    }

    public function getValidator()
    {
        if (is_object(Request::$validator)) {
            return Request::$validator;
        }
        $data = $this->all();

        Request::$validator = new Validator($data);

        $this->addCustomRules();

        return Request::$validator;
    }

    public function addCustomRules()
    {

    }

    public function mapCustomErrorMessages($validator, $rules, $messages)
    {
        return $validator;
    }

    public function getWordFromRequestKey($key)
    {
        $result = str_replace('_', ' ', $key);

        return ucwords($result);
    }

    public static function isColumnUnique($table, $column, $value, $excludeColumnName = null, $excludeColumnId = null)
    {
        $items = (new Database($table))->where("$column = %s", [$value])
            ->when(!empty($excludeColumnName) && $excludeColumnId, function ($query) use ($column, $value, $excludeColumnId, $excludeColumnName) {
                $query->where("{$excludeColumnName} not in (%s)", [$excludeColumnId]);
            });

        $items = $items->get();

        if (empty($items)) return true;

        return false;
    }
}