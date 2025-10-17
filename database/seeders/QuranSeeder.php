<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Surah Al-Fatiha (The Opening) - Complete Chapter
        $alFatiha = [
            [
                'surah' => 1,
                'ayah' => 1,
                'arabic_text' => 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ',
                'translation' => 'In the name of Allah, the Entirely Merciful, the Especially Merciful.',
                'transliteration' => 'Bismillah ir-Rahman ir-Raheem',
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'surah' => 1,
                'ayah' => 2,
                'arabic_text' => 'الْحَمْدُ لِلَّهِ رَبِّ الْعَالَمِينَ',
                'translation' => 'All praise is due to Allah, Lord of the worlds.',
                'transliteration' => 'Alhamdu lillahi rabbil aalameen',
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'surah' => 1,
                'ayah' => 3,
                'arabic_text' => 'الرَّحْمَٰنِ الرَّحِيمِ',
                'translation' => 'The Entirely Merciful, the Especially Merciful.',
                'transliteration' => 'Ar-Rahman ir-Raheem',
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'surah' => 1,
                'ayah' => 4,
                'arabic_text' => 'مَالِكِ يَوْمِ الدِّينِ',
                'translation' => 'Sovereign of the Day of Recompense.',
                'transliteration' => 'Maliki yawmid deen',
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'surah' => 1,
                'ayah' => 5,
                'arabic_text' => 'إِيَّاكَ نَعْبُدُ وَإِيَّاكَ نَسْتَعِينُ',
                'translation' => 'It is You we worship and You we ask for help.',
                'transliteration' => 'Iyyaka na\'budu wa iyyaka nasta\'een',
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'surah' => 1,
                'ayah' => 6,
                'arabic_text' => 'اهْدِنَا الصِّرَاطَ الْمُسْتَقِيمَ',
                'translation' => 'Guide us to the straight path.',
                'transliteration' => 'Ihdinas siratal mustaqeem',
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'surah' => 1,
                'ayah' => 7,
                'arabic_text' => 'صِرَاطَ الَّذِينَ أَنْعَمْتَ عَلَيْهِمْ غَيْرِ الْمَغْضُوبِ عَلَيْهِمْ وَلَا الضَّالِّينَ',
                'translation' => 'The path of those upon whom You have bestowed favor, not of those who have evoked [Your] anger or of those who are astray.',
                'transliteration' => 'Siratal latheena an\'amta alayhim ghayril maghdubi alayhim walad dalleen',
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Surah Al-Ikhlas (The Sincerity) - Complete Chapter
        $alIkhlas = [
            [
                'surah' => 112,
                'ayah' => 1,
                'arabic_text' => 'قُلْ هُوَ اللَّهُ أَحَدٌ',
                'translation' => 'Say, "He is Allah, [who is] One.',
                'transliteration' => 'Qul huwa Allahu ahad',
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'surah' => 112,
                'ayah' => 2,
                'arabic_text' => 'اللَّهُ الصَّمَدُ',
                'translation' => 'Allah, the Eternal Refuge.',
                'transliteration' => 'Allahu assamad',
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'surah' => 112,
                'ayah' => 3,
                'arabic_text' => 'لَمْ يَلِدْ وَلَمْ يُولَدْ',
                'translation' => 'He neither begets nor is born.',
                'transliteration' => 'Lam yalid walam yoolad',
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'surah' => 112,
                'ayah' => 4,
                'arabic_text' => 'وَلَمْ يَكُن لَّهُ كُفُوًا أَحَدٌ',
                'translation' => 'Nor is there to Him any equivalent."',
                'transliteration' => 'Walam yakul lahu kufuwan ahad',
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Ayat al-Kursi (Verse of the Throne) - Most famous verse
        $ayatulKursi = [
            [
                'surah' => 2,
                'ayah' => 255,
                'arabic_text' => 'اللَّهُ لَا إِلَٰهَ إِلَّا هُوَ الْحَيُّ الْقَيُّومُ ۚ لَا تَأْخُذُهُ سِنَةٌ وَلَا نَوْمٌ ۚ لَهُ مَا فِي السَّمَاوَاتِ وَمَا فِي الْأَرْضِ ۗ مَن ذَا الَّذِي يَشْفَعُ عِندَهُ إِلَّا بِإِذْنِهِ ۚ يَعْلَمُ مَا بَيْنَ أَيْدِيهِمْ وَمَا خَلْفَهُمْ ۖ وَلَا يُحِيطُونَ بِشَيْءٍ مِّنْ عِلْمِهِ إِلَّا بِمَا شَاءَ ۚ وَسِعَ كُرْسِيُّهُ السَّمَاوَاتِ وَالْأَرْضَ ۖ وَلَا يَئُودُهُ حِفْظُهُمَا ۚ وَهُوَ الْعَلِيُّ الْعَظِيمُ',
                'translation' => 'Allah - there is no deity except Him, the Ever-Living, the Sustainer of existence. Neither drowsiness overtakes Him nor sleep. To Him belongs whatever is in the heavens and whatever is on the earth. Who is it that can intercede with Him except by His permission? He knows what is before them and what will be after them, and they encompass not a thing of His knowledge except for what He wills. His Kursi extends over the heavens and the earth, and their preservation tires Him not. And He is the Most High, the Most Great.',
                'transliteration' => 'Allahu la ilaha illa huwa alhayyu alqayyoomu la ta\'khuthuhu sinatun wala nawmun lahu ma fee alssamawati wama fee al-ardi man tha allathee yashfaAAu AAindahu illa bi-ithnihi yaAAlamu ma bayna aydeehim wama khalfahum wala yuheetoona bishay-in min AAilmihi illa bima shaa wasiAAa kursiyyuhu alssamawati waal-arda wala yaooduhu hifthuhuma wahuwa alAAaliyyu alAAatheemu',
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Popular verses about mercy, guidance, and faith
        $popularVerses = [
            [
                'surah' => 2,
                'ayah' => 286,
                'arabic_text' => 'لَا يُكَلِّفُ اللَّهُ نَفْسًا إِلَّا وُسْعَهَا',
                'translation' => 'Allah does not burden a soul beyond that it can bear.',
                'transliteration' => 'La yukallifu Allahu nafsan illa wusAAaha',
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'surah' => 13,
                'ayah' => 28,
                'arabic_text' => 'أَلَا بِذِكْرِ اللَّهِ تَطْمَئِنُّ الْقُلُوبُ',
                'translation' => 'Verily, in the remembrance of Allah do hearts find rest.',
                'transliteration' => 'Ala bithikri Allahi tatma-innu alquloobu',
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'surah' => 94,
                'ayah' => 5,
                'arabic_text' => 'فَإِنَّ مَعَ الْعُسْرِ يُسْرًا',
                'translation' => 'For indeed, with hardship [will be] ease.',
                'transliteration' => 'Fa-inna maAAa alAAusri yusran',
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'surah' => 94,
                'ayah' => 6,
                'arabic_text' => 'إِنَّ مَعَ الْعُسْرِ يُسْرًا',
                'translation' => 'Indeed, with hardship [will be] ease.',
                'transliteration' => 'Inna maAAa alAAusri yusran',
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'surah' => 3,
                'ayah' => 159,
                'arabic_text' => 'فَاعْفُ عَنْهُمْ وَاسْتَغْفِرْ لَهُمْ',
                'translation' => 'So pardon them and ask forgiveness for them.',
                'transliteration' => 'FaAAfu AAanhum waistaghfir lahum',
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Merge all verses
        $allVerses = array_merge($alFatiha, $alIkhlas, $ayatulKursi, $popularVerses);

        // Insert in batches
        foreach (array_chunk($allVerses, 50) as $chunk) {
            DB::table('quran_verses')->insert($chunk);
        }

        $this->command->info('✅ Successfully seeded ' . count($allVerses) . ' Quran verses!');
        $this->command->info('   📖 Surah Al-Fatiha (1:1-7) - 7 verses');
        $this->command->info('   📖 Surah Al-Ikhlas (112:1-4) - 4 verses');
        $this->command->info('   📖 Ayat al-Kursi (2:255) - 1 verse');
        $this->command->info('   📖 Popular verses - 5 verses');
    }
}
