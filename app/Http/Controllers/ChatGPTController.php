<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ChatGPTController extends Controller
{

    public function __invoke(Request $request)
    {

        // try {

        //     $response = Http::withHeaders([
        //         "Content-Type" => "application/json",
        //         "Authorization" => "Bearer " . env('CHAT_GPT_KEY')
        //     ])->timeout(60)
        //         ->post('https://api.openai.com/v1/chat/completions', [
        //         "model" => "gpt-3.5-turbo",
        //         'messages' => [
        //             [
        //                "role" => "user",
        //                "content" => $request->post('content')
        //            ]

        //         ],

        //         'temperature' => 0.5,
        //         "max_tokens" => 1000,
        //         "top_p" => 1.0,
        //         "frequency_penalty" => 0.52,
        //         "presence_penalty" => 0.5,
        //         "stop" => ["11."],

        //     ])->json();

        //     return $response['choices'][0]['message']['content'];
        // } catch (Throwable $e) {
        //     return $e->getMessage();
        // }

        try {
            $requestData = [
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
            ];

            // Convert request data to JSON
            $jsonRequestData = json_encode($requestData);

            // Save JSON request data to a temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'gpt_request_');
            file_put_contents($tempFile, $jsonRequestData);

            // Build cURL command
            $command = [
                'curl',
                '--location',
                '--request',
                'POST',
                'https://api.openai.com/v1/chat/completions',
                '--header',
                'Content-Type: application/json',
                '--header',
                'Authorization: Bearer ' . env('CHAT_GPT_KEY'),
                '--data',
                '@' . $tempFile, // Use the temporary file containing the JSON data
            ];

            // Create a new process
            $process = new Process($command);

            // Run the process
            $process->run();

            // Check if the process was successful
            if ($process->isSuccessful()) {
                // Decode the JSON response from the command output
                $response = json_decode($process->getOutput(), true);
                $result = $response['choices'][0]['message']['content'];

                return $result;
            } else {
                throw new ProcessFailedException($process);
            }
        } catch (Throwable $e) {
            $result = $e->getMessage();

            return $result;

        } finally {
            // Clean up: remove the temporary file
            if (isset($tempFile) && file_exists($tempFile)) {
                unlink($tempFile);
            }
        }

    }
}
