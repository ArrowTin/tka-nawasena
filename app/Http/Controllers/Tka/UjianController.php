<?php

namespace App\Http\Controllers\Tka;

use App\Http\Controllers\Controller;

class UjianController extends Controller
{
    public function index()
    {
        $jenjangPendidikan = collect([
            'sd' => 'SD/MI/SEDERAJAT',
            'smp' => 'SMP/MTs/SEDERAJAT',
            'sma' => 'SMA/SMK/MA/MAK/SEDERAJAT',
        ]);

        $jenisMataPelajaran = collect([
            'wajib' => 'Wajib',
            'pilihan' => 'Pilihan',
        ]);

        $mataPelajaran = collect([
            'mtk' => 'Matematika',
            'ipa' => 'Ilmu Pengetahuan Alam',
            'indonesia' => 'Bahasa Indonesia',
        ]);

        // 10 Soal Dummy untuk TKA Matematika dengan Gambar
        $questions = collect([
            [
                'id' => 1,
                'question_text' => 'Perhatikan gambar persegi panjang berikut. Jika panjang = 12 cm dan lebar = 8 cm, maka kelilingnya adalah...',
                'question_image' => 'https://via.placeholder.com/400x300/4a90e2/ffffff?text=Persegi+Panjang%0A12+cm+%C3%97+8+cm',
                'options' => [
                    ['label' => 'A', 'text' => '20 cm'],
                    ['label' => 'B', 'text' => '40 cm'],
                    ['label' => 'C', 'text' => '80 cm'],
                    ['label' => 'D', 'text' => '96 cm'],
                ],
            ],
            [
                'id' => 2,
                'question_text' => 'Perhatikan diagram lingkaran berikut. Jika jari-jari = 14 cm, maka luas daerah yang diarsir (setengah lingkaran) adalah... (π = 22/7)',
                'question_image' => 'https://via.placeholder.com/400x300/ff6b6b/ffffff?text=Setengah+Lingkaran%0Ar+%3D+14+cm',
                'options' => [
                    ['label' => 'A', 'text' => '154 cm²'],
                    ['label' => 'B', 'text' => '308 cm²'],
                    ['label' => 'C', 'text' => '616 cm²'],
                    ['label' => 'D', 'text' => '1232 cm²'],
                ],
            ],
            [
                'id' => 3,
                'question_text' => 'Perhatikan gambar segitiga siku-siku berikut. Jika panjang sisi AB = 5 cm dan BC = 12 cm, maka panjang sisi AC adalah...',
                'question_image' => 'https://via.placeholder.com/400x300/51cf66/ffffff?text=Segitiga+Siku-Siku%0AAB+%3D+5+cm%0ABC+%3D+12+cm',
                'options' => [
                    ['label' => 'A', 'text' => '10 cm'],
                    ['label' => 'B', 'text' => '13 cm'],
                    ['label' => 'C', 'text' => '17 cm'],
                    ['label' => 'D', 'text' => '20 cm'],
                ],
            ],
            [
                'id' => 4,
                'question_text' => 'Perhatikan gambar kubus berikut. Jika panjang rusuk = 6 cm, maka volume kubus tersebut adalah...',
                'question_image' => 'https://via.placeholder.com/400x300/ffd43b/ffffff?text=Kubus%0As+%3D+6+cm',
                'options' => [
                    ['label' => 'A', 'text' => '36 cm³'],
                    ['label' => 'B', 'text' => '108 cm³'],
                    ['label' => 'C', 'text' => '216 cm³'],
                    ['label' => 'D', 'text' => '432 cm³'],
                ],
            ],
            [
                'id' => 5,
                'question_text' => 'Perhatikan grafik fungsi linear berikut. Persamaan garis yang melalui titik (0, 3) dan (4, 7) adalah...',
                'question_image' => 'https://via.placeholder.com/400x300/845ef7/ffffff?text=Grafik+Fungsi+Linear%0A(0%2C+3)+dan+(4%2C+7)',
                'options' => [
                    ['label' => 'A', 'text' => 'y = x + 3'],
                    ['label' => 'B', 'text' => 'y = 2x + 3'],
                    ['label' => 'C', 'text' => 'y = x - 3'],
                    ['label' => 'D', 'text' => 'y = 2x - 3'],
                ],
            ],
            [
                'id' => 6,
                'question_text' => 'Perhatikan gambar trapesium berikut. Jika tinggi = 10 cm, panjang sisi sejajar atas = 6 cm, dan panjang sisi sejajar bawah = 14 cm, maka luasnya adalah...',
                'question_image' => 'https://via.placeholder.com/400x300/fd7e14/ffffff?text=Trapesium%0At+%3D+10+cm%0Aa+%3D+6+cm%0Ab+%3D+14+cm',
                'options' => [
                    ['label' => 'A', 'text' => '60 cm²'],
                    ['label' => 'B', 'text' => '100 cm²'],
                    ['label' => 'C', 'text' => '140 cm²'],
                    ['label' => 'D', 'text' => '200 cm²'],
                ],
            ],
            [
                'id' => 7,
                'question_text' => 'Perhatikan diagram batang berikut yang menunjukkan jumlah siswa per kelas. Kelas manakah yang memiliki jumlah siswa terbanyak?',
                'question_image' => 'https://via.placeholder.com/400x300/e64980/ffffff?text=Diagram+Batang%0AJumlah+Siswa+per+Kelas',
                'options' => [
                    ['label' => 'A', 'text' => 'Kelas VII A'],
                    ['label' => 'B', 'text' => 'Kelas VII B'],
                    ['label' => 'C', 'text' => 'Kelas VIII A'],
                    ['label' => 'D', 'text' => 'Kelas VIII B'],
                ],
            ],
            [
                'id' => 8,
                'question_text' => 'Perhatikan gambar belah ketupat berikut. Jika panjang diagonal 1 = 12 cm dan diagonal 2 = 16 cm, maka luasnya adalah...',
                'question_image' => 'https://via.placeholder.com/400x300/339af0/ffffff?text=Belah+Ketupat%0Ad1+%3D+12+cm%0Ad2+%3D+16+cm',
                'options' => [
                    ['label' => 'A', 'text' => '48 cm²'],
                    ['label' => 'B', 'text' => '56 cm²'],
                    ['label' => 'C', 'text' => '96 cm²'],
                    ['label' => 'D', 'text' => '192 cm²'],
                ],
            ],
            [
                'id' => 9,
                'question_text' => 'Perhatikan gambar jajar genjang berikut. Jika alas = 15 cm dan tinggi = 8 cm, maka luasnya adalah...',
                'question_image' => 'https://via.placeholder.com/400x300/20c997/ffffff?text=Jajar+Genjang%0Aalas+%3D+15+cm%0Atinggi+%3D+8+cm',
                'options' => [
                    ['label' => 'A', 'text' => '60 cm²'],
                    ['label' => 'B', 'text' => '90 cm²'],
                    ['label' => 'C', 'text' => '120 cm²'],
                    ['label' => 'D', 'text' => '150 cm²'],
                ],
            ],
            [
                'id' => 10,
                'question_text' => 'Perhatikan gambar layang-layang berikut. Jika panjang diagonal 1 = 10 cm dan diagonal 2 = 24 cm, maka luasnya adalah...',
                'question_image' => 'https://via.placeholder.com/400x300/ff8787/ffffff?text=Layang-Layang%0Ad1+%3D+10+cm%0Ad2+%3D+24+cm',
                'options' => [
                    ['label' => 'A', 'text' => '120 cm²'],
                    ['label' => 'B', 'text' => '240 cm²'],
                    ['label' => 'C', 'text' => '340 cm²'],
                    ['label' => 'D', 'text' => '480 cm²'],
                ],
            ],
        ]);

        $currentQuestionNumber = request('question', 1);
        $currentQuestion = $questions->where('id', $currentQuestionNumber)->first() ?? $questions->first();
        $totalQuestions = $questions->count();
        $totalAttempts = 20;

        return view('tka.index', compact(
            'jenjangPendidikan',
            'jenisMataPelajaran',
            'mataPelajaran',
            'questions',
            'currentQuestion',
            'currentQuestionNumber',
            'totalQuestions',
            'totalAttempts'
        ));
    }
}
