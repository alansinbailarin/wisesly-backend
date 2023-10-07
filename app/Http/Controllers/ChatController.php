<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function generateResponse(Request $request)
    {
        // Recupera el historial de conversación actual de la sesión
        $messages = session('messages', []);

        // Agrega el mensaje del asistente al historial
        $messages[] = [
            "role" => "assistant",
            "content" => "Eres un bot de asistencia, con el propósito de dar información
            a los usuarios que chateen contigo, armaras una lista de cosas para hacer en
            la ciudad de destino, y también darás información sobre el clima de la ciudad
            todo esto dependiendo de a donde quieran ir los usuarios, teniendo en cuenta
            la fecha en la que les gustaría viajar, perteneces a wisesly, que es una página
            de viajes a medida, o sea que crearás el itinerario y cosas por hacer para los usuarios
            hay varias preguntas que debes hacer para que funcione de manera correcta, y no
            des detalles hasta que los usuarios las respondan, intenta ser muy amable con los
            usuarios, las preguntas son, a dónde te gustaría viajar, cuándo te gustaría viajar,
            cuál es tu presupuesto, ciudad de origen, precios aproximados de vuelos, estancias,
            comida, gastos en general, el tipo de moneda del usuario, si irá con más personas,
            etc, o sea que serás un IA trip planner"
        ];

        // Agrega el mensaje del usuario al historial
        $messages[] = [
            "role" => "user",
            "content" => $request->input('content')
        ];

        // Realiza la solicitud a la API de OpenAI con el historial de conversación
        $response = Http::withHeaders([
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . env("CHAT_GPT_KEY")
        ])->post('https://api.openai.com/v1/chat/completions', [
            "model" => "gpt-3.5-turbo",
            "messages" => $messages,
            "temperature" => 0.6,
            "max_tokens" => 150,
        ])->body();

        // Almacena el nuevo historial de conversación en la sesión del usuario
        session(['messages' => $messages]);

        return response()->json(json_decode($response));
    }


    public function checkSkyScannerServer()
    {
        $response = Http::withHeaders([
            'X-RapidAPI-Host' => env("SKY_SCANNER_HOST"),
            "X-RapidAPI-Key" => env("SKY_SCANNER_KEY")
        ])->get('https://sky-scrapper.p.rapidapi.com/api/v1/checkServer')->body();

        return response()->json(json_decode($response));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chat $chat)
    {
        //
    }
}
