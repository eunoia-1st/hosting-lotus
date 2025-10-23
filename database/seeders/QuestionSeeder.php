<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\QuestionOption;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        // Seeder Kategori Pelayanan
        Question::create([
            'question_category_id' => 1,
            'question_text' => 'Menurut Anda, apa yang paling berkesan dari pelayanan kami?',
            'question_type' => 'text',
        ]);


        $question1 = Question::create([
            'question_category_id' => 1,
            'question_text' => 'Berikan rating keramahan staff di restoran kami',
            'question_type' => 'option',
        ]);

        $question1->question_options()->createMany([
            ['question_value' => 'Sangat Ramah'],
            ['question_value' => 'Biasa'],
            ['question_value' => 'Kasar'],
        ]);

        $question2 = Question::create([
            'question_category_id' => 1,
            'question_text' => 'Layanan apa yang menurut Anda perlu ditingkatkan?',
            'question_type' => 'checkbox',
        ]);

        $question2->question_options()->createMany([
            ['question_value' => 'Kecepatan Pelayanan'],
            ['question_value' => 'Penguasaan menu oleh Staff'],
            ['question_value' => 'Keramahanan Staf'],
            ['question_value' => 'Kenyamanan restoran'],
        ]);

        Question::create([
            'question_category_id' => 1,
            'question_text' => 'Saran Anda untuk meningkatkan pelayanan kami.',
            'question_type' => 'text',
        ]);

        // Seeder Kategori Kebersihan
        $question3 = Question::create([
            'question_category_id' => 2,
            'question_text' => 'Sebearapa puas Anda dengan kebersihan Restoran Kami?',
            'question_type' => 'option',
        ]);

        $question3->question_options()->createMany([
            ['question_value' => 'Sangat Puas'],
            ['question_value' => 'Cukup'],
            ['question_value' => 'Tidak Puas'],
        ]);


        Question::create([
            'question_category_id' => 2,
            'question_text' => 'Berikan saran dan komentar terkait kebersihan di Restoran Kami.',
            'question_type' => 'text',
        ]);

        $question4 = Question::create([
            'question_category_id' => 2,
            'question_text' => 'Seberapa sering Anda melihat petugas kebersihan membersihkan area selama kunjungan?',
            'question_type' => 'option',
        ]);

        $question4->question_options()->createMany([
            ['question_value' => 'Sering'],
            ['question_value' => 'Jarang'],
            ['question_value' => 'Tidak Pernah'],
        ]);

        $question5 = Question::create([
            'question_category_id' => 2,
            'question_text' => 'Area mana saja yang perlu perhatian lebih? (Pilih semua yang relevan)',
            'question_type' => 'checkbox',
        ]);

        $question5->question_options()->createMany([
            ['question_value' => 'Meja dan Kursi'],
            ['question_value' => 'Lantai dan Karpet'],
            ['question_value' => 'Kamar Mandi'],
            ['question_value' => 'Tempat Sampah'],
        ]);

        // Kategori Suasana
        $question6 = question::create([
            'question_category_id' => 3,
            'question_text' => 'bagaimana menurut anda kenyamanan suasana restoran secara umum?',
            'question_type' => 'option',
        ]);

        $question6->question_options()->createmany([
            ['question_value' => 'sangat nyaman'],
            ['question_value' => 'biasa aja'],
            ['question_value' => 'tidak nyaman'],
        ]);

        $question7 = question::create([
            'question_category_id' => 3,
            'question_text' => 'Faktor apa yang paling berpengaruh pada kenyamanan Anda?',
            'question_type' => 'checkbox',
        ]);

        $question7->question_options()->createmany([
            ['question_value' => 'Pencahayaan'],
            ['question_value' => 'Musik'],
            ['question_value' => 'Tata ruang'],
            ['question_value' => 'Aroma'],
            ['question_value' => 'Kebisingan'],
        ]);

        $question8 = question::create([
            'question_category_id' => 3,
            'question_text' => 'Apakah restoran ini cocok untuk makan bersama keluarga/teman?',
            'question_type' => 'option',
        ]);

        $question8->question_options()->createmany([
            ['question_value' => 'Sangat Cocok'],
            ['question_value' => 'Kurang Cocok'],
            ['question_value' => 'Tidak Cocok'],
        ]);

        Question::create([
            'question_category_id' => 3,
            'question_text' => 'Jelaskan kesan Anda tentang suasana restoran kami.',
            'question_type' => 'text',
        ]);

        // Kategori Menu
        $question9 = question::create([
            'question_category_id' => 4,
            'question_text' => 'Bagaimana menurut Anda variasi pilihan menu yang tersedia?',
            'question_type' => 'option',
        ]);

        $question9->question_options()->createmany([
            ['question_value' => 'Sangat Bervariasi'],
            ['question_value' => 'Cukup Bervariasi'],
            ['question_value' => 'Sangat Terbatas'],
        ]);

        Question::create([
            'question_category_id' => 4,
            'question_text' => 'Menu apa yang menurut Anda paling berkesar?',
            'question_type' => 'text',
        ]);

        $question10 = question::create([
            'question_category_id' => 4,
            'question_text' => 'Apakah tersedia menu yang sesuai dengan kebutuhan/diet Anda?',
            'question_type' => 'checkbox',
        ]);

        $question10->question_options()->createmany([
            ['question_value' => 'Vegetarian'],
            ['question_value' => 'Vegan'],
            ['question_value' => 'Halal'],
            ['question_value' => 'Gluten-free'],
            ['question_value' => 'Tidak ada yang sesuai'],
        ]);

        $question11 = question::create([
            'question_category_id' => 4,
            'question_text' => 'Bagaimana penilaian Anda terhadap rasa makanan/minuman?',
            'question_type' => 'option',
        ]);

        $question11->question_options()->createmany([
            ['question_value' => 'Sangat Enak'],
            ['question_value' => 'Biasa aja'],
            ['question_value' => 'Tidak enak'],
        ]);

        // Kategori Keseluruhan
        $question12 = question::create([
            'question_category_id' => 6,
            'question_text' => 'Secara keseluruhan, sebarapa puas Anda dengan pengalaman makan di sini?',
            'question_type' => 'option',
        ]);

        $question12->question_options()->createmany([
            ['question_value' => 'Sangat Puas'],
            ['question_value' => 'Puas'],
            ['question_value' => 'Cukup Puas'],
            ['question_value' => 'Tidak Puas'],
        ]);

        $question13 = question::create([
            'question_category_id' => 6,
            'question_text' => 'Apakah Anda ingin berkunjung kembali ke restoran ini?',
            'question_type' => 'option',
        ]);

        $question13->question_options()->createmany([
            ['question_value' => 'Iya'],
            ['question_value' => 'Mungkin'],
            ['question_value' => 'Tidak'],
        ]);

        $question14 = question::create([
            'question_category_id' => 6,
            'question_text' => 'Hal apa yang membuat Anda paling puas di restoran ini?',
            'question_type' => 'checkbox',
        ]);

        $question14->question_options()->createmany([
            ['question_value' => 'Makanan'],
            ['question_value' => 'Minuman'],
            ['question_value' => 'Pelayanan'],
            ['question_value' => 'Suasana'],
            ['question_value' => 'Harga'],
        ]);

        Question::create([
            'question_category_id' => 6,
            'question_text' => 'Berikan masukan Anda bagaimana kami bisa meningkatkan resstoraan Kami?',
            'question_type' => 'text',
        ]);

        $question15 = question::create([
            'question_category_id' => 6,
            'question_text' => 'Apakah Anda akan merekomendasikan restoran ini kepada keluarga/teman?',
            'question_type' => 'checkbox',
        ]);

        $question15->question_options()->createmany([
            ['question_value' => 'Iya'],
            ['question_value' => 'Tidak'],
            ['question_value' => 'Mungkin'],
        ]);
    }
}
