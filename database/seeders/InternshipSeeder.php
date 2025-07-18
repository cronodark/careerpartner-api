<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Internship;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InternshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $companies = Company::pluck('id')->toArray();

        $staticInternships = [
            [
                'title' => 'Intern Software Engineer (Backend)',
                'description' => 'Membangun dan memelihara API serta layanan backend untuk aplikasi skala besar. Belajar teknologi Go/Java/Node.js.',
                'image_cover' => 'https://placehold.co/600x400?text=Internship',
                'location' => 'Jakarta Selatan, DKI Jakarta',
                'status' => 'open',
                'responsibilities_base' => [
                    'Membantu pengembangan fitur backend baru.',
                    'Menulis kode yang bersih dan teruji.',
                    'Berpartisipasi dalam code review.',
                ],
                'requirements_base' => [
                    'Mahasiswa tingkat akhir/fresh graduate jurusan Teknik Informatika/Ilmu Komputer.',
                    'Memiliki pemahaman dasar tentang algoritma dan struktur data.',
                    'Familiar dengan minimal satu bahasa pemrograman (Python/Java/Node.js).',
                ],
            ],
            [
                'title' => 'Intern Frontend Developer (React/Vue)',
                'description' => 'Berpartisipasi dalam pengembangan antarmuka pengguna interaktif menggunakan React atau Vue.js.',
                'image_cover' => 'https://placehold.co/600x400?text=Internship',
                'location' => 'Bandung, Jawa Barat',
                'status' => 'open',
                'responsibilities_base' => [
                    'Mengembangkan komponen UI menggunakan React/Vue.',
                    'Mengintegrasikan API dengan antarmuka pengguna.',
                    'Memastikan responsivitas desain.',
                ],
                'requirements_base' => [
                    'Mahasiswa tingkat akhir/fresh graduate jurusan terkait.',
                    'Memiliki pengalaman dasar dengan HTML, CSS, JavaScript.',
                    'Familiar dengan React atau Vue.js.',
                ],
            ],
            [
                'title' => 'Intern UI/UX Designer',
                'description' => 'Merancang pengalaman pengguna yang intuitif dan menarik untuk produk digital perusahaan.',
                'image_cover' => 'https://placehold.co/600x400?text=Internship',
                'location' => 'Yogyakarta, DI Yogyakarta',
                'status' => 'closed',
                'responsibilities_base' => [
                    'Melakukan riset pengguna dan analisis kompetitor.',
                    'Membuat wireframe, mockup, dan prototype.',
                    'Menguji usability desain.',
                ],
                'requirements_base' => [
                    'Mahasiswa jurusan Desain Komunikasi Visual/Desain Produk.',
                    'Portofolio desain yang kuat.',
                    'Menguasai Figma/Sketch/Adobe XD.',
                ],
            ],
            [
                'title' => 'Intern Digital Marketing Specialist',
                'description' => 'Membantu tim marketing dalam mengelola kampanye digital, SEO, dan manajemen media sosial.',
                'image_cover' => 'https://placehold.co/600x400?text=Internship',
                'location' => 'Surabaya, Jawa Timur',
                'status' => 'open',
                'responsibilities_base' => [
                    'Membuat konten untuk media sosial.',
                    'Menganalisis performa kampanye.',
                    'Melakukan riset pasar digital.',
                ],
                'requirements_base' => [
                    'Mahasiswa/fresh graduate jurusan Pemasaran/Komunikasi.',
                    'Memiliki pemahaman dasar SEO/SEM.',
                    'Familiar dengan platform media sosial.',
                ],
            ],
            [
                'title' => 'Intern Data Analyst',
                'description' => 'Membantu mengumpulkan, menganalisis, dan menginterpretasi data untuk mendukung pengambilan keputusan bisnis.',
                'image_cover' => 'https://placehold.co/600x400?text=Internship',
                'location' => 'Jakarta Pusat, DKI Jakarta',
                'status' => 'open',
                'responsibilities_base' => [
                    'Membersihkan dan memproses data.',
                    'Membuat laporan dan visualisasi data.',
                    'Mengidentifikasi tren dan insight.',
                ],
                'requirements_base' => [
                    'Mahasiswa/fresh graduate jurusan Matematika/Statistika/Ilmu Komputer.',
                    'Menguasai SQL dan Excel.',
                    'Familiar dengan Python/R.',
                ],
            ],
            [
                'title' => 'Intern Quality Assurance (QA) Engineer',
                'description' => 'Melakukan pengujian perangkat lunak untuk memastikan kualitas dan keandalan produk.',
                'image_cover' => 'https://placehold.co/600x400?text=Internship',
                'location' => 'Jakarta Barat, DKI Jakarta',
                'status' => 'open',
                'responsibilities_base' => [
                    'Membuat test case dan skenario pengujian.',
                    'Melakukan pengujian fungsional dan non-fungsional.',
                    'Melaporkan bug dengan detail.',
                ],
                'requirements_base' => [
                    'Mahasiswa jurusan Teknik Informatika/Sistem Informasi.',
                    'Pemahaman dasar SDLC dan metodologi testing.',
                    'Teliti dan perhatian terhadap detail.',
                ],
            ],
        ];

        foreach ($companies as $companyId) {
            shuffle($staticInternships);
            $internships = array_slice($staticInternships, 0, rand(2, 4));
            foreach ($internships as $internship) {
                Internship::create([
                    'company_id' => $companyId,
                    'title' => $internship['title'],
                    'description' => $internship['description'],
                    'location' => $internship['location'],
                    'image_cover' => $internship['image_cover'],
                    'responsibilities' => json_encode($internship['responsibilities_base']),
                    'requirements' => json_encode($internship['requirements_base']),
                    'status' => $internship['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
