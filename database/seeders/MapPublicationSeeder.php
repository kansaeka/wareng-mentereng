<?php

namespace Database\Seeders;

use App\Models\MapPublication;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MapPublicationSeeder extends Seeder
{
    public function run(): void
    {
        $maps = [
            [
                'title' => 'Peta Bangunan Dusun Wareng',
                'collection_group' => 'Peta Dasar',
                'category' => 'Peta Dasar',
                'description' =>
                'Persebaran bangunan, jalan, perairan, '
                    . 'dan batas wilayah Dusun Wareng.',
                'keywords' =>
                'bangunan gedung rumah jalan wilayah',
                'thumbnail_path' =>
                'images/maps/peta-dasar/'
                    . 'peta-bangunan-dusun-wareng.jpg',
                'file_path' =>
                'downloads/maps/peta-dasar/'
                    . 'peta-bangunan-dusun-wareng.pdf',
                'sort_order' => 1,
            ],
            [
                'title' => 'Peta Foto Udara Dusun Wareng',
                'collection_group' => 'Peta Dasar',
                'category' => 'Peta Dasar',
                'description' =>
                'Kondisi permukaan Dusun Wareng berdasarkan '
                    . 'foto udara dan informasi wilayah.',
                'keywords' =>
                'foto udara citra drone wilayah',
                'thumbnail_path' =>
                'images/maps/peta-dasar/'
                    . 'peta-foto-udara-dusun-wareng.jpg',
                'file_path' =>
                'downloads/maps/peta-dasar/'
                    . 'peta-foto-udara-dusun-wareng.pdf',
                'sort_order' => 2,
            ],
            [
                'title' =>
                'Peta Penutup & Penggunaan Lahan Dusun Wareng',
                'collection_group' => 'Peta Dasar',
                'category' => 'Peta Dasar',
                'description' =>
                'Penutup lahan dan pemanfaatan ruang '
                    . 'di wilayah Dusun Wareng.',
                'keywords' =>
                'penutup penggunaan lahan sawah kebun',
                'thumbnail_path' =>
                'images/maps/peta-dasar/'
                    . 'peta-penutup-penggunaan-lahan-dusun-wareng.jpg',
                'file_path' =>
                'downloads/maps/peta-dasar/'
                    . 'peta-penutup-penggunaan-lahan-dusun-wareng.pdf',
                'sort_order' => 3,
            ],
            [
                'title' =>
                'Peta Penggunaan Lahan Dusun Wareng',
                'collection_group' => 'Peta Dasar',
                'category' => 'Peta Dasar',
                'description' =>
                'Klasifikasi penggunaan lahan terbangun '
                    . 'dan nonterbangun di Dusun Wareng.',
                'keywords' =>
                'penggunaan lahan pertanian permukiman',
                'thumbnail_path' =>
                'images/maps/peta-dasar/'
                    . 'peta-penggunaan-lahan-dusun-wareng.jpg',
                'file_path' =>
                'downloads/maps/peta-dasar/'
                    . 'peta-penggunaan-lahan-dusun-wareng.pdf',
                'sort_order' => 4,
            ],
            [
                'title' =>
                'Peta Batas Administrasi RT/RW Dusun Wareng',
                'collection_group' => 'Peta Dasar',
                'category' => 'Peta Dasar',
                'description' =>
                'Pembagian wilayah RT 1, RT 2, dan RT 3 '
                    . 'di Dusun Wareng.',
                'keywords' =>
                'administrasi batas RT RW wilayah',
                'thumbnail_path' =>
                'images/maps/peta-dasar/'
                    . 'peta-batas-administrasi-rt-rw-dusun-wareng.jpg',
                'file_path' =>
                'downloads/maps/peta-dasar/'
                    . 'peta-batas-administrasi-rt-rw-dusun-wareng.pdf',
                'sort_order' => 5,
            ],
            [
                'title' =>
                'Peta Sarana dan Prasarana Dusun Wareng',
                'collection_group' => 'Peta Dasar',
                'category' => 'Peta Dasar',
                'description' =>
                'Persebaran fasilitas umum serta sarana '
                    . 'dan prasarana Dusun Wareng.',
                'keywords' =>
                'sarpras fasilitas umum pendidikan ibadah',
                'thumbnail_path' =>
                'images/maps/peta-dasar/'
                    . 'peta-sarana-prasarana-dusun-wareng.jpg',
                'file_path' =>
                'downloads/maps/peta-dasar/'
                    . 'peta-sarana-prasarana-dusun-wareng.pdf',
                'sort_order' => 6,
            ],

            [
                'title' =>
                'Peta Area Jangkauan Akses Internet Dusun Wareng',
                'collection_group' => 'Peta Tematik',
                'category' => 'Infrastruktur Digital',
                'description' =>
                'Cakupan internet berdasarkan provider, '
                    . 'BTS, dan tingkat jangkauan sinyal.',
                'keywords' =>
                'internet jaringan sinyal BTS provider WiFi',
                'thumbnail_path' =>
                'images/maps/peta-tematik/'
                    . 'peta-area-jangkauan-akses-internet-dusun-wareng.jpg',
                'file_path' =>
                'downloads/maps/peta-tematik/'
                    . 'peta-area-jangkauan-akses-internet-dusun-wareng.pdf',
                'sort_order' => 7,
            ],
            [
                'title' =>
                'Peta Kelayakan Hunian Berdasarkan Jumlah '
                    . 'Jendela dan Jumlah Penghuni Dusun Wareng',
                'collection_group' => 'Peta Tematik',
                'category' =>
                'Permukiman dan Kesehatan Lingkungan',
                'description' =>
                'Kelayakan hunian berdasarkan perbandingan '
                    . 'jumlah jendela dengan jumlah penghuni.',
                'keywords' =>
                'hunian kesehatan ventilasi jendela penghuni',
                'thumbnail_path' =>
                'images/maps/peta-tematik/'
                    . 'peta-kelayakan-hunian-dusun-wareng.jpg',
                'file_path' =>
                'downloads/maps/peta-tematik/'
                    . 'peta-kelayakan-hunian-dusun-wareng.pdf',
                'sort_order' => 8,
            ],
            [
                'title' =>
                'Peta Kepadatan Penduduk Berdasarkan Jumlah '
                    . 'Jiwa per Unit Bangunan Dusun Wareng',
                'collection_group' => 'Peta Tematik',
                'category' => 'Kependudukan',
                'description' =>
                'Tingkat kepadatan penduduk berdasarkan '
                    . 'jumlah jiwa dalam setiap unit bangunan.',
                'keywords' =>
                'penduduk kepadatan jiwa rumah keluarga',
                'thumbnail_path' =>
                'images/maps/peta-tematik/'
                    . 'peta-kepadatan-penduduk-dusun-wareng.jpg',
                'file_path' =>
                'downloads/maps/peta-tematik/'
                    . 'peta-kepadatan-penduduk-dusun-wareng.pdf',
                'sort_order' => 9,
            ],
            [
                'title' =>
                'Peta Persebaran Golongan Daya Listrik '
                    . 'Dusun Wareng',
                'collection_group' => 'Peta Tematik',
                'category' => 'Infrastruktur dan Utilitas',
                'description' =>
                'Persebaran golongan daya listrik pada '
                    . 'bangunan rumah tangga.',
                'keywords' =>
                'listrik daya PLN 450 900 1300 utilitas',
                'thumbnail_path' =>
                'images/maps/peta-tematik/'
                    . 'peta-persebaran-daya-listrik-dusun-wareng.jpg',
                'file_path' =>
                'downloads/maps/peta-tematik/'
                    . 'peta-persebaran-daya-listrik-dusun-wareng.pdf',
                'sort_order' => 10,
            ],
            [
                'title' =>
                'Peta Mata Pencaharian Penduduk '
                    . '(Kepala Keluarga) Dusun Wareng',
                'collection_group' => 'Peta Tematik',
                'category' => 'Sosial Ekonomi',
                'description' =>
                'Persebaran jenis mata pencaharian '
                    . 'kepala keluarga di Dusun Wareng.',
                'keywords' =>
                'pekerjaan pencaharian petani pedagang '
                    . 'wiraswasta buruh',
                'thumbnail_path' =>
                'images/maps/peta-tematik/'
                    . 'peta-mata-pencaharian-penduduk-dusun-wareng.jpg',
                'file_path' =>
                'downloads/maps/peta-tematik/'
                    . 'peta-mata-pencaharian-penduduk-dusun-wareng.pdf',
                'sort_order' => 11,
            ],
            [
                'title' =>
                'Peta Administratif Bangunan Berdasarkan RT '
                    . 'Dusun Wareng',
                'collection_group' => 'Peta Tematik',
                'category' => 'Administrasi Wilayah',
                'description' =>
                'Pengelompokan bangunan berdasarkan '
                    . 'wilayah RT 1, RT 2, dan RT 3.',
                'keywords' =>
                'administrasi bangunan RT wilayah lingkungan',
                'thumbnail_path' =>
                'images/maps/peta-tematik/'
                    . 'peta-administratif-bangunan-berdasarkan-rt-dusun-wareng.jpg',
                'file_path' =>
                'downloads/maps/peta-tematik/'
                    . 'peta-administratif-bangunan-berdasarkan-rt-dusun-wareng.pdf',
                'sort_order' => 12,
            ],
            [
                'title' =>
                'Peta Persebaran Usaha Milik Warga '
                    . 'Dusun Wareng',
                'collection_group' => 'Peta Tematik',
                'category' => 'Ekonomi Lokal dan UMKM',
                'description' =>
                'Lokasi usaha, perdagangan, jasa, warung, '
                    . 'toko, dan kegiatan ekonomi warga.',
                'keywords' =>
                'UMKM usaha warga ekonomi warung toko jasa',
                'thumbnail_path' =>
                'images/maps/peta-tematik/'
                    . 'peta-persebaran-usaha-milik-warga-dusun-wareng.jpg',
                'file_path' =>
                'downloads/maps/peta-tematik/'
                    . 'peta-persebaran-usaha-milik-warga-dusun-wareng.pdf',
                'sort_order' => 13,
            ],
        ];

        foreach ($maps as $map) {
            MapPublication::updateOrCreate(
                [
                    'slug' => Str::slug($map['title']),
                ],
                [
                    ...$map,
                    'year' => 2026,
                    'format' => 'PDF',
                    'status' => 'Dalam penyusunan',
                    'is_published' => true,
                ]
            );
        }
    }
}
