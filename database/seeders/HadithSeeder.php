<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HadithSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 40 Hadith Nawawi - The most famous collection for beginners
        $nawawi40 = [
            [
                'collection' => 'nawawi',
                'book' => 'Book 1',
                'text' => 'إِنَّمَا الْأَعْمَالُ بِالنِّيَّاتِ، وَإِنَّمَا لِكُلِّ امْرِئٍ مَا نَوَى - Actions are but by intentions, and every man shall have only that which he intended.',
                'translation' => 'Actions are judged by intentions, so each man will have what he intended.',
                'narrator' => 'Umar ibn al-Khattab',
                'reference' => 'Hadith 1, 40 Hadith Nawawi',
                'meta' => json_encode(['number' => 1, 'grade' => 'Sahih', 'arabic_text' => 'إِنَّمَا الْأَعْمَالُ بِالنِّيَّاتِ، وَإِنَّمَا لِكُلِّ امْرِئٍ مَا نَوَى']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'nawawi',
                'book' => 'Book 1',
                'text' => 'بُنِيَ الْإِسْلَامُ عَلَى خَمْسٍ - Islam is built upon five: the testimony that there is no god but Allah and that Muhammad is the Messenger of Allah, the establishment of prayer, the giving of zakah, the pilgrimage to the House, and the fast of Ramadan.',
                'translation' => 'Islam has been built on five pillars: testifying that there is no god but Allah and that Muhammad is the Messenger of Allah, performing the prayers, paying the zakat, making the pilgrimage to the House, and fasting in Ramadan.',
                'narrator' => 'Abdullah ibn Umar',
                'reference' => 'Hadith 2, 40 Hadith Nawawi',
                'meta' => json_encode(['number' => 2, 'grade' => 'Sahih', 'arabic_text' => 'بُنِيَ الْإِسْلَامُ عَلَى خَمْسٍ']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'nawawi',
                'book' => 'Book 1',
                'text' => 'الْإِيمَانُ بِضْعٌ وَسَبْعُونَ شُعْبَةً - Faith has over seventy branches, the most excellent of which is the declaration that there is no god but Allah, and the humblest of which is the removal of harm from the road, and modesty is a branch of faith.',
                'translation' => 'Faith consists of more than sixty branches, the highest of which is the testimony that there is no god but Allah, and the lowest of which is removing harmful things from the road, and modesty is a branch of faith.',
                'narrator' => 'Abu Huraira',
                'reference' => 'Hadith 3, 40 Hadith Nawawi',
                'meta' => json_encode(['number' => 3, 'grade' => 'Sahih', 'arabic_text' => 'الْإِيمَانُ بِضْعٌ وَسَبْعُونَ شُعْبَةً']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'nawawi',
                'book' => 'Book 1',
                'text' => 'الْحَلَالُ بَيِّنٌ، وَالْحَرَامُ بَيِّنٌ، وَبَيْنَهُمَا أُمُورٌ مُشْتَبِهَاتٌ - That which is lawful is clear and that which is unlawful is clear, and between the two of them are doubtful matters.',
                'translation' => 'The lawful is clear and the unlawful is clear, and between them are matters unclear that are unknown to most people.',
                'narrator' => 'Al-Numan ibn Bashir',
                'reference' => 'Hadith 6, 40 Hadith Nawawi',
                'meta' => json_encode(['number' => 6, 'grade' => 'Sahih', 'arabic_text' => 'الْحَلَالُ بَيِّنٌ، وَالْحَرَامُ بَيِّنٌ']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'nawawi',
                'book' => 'Book 1',
                'text' => 'الدِّينُ النَّصِيحَةُ - Religion is sincerity.',
                'translation' => 'The religion is sincerity, good counsel, and advice.',
                'narrator' => 'Tamim al-Dari',
                'reference' => 'Hadith 7, 40 Hadith Nawawi',
                'meta' => json_encode(['number' => 7, 'grade' => 'Sahih', 'arabic_text' => 'الدِّينُ النَّصِيحَةُ']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Sahih Bukhari - Most authentic collection
        $bukhari = [
            [
                'collection' => 'bukhari',
                'book' => 'Book of Revelation',
                'text' => 'إِنَّمَا الأَعْمَالُ بِالنِّيَّاتِ - The deeds are considered by the intentions, and a person will get the reward according to his intention.',
                'translation' => 'Actions are according to intentions, and everyone will get what was intended.',
                'narrator' => 'Umar ibn al-Khattab',
                'reference' => 'Sahih Bukhari 1',
                'meta' => json_encode(['number' => 1, 'grade' => 'Sahih', 'arabic_text' => 'إِنَّمَا الأَعْمَالُ بِالنِّيَّاتِ']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'bukhari',
                'book' => 'Book of Belief',
                'text' => 'بُنِيَ الإِسْلاَمُ عَلَى خَمْسٍ - Islam is based on five principles: To testify that none has the right to be worshipped but Allah and Muhammad is the Messenger of Allah, to offer prayers perfectly, to pay the Zakat, to perform Hajj, and to observe fast during the month of Ramadan.',
                'translation' => 'Islam is built upon five things: testifying that there is no god but Allah and that Muhammad is the Messenger of Allah, establishing prayer, giving zakah, pilgrimage to the House, and fasting in Ramadan.',
                'narrator' => 'Abdullah ibn Umar',
                'reference' => 'Sahih Bukhari 8',
                'meta' => json_encode(['number' => 8, 'grade' => 'Sahih', 'arabic_text' => 'بُنِيَ الإِسْلاَمُ عَلَى خَمْسٍ']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'bukhari',
                'book' => 'Book of Knowledge',
                'text' => 'مَنْ يُرِدِ اللَّهُ بِهِ خَيْرًا يُفَقِّهْهُ فِي الدِّينِ - If Allah wants to do good to a person, He makes him comprehend the religion.',
                'translation' => 'Whoever Allah wants good for, He gives him understanding of the religion.',
                'narrator' => 'Muawiya',
                'reference' => 'Sahih Bukhari 71',
                'meta' => json_encode(['number' => 71, 'grade' => 'Sahih', 'arabic_text' => 'مَنْ يُرِدِ اللَّهُ بِهِ خَيْرًا يُفَقِّهْهُ فِي الدِّينِ']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Sahih Muslim - Second most authentic collection
        $muslim = [
            [
                'collection' => 'muslim',
                'book' => 'Book of Faith',
                'text' => 'إِنَّمَا الأَعْمَالُ بِالنِّيَّةِ - Actions are judged by intentions.',
                'translation' => 'The reward of deeds depends upon the intentions and every person will get the reward according to what he has intended.',
                'narrator' => 'Umar ibn al-Khattab',
                'reference' => 'Sahih Muslim 1907',
                'meta' => json_encode(['number' => 1, 'grade' => 'Sahih', 'arabic_text' => 'إِنَّمَا الأَعْمَالُ بِالنِّيَّةِ']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'muslim',
                'book' => 'Book of Faith',
                'text' => 'لاَ يُؤْمِنُ أَحَدُكُمْ حَتَّى يُحِبَّ لأَخِيهِ مَا يُحِبُّ لِنَفْسِهِ - None of you truly believes until he loves for his brother what he loves for himself.',
                'translation' => 'None of you will have faith until he loves for his brother what he loves for himself.',
                'narrator' => 'Anas ibn Malik',
                'reference' => 'Sahih Muslim 45',
                'meta' => json_encode(['number' => 45, 'grade' => 'Sahih', 'arabic_text' => 'لاَ يُؤْمِنُ أَحَدُكُمْ حَتَّى يُحِبَّ لأَخِيهِ مَا يُحِبُّ لِنَفْسِهِ']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'muslim',
                'book' => 'Book of Purity',
                'text' => 'الطَّهُورُ شَطْرُ الإِيمَانِ - Cleanliness is half of faith.',
                'translation' => 'Purification is half of faith.',
                'narrator' => 'Abu Malik al-Ashari',
                'reference' => 'Sahih Muslim 223',
                'meta' => json_encode(['number' => 223, 'grade' => 'Sahih', 'arabic_text' => 'الطَّهُورُ شَطْرُ الإِيمَانِ']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Riyadh as-Salihin - Gardens of the Righteous
        $riyadh = [
            [
                'collection' => 'riyadh',
                'book' => 'Book of Good Manners',
                'text' => 'تَبَسُّمُكَ فِي وَجْهِ أَخِيكَ صَدَقَةٌ - Your smile for your brother is a charity.',
                'translation' => 'Smiling in your brothers face is an act of charity.',
                'narrator' => 'Abu Dharr',
                'reference' => 'Riyadh as-Salihin, Book 1, Hadith 1',
                'meta' => json_encode(['number' => 1, 'grade' => 'Hasan', 'arabic_text' => 'تَبَسُّمُكَ فِي وَجْهِ أَخِيكَ صَدَقَةٌ']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'riyadh',
                'book' => 'Book of Good Character',
                'text' => 'أَحَبُّ الأَعْمَالِ إِلَى اللَّهِ أَدْوَمُهَا وَإِنْ قَلَّ - The most beloved deed to Allah is the most regular and constant even if it were little.',
                'translation' => 'The deeds most loved by Allah are those done regularly, even if they are small.',
                'narrator' => 'Aisha',
                'reference' => 'Riyadh as-Salihin, Book 1, Hadith 15',
                'meta' => json_encode(['number' => 15, 'grade' => 'Sahih', 'arabic_text' => 'أَحَبُّ الأَعْمَالِ إِلَى اللَّهِ أَدْوَمُهَا وَإِنْ قَلَّ']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Merge all hadiths
        $allHadiths = array_merge($nawawi40, $bukhari, $muslim, $riyadh);

        // Insert in batches
        foreach (array_chunk($allHadiths, 50) as $chunk) {
            DB::table('hadiths')->insert($chunk);
        }

        $this->command->info('✅ Successfully seeded ' . count($allHadiths) . ' Hadiths!');
        $this->command->info('   📚 40 Hadith Nawawi - 5 hadiths');
        $this->command->info('   📚 Sahih Bukhari - 3 hadiths');
        $this->command->info('   📚 Sahih Muslim - 3 hadiths');
        $this->command->info('   📚 Riyadh as-Salihin - 2 hadiths');
    }
}
