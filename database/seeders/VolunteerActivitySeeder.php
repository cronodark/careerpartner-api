<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\VolunteerActivity;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VolunteerActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $organizations = Organization::pluck('id')->toArray();

        $staticActivities = [
            [
                'title' => 'Relawan Pengajar Literasi Digital',
                'description' => 'Membantu anak-anak muda di daerah terpencil untuk memahami dasar-dasar literasi digital dan keamanan internet.',
                'location' => 'Bandung, Jawa Barat',
                'detail_activity' => 'Kegiatan ini akan dilakukan di beberapa sekolah dasar di Bandung dengan melibatkan relawan dari berbagai latar belakang pendidikan.',
                'status' => 'open',
                'link' => 'https://example.org/daftar-literasi-digital',
                'image_cover' => 'https://example.org/images/literasi-digital.jpg',
            ],
            [
                'title' => 'Aksi Bersih Sampah Pantai Kuta',
                'description' => 'Membersihkan sampah plastik dan limbah lainnya di sepanjang garis pantai Kuta untuk menjaga kebersihan lingkungan laut.',
                'location' => 'Denpasar, Bali',
                'detail_activity' => 'Kegiatan ini akan melibatkan komunitas lokal dan wisatawan untuk bersama-sama menjaga kebersihan pantai.',
                'status' => 'open',
                'link' => 'https://example.org/pantai-bersih-kuta',
                'image_cover' => 'https://placehold.co/600x400?text=Volunteer',
            ],
            [
                'title' => 'Donasi Pakaian Layak Pakai untuk Korban Bencana',
                'description' => 'Mengumpulkan dan mendistribusikan pakaian layak pakai kepada masyarakat yang terdampak bencana alam di Lombok.',
                'location' => 'Lombok, Nusa Tenggara Barat',
                'detail_activity' => 'Kegiatan ini akan dilakukan dengan mengumpulkan pakaian dari berbagai donatur dan mendistribusikannya ke lokasi bencana.',
                'status' => 'completed',
                'link' => 'https://example.org/donasi-pakaian-bencana',
                'image_cover' => 'https://placehold.co/600x400?text=Volunteer',
            ],
            [
                'title' => 'Program Mentor UMKM Lokal',
                'description' => 'Memberikan bimbingan dan pendampingan kepada pelaku UMKM dalam mengembangkan bisnis dan strategi pemasaran digital.',
                'location' => 'Yogyakarta, DI Yogyakarta',
                'detail_activity' => 'Kegiatan ini akan melibatkan mentor dari berbagai bidang bisnis untuk membantu UMKM lokal.',
                'status' => 'open',
                'link' => 'https://example.org/mentor-umkm-lokal',
                'image_cover' => 'https://placehold.co/600x400?text=Volunteer',
            ],
            [
                'title' => 'Kampanye Penanaman Pohon di Lahan Kritis',
                'description' => 'Berpartisipasi dalam kegiatan penanaman ribuan bibit pohon di lahan-lahan yang membutuhkan reboisasi di Kalimantan.',
                'location' => 'Pontianak, Kalimantan Barat',
                'detail_activity' => 'Kegiatan ini akan melibatkan masyarakat lokal dan relawan dari berbagai daerah untuk bersama-sama menanam pohon.',
                'status' => 'open',
                'link' => 'https://example.org/tanam-pohon-kalimantan',
                'image_cover' => 'https://placehold.co/600x400?text=Volunteer',
            ],
            [
                'title' => 'Bakti Sosial Kesehatan Gratis',
                'description' => 'Menyediakan layanan pemeriksaan kesehatan, konsultasi gizi, dan obat-obatan gratis bagi masyarakat kurang mampu di perkotaan.',
                'location' => 'Jakarta Pusat, DKI Jakarta',
                'detail_activity' => 'Kegiatan ini akan dilakukan di beberapa lokasi di Jakarta dengan melibatkan tenaga medis relawan.',
                'status' => 'open',
                'link' => 'https://example.org/baksos-kesehatan-gratis',
                'image_cover' => 'https://placehold.co/600x400?text=Volunteer',
            ],
            [
                'title' => 'Pembersihan Sungai Ciliwung',
                'description' => 'Inisiatif komunitas untuk membersihkan dan merevitalisasi Sungai Ciliwung dari sampah dan polusi.',
                'location' => 'Jakarta Timur, DKI Jakarta',
                'status' => 'open',
                'detail_activity' => 'Kegiatan ini akan melibatkan warga sekitar dan relawan untuk membersihkan sungai secara rutin.',
                'link' => 'https://example.org/ciliwung-bersih',
                'image_cover' => 'https://placehold.co/600x400?text=Volunteer',
            ],
            [
                'title' => 'Workshop Keterampilan Digital untuk Disabilitas',
                'description' => 'Melatih individu dengan disabilitas dalam keterampilan digital dasar seperti pengolahan data dan desain grafis.',
                'location' => 'Surabaya, Jawa Timur',
                'detail_activity' => 'Kegiatan ini akan dilakukan di pusat rehabilitasi dengan melibatkan relawan yang berpengalaman di bidang teknologi.',
                'status' => 'closed',
                'link' => 'https://example.org/digital-disabilitas',
                'image_cover' => 'https://placehold.co/600x400?text=Volunteer',
            ],
        ];

        foreach ($organizations as $organization) {
            shuffle($staticActivities);
            $activities = array_slice($staticActivities, 0, rand(2, 4));
            foreach ($activities as $activity) {
                VolunteerActivity::create([
                    'organization_id' => $organization,
                    'title' => $activity['title'],
                    'description' => $activity['description'],
                    'detail_activity' => $activity['detail_activity'],
                    'location' => $activity['location'],
                    'status' => $activity['status'],
                    'link' => $activity['link'],
                    'image_cover' => $activity['image_cover'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
