<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Ambil 1 soal acak yang belum pernah digunakan.
     */
    public function getRandomQuestion(Request $request)
    {
        // Tangani parameter used_ids (bisa berupa array atau string pisahan koma "1,2,3")
        $rawUsedIds = $request->input('used_ids');
        $usedIds = [];

        if (is_array($rawUsedIds)) {
            $usedIds = array_filter($rawUsedIds);
        } elseif (is_string($rawUsedIds) && trim($rawUsedIds) !== '') {
            $usedIds = array_filter(explode(',', $rawUsedIds));
        }

        $card = $request->input('card');

        // Query dasar
        $query = Question::query();

        // Jika ada filter kartu spesifik (opsional)
        if ($card) {
            $query->where('card', $card);
        }

        // Hindari soal yang sudah pernah dipakai
        if (!empty($usedIds)) {
            $query->whereNotIn('id', $usedIds);
        }

        $question = $query->inRandomOrder()->first();

        // Jika soal berdasarkan kartu tertentu habis, ambil dari bank soal umum yang belum dipakai
        if (!$question && !empty($usedIds)) {
            $question = Question::whereNotIn('id', $usedIds)->inRandomOrder()->first();
        }

        // Jika bank soal benar-benar habis, ambil soal acak mana saja agar game tidak crash
        if (!$question) {
            $question = Question::inRandomOrder()->first();
        }

        if (!$question) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bank soal kosong.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $question->id,
                'card' => $question->card ?? $card,
                'question' => $question->question,
                'options' => [
                    'A' => $question->option_a,
                    'B' => $question->option_b,
                    'C' => $question->option_c,
                    'D' => $question->option_d,
                ],
                'points' => $question->points ?? 10,
                'category' => $question->category,
                'difficulty' => $question->difficulty,
            ]
        ]);
    }

    /**
     * Verifikasi jawaban yang dipilih pemain.
     */
    public function checkAnswer(Request $request)
    {
        $request->validate([
            'question_id' => 'required',
            'answer' => 'required|string',
        ]);

        $question = Question::find($request->question_id);

        if (!$question) {
            return response()->json([
                'status' => 'error',
                'message' => 'Soal tidak ditemukan'
            ], 404);
        }

        $userAnswer = strtoupper(trim($request->answer));
        $correctAnswer = strtoupper(trim($question->correct_answer));

        $isCorrect = ($userAnswer === $correctAnswer);

        return response()->json([
            'status' => 'success',
            'is_correct' => $isCorrect,
            'correct_answer' => $correctAnswer,
            'points' => $isCorrect ? ($question->points ?? 10) : 0,
        ]);
    }
}