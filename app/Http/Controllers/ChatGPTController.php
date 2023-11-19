<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class ChatGPTController extends Controller
{

    public function __invoke(Request $request)
    {

        try {

            $response = Http::withHeaders([
                "Content-Type" => "application/json",
                "Authorization" => "Bearer " . env('CHAT_GPT_KEY')
            ])->timeout(60)
                ->post('https://api.openai.com/v1/chat/completions', [
                "model" => "gpt-3.5-turbo",
                'messages' => [
                    [
                       "role" => "user",
                       "content" => $request->post('content')
                   ]

                ],

                'temperature' => 0.5,
                "max_tokens" => 1000,
                "top_p" => 1.0,
                "frequency_penalty" => 0.52,
                "presence_penalty" => 0.5,
                "stop" => ["11."],

            ])->json();

            return $response['choices'][0]['message']['content'];
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }
}
