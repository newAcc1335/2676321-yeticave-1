<?php

require_once __DIR__ . "/validation_rules.php";

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
    $rules = [
        'title' => [
            [
                'rule' => required(),
                'message' => 'Введите наименование лота',
            ],
            [
                'rule' => maxLength(150),
                'message' => 'Название не должно превышать 150 символов',
            ],
        ],

        'category' => [
            [
                'rule' => required(),
                'message' => 'Выберите категорию',
            ],
            [
                'rule' => categoryExists($categories),
                'message' => 'Несуществующая категория',
            ],
        ],

        'description' => [
            [
                'rule' => required(),
                'message' => 'Введите описание лота',
            ],
            [
                'rule' => maxLength(2000),
                'message' => 'Описание не должно превышать 2000 символов',
            ],
        ],

        'starting_price' => [
            [
                'rule' => required(),
                'message' => 'Введите начальную цену',
            ],
            [
                'rule' => positiveInt(),
                'message' => 'Начальная цена должна быть целым положительным числом',
            ],
        ],

        'bid_step' => [
            [
                'rule' => required(),
                'message' => 'Введите шаг ставки',
            ],
            [
                'rule' => positiveInt(),
                'message' => 'Шаг ставки должен быть целым положительным числом',
            ],
        ],

        'end_time' => [
            [
                'rule' => required(),
                'message' => 'Введите дату окончания торгов',
            ],
            [
                'rule' => dateYmd(),
                'message' => 'Дата должна быть в формате «ГГГГ-ММ-ДД»',
            ],
            [
                'rule' => dateAtLeastTomorrow(),
                'message' => 'Дата должна быть не раньше следующего дня',
            ],
        ],
    ];

    $errors = validateForm($inputs, $rules);

    $imageErr = validateImage('image');
    if ($imageErr !== null) {
        $errors['image'] = $imageErr;
    }

    return $errors;
}

/**
 * Валидирует данные формы добавления лота.
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
    $rules = [
        'email' => [
            [
                'rule' => required(),
                'message' => 'Введите e-mail',
            ],
            [
                'rule' => maxLength(255),
                'message' => 'E-mail не должен превышать 255 символов',
            ],
            [
                'rule' => email(),
                'message' => 'Введите корректный e-mail',
            ],
            [
                'rule' => emailNotExists($conn),
                'message' => 'Данный e-mail уже зарегистрирован',
            ],
        ],

        'password' => [
            [
                'rule' => required(),
                'message' => 'Введите пароль',
            ],
            [
                'rule' => minLength(5),
                'message' => 'Пароль не может быть короче 5 символов',
            ],
            [
                'rule' => maxLength(72),
                'message' => 'Пароль не может быть длиннее 72 символов',
            ],
        ],

        'name' => [
            [
                'rule' => required(),
                'message' => 'Введите имя',
            ],
            [
                'rule' => maxLength(100),
                'message' => 'Имя не должно превышать 100 символов',
            ],
        ],

        'contactInfo' => [
            [
                'rule' => required(),
                'message' => 'Напишите как с вами связаться',
            ],
            [
                'rule' => maxLength(2000),
                'message' => 'Контактная информация не должна превышать 2000 символов',
            ],
        ],
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
    $rules = [
        'email' => [
            [
                'rule' => required(),
                'message' => 'Введите e-mail',
            ],
            [
                'rule' => email(),
                'message' => 'Введите корректный e-mail',
            ],
            [
                'rule' => maxLength(255),
                'message' => 'E-mail не должен превышать 255 символов',
            ],
        ],

        'password' => [
            [
                'rule' => required(),
                'message' => 'Введите пароль',
            ],
            [
                'rule' => maxLength(72),
                'message' => 'Пароль не может быть длиннее 72 символов',
            ],
        ],
    ];

    return validateForm($inputs, $rules);
}

/**
 * Валидирует данные формы авторизации.
 *
 * @param array $inputs Массив с данными формы
 * @param array $lotBids Массив с ставками к данном лоту
 * @param array $user Авторизованный пользователь
 * @param array $lot Лот, на который делают ставку
 *
 * @return array Массив с ошибками или пустой массив, если ошибок нет
 */
function validateAddBidForm(array $inputs, array $lotBids, array $user, array $lot): array
{
    $rules = [
        'cost' => [
            [
                'rule' => validBidUser($user, $lotBids, $lot),
                'message' => 'Нельзя ставить на свой же лот или перебивать свою же ставку',
            ],
            [
                'rule' => required(),
                'message' => 'Введите свою ставку',
            ],
            [
                'rule' => positiveInt(),
                'message' => 'Ставка должна быть целым положительным числом',
            ],
            [
                'rule' => validateBidStep($lot['price'], $lot['step'], !empty($lotBids)),
                'message' => 'Не соблюден шаг ставки',
            ],
        ]
    ];

    return validateForm($inputs, $rules);
}
