<?php

define('REGISTER_RULES', [
    RegisterField::EMAIL->value => [
        ['rule' => required(), 'message' => 'Введите e-mail'],
        ['rule' => email(), 'message' => 'Введите корректный e-mail'],
        ['rule' => maxLength(255), 'message' => 'E-mail не должен превышать 255 символов'],
    ],
    RegisterField::PASSWORD->value => [
        ['rule' => required(), 'message' => 'Введите пароль'],
        ['rule' => minLength(5), 'message' => 'Пароль не может быть короче 5 символов'],
        ['rule' => maxLength(72), 'message' => 'Пароль не может быть длиннее 72 символов'],
    ],
    RegisterField::NAME->value => [
        ['rule' => required(), 'message' => 'Введите имя'],
        ['rule' => maxLength(100), 'message' => 'Имя не должно превышать 100 символов'],
    ],
    RegisterField::CONTACT_INFO->value => [
        ['rule' => required(), 'message' => 'Напишите как с вами связаться'],
        ['rule' => maxLength(2000), 'message' => 'Контактная информация не должна превышать 2000 символов'],
    ],
]);

define('ADD_LOT_RULES', [
    LotField::TITLE->value => [
        ['rule' => required(), 'message' => 'Введите наименование лота'],
        ['rule' => maxLength(150), 'message' => 'Название не должно превышать 150 символов'],
    ],
    LotField::CATEGORY->value => [
        ['rule' => required(), 'message' => 'Выберите категорию'],
    ],
    LotField::DESCRIPTION->value => [
        ['rule' => required(), 'message' => 'Введите описание лота'],
        ['rule' => maxLength(2000), 'message' => 'Описание не должно превышать 2000 символов'],
    ],
    LotField::STARTING_PRICE->value => [
        ['rule' => required(), 'message' => 'Введите начальную цену'],
        [
            'rule' => positiveInt(),
            'message' => 'Начальная цена должна быть целым положительным числом, не превышающая 1млрд'
        ],
    ],
    LotField::BID_STEP->value => [
        ['rule' => required(), 'message' => 'Введите шаг ставки'],
        [
            'rule' => positiveInt(),
            'message' => 'Шаг ставки должен быть целым положительным числом, не превышающим 1млрд'
        ],
    ],
    LotField::END_TIME->value => [
        ['rule' => required(), 'message' => 'Введите дату окончания торгов'],
        ['rule' => dateYmd(), 'message' => 'Дата должна быть в формате «ГГГГ-ММ-ДД»'],
        ['rule' => dateAtLeastTomorrow(), 'message' => 'Дата должна быть не раньше следующего дня'],
    ],
]);

define('LOGIN_RULES', [
    LoginField::EMAIL->value => [
        ['rule' => required(), 'message' => 'Введите e-mail'],
        ['rule' => email(), 'message' => 'Введите корректный e-mail'],
        ['rule' => maxLength(255), 'message' => 'E-mail не должен превышать 255 символов'],
    ],
    LoginField::PASSWORD->value => [
        ['rule' => required(), 'message' => 'Введите пароль'],
        ['rule' => maxLength(72), 'message' => 'Пароль не может быть длиннее 72 символов'],
    ],
]);

define('ADD_BID_RULES', [
    BidField::COST->value => [
        ['rule' => required(), 'message' => 'Введите свою ставку'],
        ['rule' => positiveInt(), 'message' => 'Ставка должна быть целым положительным числом, не превышающим 1млрд'],
    ],
]);

/**
 * Валидирует входные данные формы по набору правил.
 *
 * @param array $inputs Массив с данными формы:
 *                   ['field1' => 'value1',
 *                    'field2' => 'value2',
 *                    ...
 *                   ]
 * @param array $rules Массив с правилами валидации и текстом ошибок к этим правилам:
 *                   ['field1' => [
 *                                  [
 *                                      'rule' => callable(mixed): bool,
 *                                      'message' => string,
 *                                  ],
 *                                  ...
 *                                ],
 *                    ...
 *                   ],
 * @return array Массив с ошибками или пустой массив, если ошибок нет:
 *                    ['field1' => 'message',
 *                     'field2' => 'message',
 *                     ...
 *                    ]
 */
function validateForm(array $inputs, array $rules): array
{
    $errors = [];

    foreach ($rules as $field => $validRules) {
        $fieldValue = $inputs[$field] ?? null;

        foreach ($validRules as $valid) {
            if ($valid['rule']($fieldValue) === false) {
                $errors[$field] = $valid['message'];
                break;
            }
        }
    }

    return $errors;
}

/**
 * Валидирует данные формы добавления лота.
 *
 * @param array $inputs Массив с данными формы
 * @param array $categories Массив с категориями
 *
 * @return array Массив с ошибками или пустой массив, если ошибок нет
 */
function validateAddLotForm(array $inputs, array $categories): array
{
    $rules = ADD_LOT_RULES;
    $rules[LotField::CATEGORY->value][] = [
        'rule' => categoryExists($categories),
        'message' => 'Несуществующая категория'
    ];

    $errors = validateForm($inputs, $rules);

    $imageErr = validateImage(LotField::IMAGE->value);
    if ($imageErr !== null) {
        $errors[LotField::IMAGE->value] = $imageErr;
    }

    return $errors;
}

/**
 * Валидирует данные формы регистрации пользователя.
 *
 * @param array $inputs Массив с данными формы
 * @param mysqli $conn Соединение с базой данных
 *
 * @return array Массив с ошибками или пустой массив, если ошибок нет
 *
 * @throws RuntimeException В случае ошибки выполнения запроса для проверки e-mail.
 */
function validateRegisterForm(array $inputs, mysqli $conn): array
{
    $rules = REGISTER_RULES;
    $rules[RegisterField::EMAIL->value][] = [
        'rule' => emailNotExists($conn),
        'message' => 'Данный e-mail уже зарегистрирован'
    ];

    return validateForm($inputs, $rules);
}

/**
 * Валидирует данные формы авторизации.
 *
 * @param array $inputs Массив с данными формы
 * @return array Массив с ошибками или пустой массив, если ошибок нет
 */
function validateLoginForm(array $inputs): array
{
    return validateForm($inputs, LOGIN_RULES);
}

/**
 * Валидирует данные формы добавления ставки к лоту.
 *
 * @param array $inputs Массив с данными формы
 * @param array $lotBids Массив со ставками к данном лоту
 * @param array $lot Лот, на который делают ставку
 *
 * @return array Массив с ошибками или пустой массив, если ошибок нет
 */
function validateAddBidForm(array $inputs, array $lotBids, array $lot): array
{
    $rules = ADD_BID_RULES;
    $rules[BidField::COST->value][] = [
        'rule' => validateBidStep($lot['price'], $lot['step'], !empty($lotBids)),
        'message' => 'Не соблюден шаг ставки'
    ];

    return validateForm($inputs, $rules);
}
