<?php

return [
    'accepted' => 'The :attribute field must be accepted.',
    'accepted_if' => 'The :attribute field must be accepted when :other is :value.',
    'active_url' => 'The :attribute field must be a valid URL.',
    'after' => 'The :attribute field must be a date after :date.',
    'after_or_equal' => 'The :attribute field must be a date after or equal to :date.',
    'alpha' => 'The :attribute field must only contain letters.',
    'alpha_dash' => 'The :attribute field must only contain letters, numbers, dashes, and underscores.',
    'alpha_num' => 'The :attribute field must only contain letters and numbers.',
    'array' => 'The :attribute field must be an array.',
    'ascii' => 'The :attribute field must only contain single-byte alphanumeric characters and symbols.',
    'before' => 'The :attribute field must be a date before :date.',
    'before_or_equal' => 'The :attribute field must be a date before or equal to :date.',
    'between' => [
        'array'     => 'Поле :attribute должно содержать элементы от :min до :max.',
        'file'      => 'Поле :attribute должно быть между :min и :max килобайтами.',
        'numeric'   => 'Поле :attribute должно быть между :min и :max.',
        'string'    => 'Поле :attribute должно быть между символами :min и :max.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'can' => 'The :attribute field contains an unauthorized value.',
    'confirmed' => 'Поле подтверждения :attribute не совпадает.',
    'current_password' => 'The password is incorrect.',
    'date' => 'The :attribute field must be a valid date.',
    'date_equals' => 'The :attribute field must be a date equal to :date.',
    'date_format' => 'The :attribute field must match the format :format.',
    'decimal' => 'The :attribute field must have :decimal decimal places.',
    'declined' => 'The :attribute field must be declined.',
    'declined_if' => 'The :attribute field must be declined when :other is :value.',
    'different' => 'The :attribute field and :other must be different.',
    'digits' => 'The :attribute field must be :digits digits.',
    'digits_between' => 'The :attribute field must be between :min and :max digits.',
    'dimensions' => 'The :attribute field has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'doesnt_end_with' => 'The :attribute field must not end with one of the following: :values.',
    'doesnt_start_with' => 'The :attribute field must not start with one of the following: :values.',
    'email' => 'Поле :attribute должно содержать действительный адрес электронной почты.',
    'ends_with' => 'The :attribute field must end with one of the following: :values.',
    'enum' => 'The selected :attribute is invalid.',
    'exists' => 'The selected :attribute is invalid.',
    'extensions' => 'The :attribute field must have one of the following extensions: :values.',
    'file' => 'The :attribute field must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'array'     => 'Поле :attribute должно содержать больше элементов :value.',
        'file'      => 'Поле :attribute должно быть больше :value килобайт.',
        'numeric'   => 'Поле :attribute должно быть больше :value.',
        'string'    => 'Поле :attribute должно быть больше символов :value.',
    ],
    'gte' => [
        'array'     => 'Поле :attribute должно содержать элементы :value или более.',
        'file'      => 'Поле :attribute должно быть больше или равно :value килобайтам.',
        'numeric'   => 'Поле :attribute должно быть больше или равно :value.',
        'string'    => 'Поле :attribute должно быть больше или равно :value символов.',
    ],
    'hex_color'     => 'The :attribute field must be a valid hexadecimal color.',
    'image'         => 'Поле :attribute должно быть изображением.',
    'in'            => 'Выбранный :attribute недействителен.',
    'in_array'      => 'Поле :attribute должно существовать в :other.',
    'integer'       => 'Поле :attribute должно иметь целое число.',
    'ip'            => 'Поле :attribute должно быть действительным IP-адресом.',
    'ipv4'          => 'Поле :attribute должно быть действительным адресом IPv4.',
    'ipv6'          => 'Поле :attribute должно быть действительным адресом IPv6.',
    'json'          => 'Поле :attribute должно быть допустимой строкой JSON.',
    'lowercase'     => 'Поле :attribute должно быть в нижнем регистре.',
    'lt' => [
        'array'     => 'В поле :attribute должно быть меньше элементов :value.',
        'file'      => 'Поле :attribute должно быть меньше :value килобайт.',
        'numeric'   => 'Поле :attribute должно быть меньше :value.',
        'string'    => 'Поле :attribute должно быть меньше символов :value.',
    ],
    'lte' => [
        'array'     => 'Поле :attribute не должно содержать более элементов :value.',
        'file'      => 'Поле :attribute должно быть меньше или равно :value килобайтам.',
        'numeric'   => 'Поле :attribute должно быть меньше или равно :value.',
        'string'    => 'Поле :attribute должно быть меньше или равно :value символов.',
    ],
    'mac_address' => 'The :attribute field must be a valid MAC address.',
    'max' => [
        'array' => 'Поле :attribute не должно содержать более :max элементов.',
        'file' => 'Поле :attribute не должно содержать более :max килобайт.',
        'numeric' => 'Поле :attribute не должно содержать более :max.',
        'string' => 'Поле :attribute не должно содержать более :max символов.',
    ],
    'max_digits'    => 'Поле :attribute не должно содержать более :max цифр.',
    'mimes'         => 'Поле :attribute должно быть файлом типа: :values.',
    'mimetypes'     => 'Поле :attribute должно быть файлом типа: :values.',
    'min' => [
        'array'     => 'Поле :attribute должно содержать как минимум элементы :min.',
        'file'      => 'Поле :attribute должно быть не менее :min килобайт.',
        'numeric'   => 'В поле :attribute должно быть не меньше :min.',
        'string'    => 'Поле :attribute должно быть не менее :min символов.',
    ],
    'min_digits' => 'The :attribute field must have at least :min digits.',
    'missing' => 'The :attribute field must be missing.',
    'missing_if' => 'The :attribute field must be missing when :other is :value.',
    'missing_unless' => 'The :attribute field must be missing unless :other is :value.',
    'missing_with' => 'The :attribute field must be missing when :values is present.',
    'missing_with_all' => 'The :attribute field must be missing when :values are present.',
    'multiple_of' => 'The :attribute field must be a multiple of :value.',
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'Не допустимый формат для поля :attribute.',
    'numeric' => 'The :attribute field must be a number.',
    'password' => [
        'letters' => 'The :attribute field must contain at least one letter.',
        'mixed' => 'The :attribute field must contain at least one uppercase and one lowercase letter.',
        'numbers' => 'The :attribute field must contain at least one number.',
        'symbols' => 'The :attribute field must contain at least one symbol.',
        'uncompromised' => 'The given :attribute has appeared in a data leak. Please choose a different :attribute.',
    ],
    'present' => 'The :attribute field must be present.',
    'present_if' => 'The :attribute field must be present when :other is :value.',
    'present_unless' => 'The :attribute field must be present unless :other is :value.',
    'present_with' => 'The :attribute field must be present when :values is present.',
    'present_with_all' => 'The :attribute field must be present when :values are present.',
    'prohibited' => 'The :attribute field is prohibited.',
    'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
    'prohibits' => 'The :attribute field prohibits :other from being present.',
    'regex' => 'Не допустимый формат для поля :attribute.',
    'required' => 'Поле :attribute обязательно.',
    'required_array_keys' => 'The :attribute field must contain entries for: :values.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_if_accepted' => 'The :attribute field is required when :other is accepted.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute field must match :other.',
    'size' => [
        'array' => 'The :attribute field must contain :size items.',
        'file' => 'The :attribute field must be :size kilobytes.',
        'numeric' => 'The :attribute field must be :size.',
        'string' => 'The :attribute field must be :size characters.',
    ],
    'starts_with' => 'The :attribute field must start with one of the following: :values.',
    'string' => 'The :attribute field must be a string.',
    'timezone' => 'The :attribute field must be a valid timezone.',
    'unique' => ':attribute уже занят.',
    'uploaded' => 'The :attribute failed to upload.',
    'uppercase' => 'The :attribute field must be uppercase.',
    'url' => 'The :attribute field must be a valid URL.',
    'ulid' => 'The :attribute field must be a valid ULID.',
    'uuid' => 'The :attribute field must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'email_or_login' => 'E-mail или Логин',
        'password' => 'Пароль',
        'site_name_*' => 'Название сайта',
        'title_1' => 'Название',
        'title_2' => 'Название',
        'title_3' => 'Название',
        'meta_title_*' => 'Meta-Title',
        'support_email' => 'Email поддержки',
        'name' => 'Название',
        'username' => 'Имя',
        'code' => 'Код',
        'status' => 'Статус',
        'login' => 'Логин',
        'email' => 'E-mail',
        'balance' => 'Баланс',
    ],

];
