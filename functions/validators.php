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
        $fieldValue = $inputs[$field];

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
 * @return array Массив с ошибками или пустой массив, если ошибок нет
 */
function validateAddLotForm(array $inputs): array
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
