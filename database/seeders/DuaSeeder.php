<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dua;

class DuaSeeder extends Seeder
{
    public function run()
    {
        // Ramadan Duas
        Dua::create([
            'category' => 'Ramadan',
            'subsection' => 'iftar',
            'title' => 'Dua for Breaking Fast',
            'arabic_text' => 'ذَهَبَ الظَّمَأُ وَابْتَلَّتِ الْعُرُوقُ، وَثَبَتَ الأَجْرُ إِنْ شَاءَ اللَّهُ',
            'transliteration' => 'Dhahaba al-zama\', wabtallat al-\'urūq, wa thabata al-ajru in shā\' Allāh',
            'translation' => 'The thirst is gone, the veins are moistened, and the reward is confirmed, if Allah wills.',
            'is_public' => true,
        ]);

        Dua::create([
            'category' => 'Ramadan',
            'subsection' => 'suhoor',
            'title' => 'Dua for Suhoor (Pre-dawn meal)',
            'arabic_text' => 'وَبِصَوْمِ غَدٍ نَّوَيْتُ مِنْ شَهْرِ رَمَضَانَ',
            'transliteration' => 'Wa bisawmi ghadin nawaytu min shahri Ramadan',
            'translation' => 'I intend to keep the fast for tomorrow in the month of Ramadan.',
            'is_public' => true,
        ]);

        Dua::create([
            'category' => 'Ramadan',
            'subsection' => 'laylatul_qadr',
            'title' => 'Dua for Laylatul Qadr',
            'arabic_text' => 'اللَّهُمَّ إِنَّكَ عَفُوٌّ تُحِبُّ الْعَفْوَ فَاعْفُ عَنِّي',
            'transliteration' => 'Allāhumma innaka \'afuwwun tuḥibbu al-\'afwa fa\'fu \'annī',
            'translation' => 'O Allah, You are Forgiving and love forgiveness, so forgive me.',
            'is_public' => true,
        ]);

        // General Duas
        Dua::create([
            'category' => 'General',
            'subsection' => 'morning',
            'title' => 'Morning Dua',
            'arabic_text' => 'أَصْبَحْنَا وَأَصْبَحَ الْمُلْكُ لِلَّهِ، وَالْحَمْدُ لِلَّهِ',
            'transliteration' => 'Aṣbaḥnā wa aṣbaḥa al-mulku lillāh, wal-ḥamdu lillāh',
            'translation' => 'We have entered morning and the dominion belongs to Allah, and all praise is for Allah.',
            'is_public' => true,
        ]);

        Dua::create([
            'category' => 'General',
            'subsection' => 'evening',
            'title' => 'Evening Dua',
            'arabic_text' => 'أَمْسَيْنَا وَأَمْسَى الْمُلْكُ لِلَّهِ، وَالْحَمْدُ لِلَّهِ',
            'transliteration' => 'Amsaynā wa amsā al-mulku lillāh, wal-ḥamdu lillāh',
            'translation' => 'We have entered evening and the dominion belongs to Allah, and all praise is for Allah.',
            'is_public' => true,
        ]);

        Dua::create([
            'category' => 'General',
            'subsection' => 'sleep',
            'title' => 'Before Sleep',
            'arabic_text' => 'بِاسْمِكَ اللَّهُمَّ أَمُوتُ وَأَحْيَا',
            'transliteration' => 'Bismika Allāhumma amūtu wa aḥyā',
            'translation' => 'In Your name, O Allah, I die and I live.',
            'is_public' => true,
        ]);

        // Occasion Duas
        Dua::create([
            'category' => 'Occasion',
            'subsection' => 'after_meal',
            'title' => 'After Finishing Meal',
            'arabic_text' => 'الْحَمْدُ لِلَّهِ الَّذِي أَطْعَمَنِي هَذَا وَرَزَقَنِيهِ مِنْ غَيْرِ حَوْلٍ مِنِّي وَلَا قُوَّةٍ',
            'transliteration' => 'Al-ḥamdu lillāhi alladhī aṭ\'amanī hādhā wa razaqanīhi min ghayri ḥawlin minnī wa lā quwwah',
            'translation' => 'All praise is to Allah Who has given me this food and provided it for me without any strength or power on my part.',
            'is_public' => true,
        ]);

        Dua::create([
            'category' => 'Occasion',
            'subsection' => 'arafat',
            'title' => 'Dua at Arafat (1)',
            'arabic_text' => 'لَا إِلَهَ إِلَّا اللَّهُ وَحْدَهُ لَا شَرِيكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ، وَهُوَ عَلَى كُلِّ شَيْءٍ قَدِيرٌ',
            'transliteration' => 'Lā ilāha illā Allāh waḥdahu lā sharīka lah, lahu al-mulku wa lahu al-ḥamd, wa huwa \'alā kulli shay\'in qadīr',
            'translation' => 'There is no deity but Allah alone, with no partner. To Him belongs the dominion and to Him belongs all praise, and He is able to do all things.',
            'is_public' => true,
        ]);

        Dua::create([
            'category' => 'Occasion',
            'subsection' => 'arafat',
            'title' => 'Dua at Arafat (2)',
            'arabic_text' => 'اللَّهُمَّ إِنِّي أَسْأَلُكَ الْجَنَّةَ وَأَعُوذُ بِكَ مِنَ النَّارِ',
            'transliteration' => 'Allāhumma innī as\'aluka al-jannah wa a\'ūdhu bika min an-nār',
            'translation' => 'O Allah, I ask You for Paradise and I seek refuge in You from the Fire.',
            'is_public' => true,
        ]);

        Dua::create([
            'category' => 'Occasion',
            'subsection' => 'arafat',
            'title' => 'Dua at Arafat (3)',
            'arabic_text' => 'رَبَّنَا آتِنَا فِي الدُّنْيَا حَسَنَةً وَفِي الْآخِرَةِ حَسَنَةً وَقِنَا عَذَابَ النَّارِ',
            'transliteration' => 'Rabbanā ātinā fī ad-dunyā ḥasanah wa fī al-ākhirati ḥasanah wa qinā \'adhāb an-nār',
            'translation' => 'Our Lord, give us good in this world and good in the Hereafter, and save us from the punishment of the Fire.',
            'is_public' => true,
        ]);

        Dua::create([
            'category' => 'Occasion',
            'subsection' => 'arafat',
            'title' => 'Dua at Arafat (4)',
            'arabic_text' => 'اللَّهُمَّ اغْفِرْ لِي ذَنْبِي كُلَّهُ، دِقَّهُ وَجِلَّهُ، وَأَوَّلَهُ وَآخِرَهُ، وَعَلَانِيَتَهُ وَسِرَّهُ',
            'transliteration' => 'Allāhumma ighfir lī dhanbī kullahu, diqqahu wa jillahu, wa awwalahu wa ākhirahu, wa \'alāniyatahu wa sirrahu',
            'translation' => 'O Allah, forgive all my sins, the small and the great, the first and the last, the apparent and the hidden.',
            'is_public' => true,
        ]);

        Dua::create([
            'category' => 'Occasion',
            'subsection' => 'travel',
            'title' => 'Before Traveling',
            'arabic_text' => 'سُبْحَانَ الَّذِي سَخَّرَ لَنَا هَذَا وَمَا كُنَّا لَهُ مُقْرِنِينَ، وَإِنَّا إِلَى رَبِّنَا لَمُنْقَلِبُونَ',
            'transliteration' => 'Subḥāna alladhī sakhkhara lanā hādhā wa mā kunnā lahu muqrinīn, wa innā ilā rabbinā lamunqalibūn',
            'translation' => 'Glory is to Him Who has subjected this to us, and we could never have it by our efforts. Surely, to our Lord we are returning.',
            'is_public' => true,
        ]);

        Dua::create([
            'category' => 'Occasion',
            'subsection' => 'rain',
            'title' => 'When it Rains',
            'arabic_text' => 'اللَّهُمَّ صَيِّبًا نَافِعًا',
            'transliteration' => 'Allāhumma ṣayyiban nāfi\'ā',
            'translation' => 'O Allah, make it a beneficial rain.',
            'is_public' => true,
        ]);
    }
}
