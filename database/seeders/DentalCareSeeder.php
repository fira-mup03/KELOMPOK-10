<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DentalCareSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user admin/pasien demo
        User::firstOrCreate(
            ['email' => 'admin@dentalcare.com'],
            [
                'name'     => 'Admin DentalCare',
                'password' => Hash::make('password'),
                'phone'    => '081234567890',
                'gender'   => 'L',
                'address'  => 'Jl. Kesehatan No. 1, Jakarta',
            ]
        );

        User::firstOrCreate(
            ['email' => 'pasien@dentalcare.com'],
            [
                'name'          => 'Budi Santoso',
                'password'      => Hash::make('password'),
                'phone'         => '085678901234',
                'date_of_birth' => '1995-03-15',
                'gender'        => 'L',
                'address'       => 'Jl. Mawar No. 10, Bandung',
            ]
        );

        // Dokter
        $doctors = [
            [
                'name'           => 'drg. Siti Rahayu',
                'specialization' => 'Dokter Gigi Umum',
                'schedules'      => [
                    ['day_of_week' => 1, 'start_time' => '08:00', 'end_time' => '12:00', 'max_quota' => 20],
                    ['day_of_week' => 3, 'start_time' => '13:00', 'end_time' => '17:00', 'max_quota' => 20],
                    ['day_of_week' => 5, 'start_time' => '08:00', 'end_time' => '12:00', 'max_quota' => 20],
                ],
            ],
            [
                'name'           => 'drg. Ahmad Fauzi, Sp.Ort',
                'specialization' => 'Ortodonti (Kawat Gigi)',
                'schedules'      => [
                    ['day_of_week' => 2, 'start_time' => '09:00', 'end_time' => '14:00', 'max_quota' => 15],
                    ['day_of_week' => 4, 'start_time' => '09:00', 'end_time' => '14:00', 'max_quota' => 15],
                ],
            ],
            [
                'name'           => 'drg. Maya Kusuma, Sp.Perio',
                'specialization' => 'Periodonti (Gusi)',
                'schedules'      => [
                    ['day_of_week' => 1, 'start_time' => '13:00', 'end_time' => '17:00', 'max_quota' => 15],
                    ['day_of_week' => 6, 'start_time' => '08:00', 'end_time' => '13:00', 'max_quota' => 15],
                ],
            ],
            [
                'name'           => 'drg. Reza Pratama, Sp.BM',
                'specialization' => 'Bedah Mulut',
                'schedules'      => [
                    ['day_of_week' => 2, 'start_time' => '14:00', 'end_time' => '18:00', 'max_quota' => 10],
                    ['day_of_week' => 5, 'start_time' => '13:00', 'end_time' => '17:00', 'max_quota' => 10],
                ],
            ],
        ];

        foreach ($doctors as $doctorData) {
            $schedules = $doctorData['schedules'];
            unset($doctorData['schedules']);

            $doctor = Doctor::firstOrCreate(
                ['name' => $doctorData['name']],
                $doctorData
            );

            if ($doctor->schedules()->count() === 0) {
                foreach ($schedules as $schedule) {
                    $doctor->schedules()->create($schedule);
                }
            }
        }

        // Artikel Edukasi
        $articles = [
            [
                'title'     => 'Cara Menyikat Gigi yang Benar untuk Kesehatan Optimal',
                'category'  => 'tips',
                'read_time' => 3,
                'content'   => '<p>Menyikat gigi adalah kegiatan sehari-hari yang tampak sederhana, namun banyak orang melakukannya dengan cara yang kurang tepat. Teknik yang benar sangat penting untuk memastikan semua plak dan bakteri terangkat dengan sempurna.</p><h2>Langkah-langkah Menyikat Gigi yang Benar</h2><ol><li><strong>Gunakan sikat gigi berbulu lembut</strong> – Bulu yang keras dapat merusak email gigi dan gusi.</li><li><strong>Oleskan pasta gigi sebesar biji kacang</strong> – Tidak perlu banyak.</li><li><strong>Sikat dengan gerakan memutar kecil</strong> – Arahkan sikat 45 derajat terhadap gusi.</li><li><strong>Lakukan selama 2 menit</strong> – Fokus di setiap kuadran selama 30 detik.</li><li><strong>Jangan lupa lidah</strong> – Bakteri penyebab bau mulut juga bersarang di permukaan lidah.</li></ol><p>Ganti sikat gigi setiap 3 bulan atau saat bulu sikat sudah mekar. Konsultasikan ke dokter gigi secara rutin minimal 6 bulan sekali.</p>',
            ],
            [
                'title'     => 'Gigi Berlubang (Karies): Penyebab, Gejala, dan Cara Pencegahan',
                'category'  => 'penyakit',
                'read_time' => 5,
                'content'   => '<p>Gigi berlubang atau karies adalah salah satu masalah kesehatan gigi yang paling umum di dunia. Kondisi ini terjadi ketika bakteri dalam mulut menghasilkan asam yang merusak lapisan luar gigi (email) secara perlahan.</p><h2>Penyebab Gigi Berlubang</h2><ul><li>Konsumsi makanan dan minuman manis berlebihan</li><li>Tidak menyikat gigi secara rutin dan benar</li><li>Kurangnya fluoride dalam diet</li><li>Mulut kering yang mengurangi produksi air liur</li></ul><h2>Gejala</h2><p>Pada tahap awal, karies mungkin tidak menimbulkan gejala. Seiring perkembangan, muncul rasa ngilu saat minum dingin/panas, nyeri saat menggigit, dan lubang yang terlihat pada gigi.</p><h2>Pencegahan</h2><p>Sikat gigi dua kali sehari, gunakan benang gigi, kurangi makanan manis, minum air berflor, dan lakukan pemeriksaan rutin setiap 6 bulan.</p>',
            ],
            [
                'title'     => 'Manfaat Scaling Gigi untuk Kesehatan Mulut',
                'category'  => 'perawatan',
                'read_time' => 4,
                'content'   => '<p>Scaling atau pembersihan karang gigi adalah prosedur pembersihan profesional yang dilakukan oleh dokter atau perawat gigi untuk menghilangkan plak dan karang gigi (tartar) yang menumpuk di permukaan gigi dan di bawah garis gusi.</p><h2>Mengapa Scaling Penting?</h2><p>Karang gigi tidak dapat dibersihkan hanya dengan menyikat gigi biasa. Jika dibiarkan, karang gigi dapat menyebabkan penyakit gusi (gingivitis dan periodontitis), kerusakan tulang penyangga gigi, dan bau mulut kronis.</p><h2>Seberapa Sering Harus Scaling?</h2><p>Untuk kebanyakan orang, scaling disarankan setiap 6 bulan sekali. Bagi penderita penyakit gusi, mungkin perlu dilakukan setiap 3-4 bulan.</p>',
            ],
            [
                'title'     => 'Makanan Terbaik untuk Menjaga Kesehatan Gigi Anda',
                'category'  => 'nutrisi',
                'read_time' => 4,
                'content'   => '<p>Apa yang Anda makan tidak hanya mempengaruhi kesehatan tubuh secara keseluruhan, tetapi juga berdampak langsung pada kesehatan gigi dan mulut. Pilihan nutrisi yang tepat dapat membantu memperkuat enamel gigi dan mencegah kerusakan.</p><h2>Makanan Terbaik untuk Gigi</h2><ul><li><strong>Produk susu</strong> (keju, yogurt) – Kaya kalsium dan fosfor untuk menguatkan email gigi</li><li><strong>Sayuran hijau</strong> – Kaya kalsium dan asam folat</li><li><strong>Buah apel</strong> – Merangsang produksi air liur dan membersihkan plak</li><li><strong>Wortel & seledri</strong> – Bertindak sebagai sikat alami</li><li><strong>Kacang-kacangan</strong> – Kaya kalsium dan fosfor</li><li><strong>Air putih</strong> – Membantu membilas sisa makanan dan bakteri</li></ul>',
            ],
            [
                'title'     => 'Tips Mengatasi Rasa Takut ke Dokter Gigi',
                'category'  => 'tips',
                'read_time' => 3,
                'content'   => '<p>Rasa takut atau cemas pergi ke dokter gigi (dental anxiety) adalah hal yang sangat umum. Namun, menghindari kunjungan rutin justru dapat membuat masalah gigi semakin parah dan penanganannya semakin kompleks.</p><h2>Cara Mengatasi Kecemasan Dental</h2><ol><li><strong>Komunikasikan rasa takut Anda</strong> – Beritahu dokter gigi Anda sebelum perawatan dimulai</li><li><strong>Buat sinyal berhenti</strong> – Sepakati kode dengan dokter untuk berhenti sejenak jika Anda tidak nyaman</li><li><strong>Teknik pernapasan dalam</strong> – Bernapas perlahan dapat mengurangi kecemasan</li><li><strong>Pilih waktu yang tepat</strong> – Jadwalkan kunjungan di waktu Anda tidak terburu-buru</li><li><strong>Bawa teman</strong> – Dukungan sosial dapat membantu</li></ol>',
            ],
            [
                'title'     => 'Penyakit Gusi: Mengenal Gingivitis dan Periodontitis',
                'category'  => 'penyakit',
                'read_time' => 6,
                'content'   => '<p>Penyakit gusi adalah infeksi serius pada gusi yang merusak jaringan lunak dan, tanpa penanganan yang tepat, dapat menghancurkan tulang yang menopang gigi Anda. Penyakit gusi adalah penyebab utama kehilangan gigi pada orang dewasa.</p><h2>Gingivitis</h2><p>Ini adalah tahap awal penyakit gusi. Ditandai dengan gusi kemerahan, bengkak, dan mudah berdarah saat menyikat gigi. Gingivitis biasanya disebabkan oleh plak dan dapat dibalik dengan perawatan dan kebersihan mulut yang baik.</p><h2>Periodontitis</h2><p>Jika gingivitis tidak ditangani, dapat berkembang menjadi periodontitis. Plak menyebar ke bawah garis gusi, dan racun yang dihasilkan oleh bakteri merangsang respons peradangan kronis.</p>',
            ],
        ];

        foreach ($articles as $article) {
            Article::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($article['title'])],
                array_merge($article, ['is_published' => true])
            );
        }

        $this->command->info('✓ DentalCare seed data berhasil dibuat!');
    }
}
