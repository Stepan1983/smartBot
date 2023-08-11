<?php
if (isset($_GET['word'])) {
    // Устанавливаем URL-адрес API OpenAI
    $url = 'https://api.openai.com/v1/chat/completions';

    // Устанавливаем токен API OpenAI
    $api_key = "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";

    // Загружаем историю сообщений из файла
    $history_file = 'history.json';
    if (file_exists($history_file)) {
        $messages = json_decode(file_get_contents($history_file), true);
    } else {
        $messages = array();
    }

    // Добавляем новое сообщение пользователя в историю
    $messages[] = array(
        'role' => 'user',
        'content' => $_GET['word'],
    );

    // Устанавливаем параметры запроса в виде ассоциативного массива
    $data = array(
        'model' => 'gpt-3.5-turbo',
        'messages' => $messages,
        'temperature' => 0.1
    );

    // Инициализируем curl-запрос
    $curl = curl_init();

    // Устанавливаем опции для curl-запроса
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key
        )
    ));

    // Выполняем curl-запрос
    $response = curl_exec($curl);

    // Проверяем наличие ошибок при выполнении curl-запроса
    if (curl_errno($curl)) {
        echo 'Ошибка curl: ' . curl_error($curl);
    }

    // Закрываем curl-сессию
    curl_close($curl);

    // Обрабатываем ответ от API OpenAI
    if ($response) {
        $result = json_decode($response, true);
        $text_response = $result["choices"][0]["message"]["content"];
      file_put_contents('words.txt', $text_response);

        // Добавляем ответ GPT-4 в историю сообщений
        $messages[] = array(
            'role' => 'assistant',
            'content' => $text_response,
        );

        // Сохраняем историю сообщений в файл
        file_put_contents($history_file, json_encode($messages));

        // Выводим ответ
        echo $text_response;
    } else {
        echo 'Получен пустой ответ от API OpenAI';
    }
}
?>