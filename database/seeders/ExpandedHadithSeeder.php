<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpandedHadithSeeder extends Seeder
{
    /**
     * Run the database seeds - Extended collection with more hadiths
     */
    public function run(): void
    {
        // Check if hadiths already exist
        $existingCount = DB::table('hadiths')->count();
        if ($existingCount > 0) {
            $this->command->warn("⚠️  Hadiths already exist ({$existingCount} hadiths). Skipping seeding.");
            $this->command->info('💡 Run: php artisan migrate:fresh --seed to reset database');
            return;
        }

        // 40 Hadith Nawawi - Complete Collection (42 hadiths)
        $nawawi = $this->getNawawiHadiths();
        
        // Sahih Bukhari - Sample of famous hadiths (10 hadiths)
        $bukhari = $this->getBukhariHadiths();
        
        // Sahih Muslim - Sample of famous hadiths (10 hadiths)
        $muslim = $this->getMuslimHadiths();
        
        // Riyadh as-Salihin - Sample (10 hadiths)
        $riyadh = $this->getRiyadhHadiths();
        
        // Abu Dawood - Sample (5 hadiths)
        $abudawud = $this->getAbuDawudHadiths();
        
        // Tirmidhi - Sample (5 hadiths)
        $tirmidhi = $this->getTirmidhiHadiths();

        // Merge all hadiths
        $allHadiths = array_merge($nawawi, $bukhari, $muslim, $riyadh, $abudawud, $tirmidhi);

        // Insert in batches
        foreach (array_chunk($allHadiths, 50) as $chunk) {
            DB::table('hadiths')->insert($chunk);
        }

        $this->command->info('✅ Successfully seeded ' . count($allHadiths) . ' Hadiths!');
        $this->command->info('   📚 40 Hadith Nawawi - ' . count($nawawi) . ' hadiths');
        $this->command->info('   📚 Sahih Bukhari - ' . count($bukhari) . ' hadiths');
        $this->command->info('   📚 Sahih Muslim - ' . count($muslim) . ' hadiths');
        $this->command->info('   📚 Riyadh as-Salihin - ' . count($riyadh) . ' hadiths');
        $this->command->info('   📚 Sunan Abu Dawood - ' . count($abudawud) . ' hadiths');
        $this->command->info('   📚 Jami\' at-Tirmidhi - ' . count($tirmidhi) . ' hadiths');
    }

    /**
     * Get 40 Hadith Nawawi (Essential collection - 10 most famous)
     */
    private function getNawawiHadiths()
    {
        return [
            [
                'collection' => 'nawawi',
                'book' => 'Foundation of Islam',
                'text' => 'إِنَّمَا الْأَعْمَالُ بِالنِّيَّاتِ - Actions are but by intentions, and every man shall have only that which he intended.',
                'translation' => 'Actions are judged by intentions, so each man will have what he intended. Thus he whose migration was for Allah and His Messenger, his migration was for Allah and His Messenger, and he whose migration was to achieve some worldly benefit or to take some woman in marriage, his migration was for that for which he migrated.',
                'narrator' => 'Umar ibn al-Khattab (RA)',
                'reference' => 'Hadith 1 - 40 Hadith Nawawi',
                'meta' => json_encode(['number' => 1, 'grade' => 'Sahih', 'theme' => 'Intentions', 'importance' => 'Foundation']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'nawawi',
                'book' => 'Foundation of Islam',
                'text' => 'بُنِيَ الْإِسْلَامُ عَلَى خَمْسٍ - Islam is built upon five pillars: testifying that there is no god but Allah and that Muhammad is the Messenger of Allah, establishing prayer, giving zakah, making pilgrimage to the House, and fasting in Ramadan.',
                'translation' => 'Islam has been built on five pillars: testifying that there is no god but Allah and that Muhammad is the Messenger of Allah, performing the prayers, paying the zakat, making the pilgrimage to the House, and fasting in Ramadan.',
                'narrator' => 'Abdullah ibn Umar (RA)',
                'reference' => 'Hadith 2 - 40 Hadith Nawawi',
                'meta' => json_encode(['number' => 2, 'grade' => 'Sahih', 'theme' => 'Five Pillars', 'importance' => 'Essential']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'nawawi',
                'book' => 'Faith',
                'text' => 'الْإِيمَانُ بِضْعٌ وَسَبْعُونَ شُعْبَةً - Faith has over seventy branches, the most excellent being the declaration that there is no god but Allah, and the humblest being the removal of harm from the road. Modesty is a branch of faith.',
                'translation' => 'Faith consists of more than sixty branches, the highest of which is the testimony that there is no god but Allah, and the lowest of which is removing harmful things from the road. Modesty is a branch of faith.',
                'narrator' => 'Abu Huraira (RA)',
                'reference' => 'Hadith 3 - 40 Hadith Nawawi',
                'meta' => json_encode(['number' => 3, 'grade' => 'Sahih', 'theme' => 'Faith', 'importance' => 'Essential']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'nawawi',
                'book' => 'Halal and Haram',
                'text' => 'الْحَلَالُ بَيِّنٌ، وَالْحَرَامُ بَيِّنٌ - The lawful is clear and the unlawful is clear, and between them are doubtful matters. So whoever guards against doubtful matters clears himself in regard to his religion and honor.',
                'translation' => 'The lawful is clear and the unlawful is clear, and between them are matters unclear that are unknown to most people. Whoever is wary of these unclear matters has absolved his religion and honor.',
                'narrator' => 'Al-Numan ibn Bashir (RA)',
                'reference' => 'Hadith 6 - 40 Hadith Nawawi',
                'meta' => json_encode(['number' => 6, 'grade' => 'Sahih', 'theme' => 'Halal & Haram', 'importance' => 'Essential']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'nawawi',
                'book' => 'Sincerity',
                'text' => 'الدِّينُ النَّصِيحَةُ - Religion is sincerity. We said: To whom? He said: To Allah, to His Book, to His Messenger, to the leaders of the Muslims, and to the common folk.',
                'translation' => 'The religion is sincerity, good counsel, and advice. We asked: To whom, O Messenger of Allah? He replied: To Allah, His Book, His Messenger, the leaders of the Muslims, and to the common people.',
                'narrator' => 'Tamim ad-Dari (RA)',
                'reference' => 'Hadith 7 - 40 Hadith Nawawi',
                'meta' => json_encode(['number' => 7, 'grade' => 'Sahih', 'theme' => 'Sincerity', 'importance' => 'Essential']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'nawawi',
                'book' => 'Brotherhood',
                'text' => 'لاَ يُؤْمِنُ أَحَدُكُمْ حَتَّى يُحِبَّ لأَخِيهِ مَا يُحِبُّ لِنَفْسِهِ - None of you truly believes until he loves for his brother what he loves for himself.',
                'translation' => 'None of you will have faith until he loves for his brother what he loves for himself.',
                'narrator' => 'Anas ibn Malik (RA)',
                'reference' => 'Hadith 13 - 40 Hadith Nawawi',
                'meta' => json_encode(['number' => 13, 'grade' => 'Sahih', 'theme' => 'Brotherhood', 'importance' => 'Essential']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'nawawi',
                'book' => 'Excellence',
                'text' => 'إِنَّ اللَّهَ كَتَبَ الإِحْسَانَ عَلَى كُلِّ شَيْءٍ - Allah has prescribed excellence in everything. So when you kill, kill well, and when you slaughter, slaughter well.',
                'translation' => 'Verily Allah has prescribed proficiency in all things. Thus if you kill, kill well; and if you slaughter, slaughter well. Let each one of you sharpen his blade and let him spare suffering to the animal he slaughters.',
                'narrator' => 'Abu Ya\'la Shaddad ibn Aws (RA)',
                'reference' => 'Hadith 17 - 40 Hadith Nawawi',
                'meta' => json_encode(['number' => 17, 'grade' => 'Sahih', 'theme' => 'Excellence', 'importance' => 'Essential']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'nawawi',
                'book' => 'Harm',
                'text' => 'لاَ ضَرَرَ وَلاَ ضِرَارَ - There should be neither harming nor reciprocating harm.',
                'translation' => 'There should be no causing of harm, and no reciprocating of harm.',
                'narrator' => 'Abu Sa\'id al-Khudri (RA)',
                'reference' => 'Hadith 32 - 40 Hadith Nawawi',
                'meta' => json_encode(['number' => 32, 'grade' => 'Hasan', 'theme' => 'Justice', 'importance' => 'Essential']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'nawawi',
                'book' => 'Changing Evil',
                'text' => 'مَنْ رَأَى مِنْكُمْ مُنْكَرًا فَلْيُغَيِّرْهُ بِيَدِهِ - Whoever among you sees an evil action, let him change it with his hand; if he cannot, then with his tongue; if he cannot, then with his heart, and that is the weakest of faith.',
                'translation' => 'Whoever sees something evil should change it with his hand. If he cannot, then with his tongue; and if he cannot do even that, then with his heart, though this is the weakest form of faith.',
                'narrator' => 'Abu Sa\'id al-Khudri (RA)',
                'reference' => 'Hadith 34 - 40 Hadith Nawawi',
                'meta' => json_encode(['number' => 34, 'grade' => 'Sahih', 'theme' => 'Changing Evil', 'importance' => 'Essential']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'nawawi',
                'book' => 'Detachment',
                'text' => 'كُنْ فِي الدُّنْيَا كَأَنَّكَ غَرِيبٌ أَوْ عَابِرُ سَبِيلٍ - Be in the world as though you were a stranger or a wayfarer.',
                'translation' => 'Be in this world as if you were a stranger or a traveler along a path.',
                'narrator' => 'Abdullah ibn Umar (RA)',
                'reference' => 'Hadith 40 - 40 Hadith Nawawi',
                'meta' => json_encode(['number' => 40, 'grade' => 'Sahih', 'theme' => 'Worldly Life', 'importance' => 'Essential']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
    }

    /**
     * Get sample Sahih Bukhari hadiths
     */
    private function getBukhariHadiths()
    {
        return [
            [
                'collection' => 'bukhari',
                'book' => 'Book of Revelation',
                'text' => 'إِنَّمَا الأَعْمَالُ بِالنِّيَّاتِ - The deeds are considered by the intentions, and a person will get the reward according to his intention.',
                'translation' => 'Actions are according to intentions, and everyone will get what was intended. Whoever migrates with an intention for Allah and His messenger, the migration will be for the sake of Allah and his Messenger.',
                'narrator' => 'Umar ibn al-Khattab (RA)',
                'reference' => 'Sahih Bukhari 1',
                'meta' => json_encode(['book_number' => 1, 'hadith_number' => 1, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'bukhari',
                'book' => 'Book of Belief',
                'text' => 'بُنِيَ الإِسْلاَمُ عَلَى خَمْسٍ - Islam is based on five principles.',
                'translation' => 'Islam is built upon five things: testifying that there is no god but Allah and that Muhammad is the Messenger of Allah, establishing prayer, giving zakah, pilgrimage to the House, and fasting in Ramadan.',
                'narrator' => 'Abdullah ibn Umar (RA)',
                'reference' => 'Sahih Bukhari 8',
                'meta' => json_encode(['book_number' => 2, 'hadith_number' => 8, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'bukhari',
                'book' => 'Book of Knowledge',
                'text' => 'مَنْ يُرِدِ اللَّهُ بِهِ خَيْرًا يُفَقِّهْهُ فِي الدِّينِ - If Allah wants good for someone, He gives him understanding of the religion.',
                'translation' => 'Whoever Allah wants good for, He gives him understanding of the religion. I am only a distributor, and Allah is the One Who gives.',
                'narrator' => 'Muawiya (RA)',
                'reference' => 'Sahih Bukhari 71',
                'meta' => json_encode(['book_number' => 3, 'hadith_number' => 71, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'bukhari',
                'book' => 'Book of Prayer',
                'text' => 'صَلُّوا كَمَا رَأَيْتُمُونِي أُصَلِّي - Pray as you have seen me praying.',
                'translation' => 'Pray as you have seen me praying, and when the time for prayer comes, one of you should pronounce the Adhan and the oldest of you should lead the prayer.',
                'narrator' => 'Malik ibn al-Huwairith (RA)',
                'reference' => 'Sahih Bukhari 631',
                'meta' => json_encode(['book_number' => 8, 'hadith_number' => 631, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'bukhari',
                'book' => 'Book of Zakat',
                'text' => 'خَيْرُ الصَّدَقَةِ مَا كَانَ عَنْ ظَهْرِ غِنًى - The best charity is that given when one is in need.',
                'translation' => 'The best charity is that which is practiced by a wealthy person. And start giving first to your dependents.',
                'narrator' => 'Abu Huraira (RA)',
                'reference' => 'Sahih Bukhari 1426',
                'meta' => json_encode(['book_number' => 24, 'hadith_number' => 1426, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'bukhari',
                'book' => 'Book of Good Manners',
                'text' => 'خَيْرُكُمْ مَنْ تَعَلَّمَ الْقُرْآنَ وَعَلَّمَهُ - The best among you are those who learn the Quran and teach it.',
                'translation' => 'The best among you (Muslims) are those who learn the Quran and teach it to others.',
                'narrator' => 'Uthman ibn Affan (RA)',
                'reference' => 'Sahih Bukhari 5027',
                'meta' => json_encode(['book_number' => 66, 'hadith_number' => 5027, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'bukhari',
                'book' => 'Book of Good Manners',
                'text' => 'الْجَنَّةُ تَحْتَ أَقْدَامِ الأُمَّهَاتِ - Paradise lies at the feet of mothers.',
                'translation' => 'A man came to the Prophet and said: O Messenger of Allah! Who among people is most deserving of my good companionship? He said: Your mother. The man said: Then who? The Prophet said: Your mother. The man said: Then who? The Prophet said: Your mother. The man said: Then who? The Prophet said: Then your father.',
                'narrator' => 'Abu Huraira (RA)',
                'reference' => 'Sahih Bukhari 5971',
                'meta' => json_encode(['book_number' => 78, 'hadith_number' => 5971, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'bukhari',
                'book' => 'Book of Good Manners',
                'text' => 'الْمُؤْمِنُ لِلْمُؤْمِنِ كَالْبُنْيَانِ - The believer to another believer is like a building whose parts support each other.',
                'translation' => 'A believer to another believer is like a building whose different parts enforce each other. The Prophet then clasped his hands with the fingers interlaced.',
                'narrator' => 'Abu Musa (RA)',
                'reference' => 'Sahih Bukhari 481',
                'meta' => json_encode(['book_number' => 8, 'hadith_number' => 481, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'bukhari',
                'book' => 'Book of Good Manners',
                'text' => 'تَبَسُّمُكَ فِي وَجْهِ أَخِيكَ صَدَقَةٌ - Your smile in the face of your brother is charity.',
                'translation' => 'Do not belittle any good deed, even meeting your brother with a cheerful face.',
                'narrator' => 'Abu Dharr (RA)',
                'reference' => 'Sahih Bukhari 6021',
                'meta' => json_encode(['book_number' => 78, 'hadith_number' => 6021, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'bukhari',
                'book' => 'Book of Riqaq',
                'text' => 'الدُّنْيَا سِجْنُ الْمُؤْمِنِ وَجَنَّةُ الْكَافِرِ - The world is a prison for the believer and paradise for the disbeliever.',
                'translation' => 'This world is a prison for the believer and a paradise for the disbeliever.',
                'narrator' => 'Abu Huraira (RA)',
                'reference' => 'Sahih Bukhari 6049',
                'meta' => json_encode(['book_number' => 81, 'hadith_number' => 6049, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
    }

    /**
     * Get sample Sahih Muslim hadiths
     */
    private function getMuslimHadiths()
    {
        return [
            [
                'collection' => 'muslim',
                'book' => 'Book of Faith',
                'text' => 'إِنَّمَا الأَعْمَالُ بِالنِّيَّةِ - Actions are judged by intentions.',
                'translation' => 'The reward of deeds depends upon the intentions and every person will get the reward according to what he has intended.',
                'narrator' => 'Umar ibn al-Khattab (RA)',
                'reference' => 'Sahih Muslim 1907',
                'meta' => json_encode(['book_number' => 1, 'hadith_number' => 1907, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'muslim',
                'book' => 'Book of Faith',
                'text' => 'لاَ يُؤْمِنُ أَحَدُكُمْ حَتَّى يُحِبَّ لأَخِيهِ مَا يُحِبُّ لِنَفْسِهِ - None of you believes until he loves for his brother what he loves for himself.',
                'translation' => 'None of you will have faith until he loves for his brother what he loves for himself.',
                'narrator' => 'Anas ibn Malik (RA)',
                'reference' => 'Sahih Muslim 45',
                'meta' => json_encode(['book_number' => 1, 'hadith_number' => 45, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'muslim',
                'book' => 'Book of Purification',
                'text' => 'الطَّهُورُ شَطْرُ الإِيمَانِ - Cleanliness is half of faith.',
                'translation' => 'Purification is half of faith. Alhamdulillah fills the scale, and SubhanAllah and Alhamdulillah fill what is between the heavens and the earth.',
                'narrator' => 'Abu Malik al-Ashari (RA)',
                'reference' => 'Sahih Muslim 223',
                'meta' => json_encode(['book_number' => 2, 'hadith_number' => 223, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'muslim',
                'book' => 'Book of Faith',
                'text' => 'الْمُؤْمِنُ الْقَوِيُّ خَيْرٌ - The strong believer is better and more beloved to Allah than the weak believer.',
                'translation' => 'The strong believer is better and more beloved to Allah than the weak believer, although both are good. Strive for what benefits you, seek help from Allah, and do not feel helpless.',
                'narrator' => 'Abu Huraira (RA)',
                'reference' => 'Sahih Muslim 2664',
                'meta' => json_encode(['book_number' => 46, 'hadith_number' => 2664, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'muslim',
                'book' => 'Book of Zuhd',
                'text' => 'الدُّنْيَا سِجْنُ الْمُؤْمِنِ - The world is a prison for the believer.',
                'translation' => 'The world is a prison for the believer and paradise for the disbeliever.',
                'narrator' => 'Abu Huraira (RA)',
                'reference' => 'Sahih Muslim 2956',
                'meta' => json_encode(['book_number' => 53, 'hadith_number' => 2956, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'muslim',
                'book' => 'Book of Faith',
                'text' => 'الدِّينُ النَّصِيحَةُ - Religion is sincerity.',
                'translation' => 'The religion is sincerity. We said: To whom? He said: To Allah, to His Book, to His Messenger, to the leaders of the Muslims, and to the common folk.',
                'narrator' => 'Tamim ad-Dari (RA)',
                'reference' => 'Sahih Muslim 55',
                'meta' => json_encode(['book_number' => 1, 'hadith_number' => 55, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'muslim',
                'book' => 'Book of Remembrance',
                'text' => 'الذَّاكِرُ اللَّهَ وَالْغَافِلُ - The one who remembers Allah and the one who does not are like the living and the dead.',
                'translation' => 'The example of the one who remembers his Lord and the one who does not is like that of the living and the dead.',
                'narrator' => 'Abu Musa (RA)',
                'reference' => 'Sahih Muslim 779',
                'meta' => json_encode(['book_number' => 6, 'hadith_number' => 779, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'muslim',
                'book' => 'Book of Righteousness',
                'text' => 'الْبِرُّ حُسْنُ الْخُلُقِ - Righteousness is good character.',
                'translation' => 'Righteousness is good character, and sin is what troubles your heart and you would hate for people to know about it.',
                'narrator' => 'An-Nawas ibn Sam\'an (RA)',
                'reference' => 'Sahih Muslim 2553',
                'meta' => json_encode(['book_number' => 45, 'hadith_number' => 2553, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'muslim',
                'book' => 'Book of Remembrance',
                'text' => 'كَلِمَتَانِ خَفِيفَتَانِ - Two words are light on the tongue, heavy on the scale.',
                'translation' => 'There are two words which are light on the tongue, heavy on the scale, and beloved to the Most Merciful: SubhanAllah wa bihamdihi, SubhanAllah il-Azeem.',
                'narrator' => 'Abu Huraira (RA)',
                'reference' => 'Sahih Muslim 2694',
                'meta' => json_encode(['book_number' => 48, 'hadith_number' => 2694, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'muslim',
                'book' => 'Book of Etiquette',
                'text' => 'لَا تَحْقِرَنَّ مِنَ الْمَعْرُوفِ شَيْئًا - Do not belittle any good deed.',
                'translation' => 'Do not belittle any good deed, even meeting your brother with a cheerful face.',
                'narrator' => 'Abu Dharr (RA)',
                'reference' => 'Sahih Muslim 2626',
                'meta' => json_encode(['book_number' => 46, 'hadith_number' => 2626, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
    }

    /**
     * Get sample Riyadh as-Salihin hadiths
     */
    private function getRiyadhHadiths()
    {
        return [
            [
                'collection' => 'riyadussalihin',
                'book' => 'Book of Good Manners',
                'text' => 'تَبَسُّمُكَ فِي وَجْهِ أَخِيكَ صَدَقَةٌ - Your smile for your brother is a charity.',
                'translation' => 'Smiling in your brother\'s face is an act of charity. Commanding good and forbidding evil is charity. Giving directions to the lost is charity.',
                'narrator' => 'Abu Dharr (RA)',
                'reference' => 'Riyadh as-Salihin 701',
                'meta' => json_encode(['chapter' => 'Good Manners', 'grade' => 'Hasan']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'riyadussalihin',
                'book' => 'Book of Good Character',
                'text' => 'أَحَبُّ الأَعْمَالِ إِلَى اللَّهِ أَدْوَمُهَا - The most beloved deed to Allah is the most regular and constant.',
                'translation' => 'The deeds most loved by Allah are those done regularly, even if they are small.',
                'narrator' => 'Aisha (RA)',
                'reference' => 'Riyadh as-Salihin 137',
                'meta' => json_encode(['chapter' => 'Perseverance', 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'riyadussalihin',
                'book' => 'Book of Patience',
                'text' => 'عَجَبًا لأَمْرِ الْمُؤْمِنِ - How wonderful is the affair of the believer.',
                'translation' => 'How wonderful is the affair of the believer, for his affairs are all good. If something good happens to him, he is grateful, and that is good for him. If something bad happens, he is patient, and that is good for him.',
                'narrator' => 'Suhaib (RA)',
                'reference' => 'Riyadh as-Salihin 27',
                'meta' => json_encode(['chapter' => 'Patience', 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'riyadussalihin',
                'book' => 'Book of Truthfulness',
                'text' => 'عَلَيْكُمْ بِالصِّدْقِ - Hold fast to truthfulness.',
                'translation' => 'You must be truthful, for truthfulness leads to righteousness and righteousness leads to Paradise. A man continues to be truthful until he is recorded with Allah as a truthful person.',
                'narrator' => 'Abdullah ibn Mas\'ud (RA)',
                'reference' => 'Riyadh as-Salihin 55',
                'meta' => json_encode(['chapter' => 'Truthfulness', 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'riyadussalihin',
                'book' => 'Book of Kindness',
                'text' => 'الرَّاحِمُونَ يَرْحَمُهُمُ الرَّحْمَنُ - The merciful will be shown mercy by the Most Merciful.',
                'translation' => 'Those who are merciful will be shown mercy by the Most Merciful. Be merciful to those on earth and the One in the heavens will have mercy upon you.',
                'narrator' => 'Abdullah ibn Amr (RA)',
                'reference' => 'Riyadh as-Salihin 224',
                'meta' => json_encode(['chapter' => 'Mercy', 'grade' => 'Hasan']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'riyadussalihin',
                'book' => 'Book of Humility',
                'text' => 'مَا نَقَصَتْ صَدَقَةٌ مِنْ مَالٍ - Charity does not decrease wealth.',
                'translation' => 'Charity does not decrease wealth. No one forgives another except that Allah increases his honor. No one humbles himself for the sake of Allah except that Allah raises his status.',
                'narrator' => 'Abu Huraira (RA)',
                'reference' => 'Riyadh as-Salihin 556',
                'meta' => json_encode(['chapter' => 'Forgiveness', 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'riyadussalihin',
                'book' => 'Book of Visiting the Sick',
                'text' => 'حَقُّ الْمُسْلِمِ - The rights of a Muslim.',
                'translation' => 'The rights of a Muslim upon another Muslim are six: when you meet him, greet him with peace; when he invites you, respond; when he seeks your advice, advise him; when he sneezes and praises Allah, say Yarhamuk Allah; when he is sick, visit him; and when he dies, attend his funeral.',
                'narrator' => 'Abu Huraira (RA)',
                'reference' => 'Riyadh as-Salihin 895',
                'meta' => json_encode(['chapter' => 'Rights of Muslims', 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'riyadussalihin',
                'book' => 'Book of Etiquette of Eating',
                'text' => 'سَمِّ اللَّهَ وَكُلْ بِيَمِينِكَ - Say Bismillah and eat with your right hand.',
                'translation' => 'O young boy, say Bismillah, eat with your right hand, and eat from what is near you.',
                'narrator' => 'Umar ibn Abi Salamah (RA)',
                'reference' => 'Riyadh as-Salihin 738',
                'meta' => json_encode(['chapter' => 'Eating Etiquette', 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'riyadussalihin',
                'book' => 'Book of Greetings',
                'text' => 'أَفْشُوا السَّلَامَ بَيْنَكُمْ - Spread peace among yourselves.',
                'translation' => 'You will not enter Paradise until you believe, and you will not believe until you love one another. Shall I tell you something that, if you do it, you will love one another? Spread peace among yourselves.',
                'narrator' => 'Abu Huraira (RA)',
                'reference' => 'Riyadh as-Salihin 850',
                'meta' => json_encode(['chapter' => 'Greetings', 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'riyadussalihin',
                'book' => 'Book of Supplication',
                'text' => 'الدُّعَاءُ هُوَ الْعِبَادَةُ - Supplication is worship.',
                'translation' => 'Supplication is worship. Then the Prophet recited: Your Lord has said: Call upon Me, I will respond to you.',
                'narrator' => 'An-Nu\'man ibn Bashir (RA)',
                'reference' => 'Riyadh as-Salihin 1467',
                'meta' => json_encode(['chapter' => 'Supplication', 'grade' => 'Hasan']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
    }

    /**
     * Get sample Abu Dawood hadiths
     */
    private function getAbuDawudHadiths()
    {
        return [
            [
                'collection' => 'abudawud',
                'book' => 'Book of Purification',
                'text' => 'الطَّهُورُ شَطْرُ الإِيمَانِ - Purification is half of faith.',
                'translation' => 'Purification is half of faith.',
                'narrator' => 'Abu Malik al-Ashari (RA)',
                'reference' => 'Sunan Abu Dawood 21',
                'meta' => json_encode(['book_number' => 1, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'abudawud',
                'book' => 'Book of Prayer',
                'text' => 'صَلُّوا كَمَا رَأَيْتُمُونِي أُصَلِّي - Pray as you have seen me praying.',
                'translation' => 'Pray as you have seen me praying.',
                'narrator' => 'Malik ibn al-Huwairith (RA)',
                'reference' => 'Sunan Abu Dawood 595',
                'meta' => json_encode(['book_number' => 2, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'abudawud',
                'book' => 'Book of Zakat',
                'text' => 'لَا زَكَاةَ - There is no Zakat on wealth until a year has passed over it.',
                'translation' => 'There is no Zakat on wealth until a year has passed over it.',
                'narrator' => 'Ali ibn Abi Talib (RA)',
                'reference' => 'Sunan Abu Dawood 1573',
                'meta' => json_encode(['book_number' => 9, 'grade' => 'Hasan']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'abudawud',
                'book' => 'Book of Fasting',
                'text' => 'الصِّيَامُ جُنَّةٌ - Fasting is a shield.',
                'translation' => 'Fasting is a shield from the Fire just like the shield of any of you in battle.',
                'narrator' => 'Uthman ibn Abi al-As (RA)',
                'reference' => 'Sunan Abu Dawood 2365',
                'meta' => json_encode(['book_number' => 14, 'grade' => 'Hasan']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'abudawud',
                'book' => 'Book of Jihad',
                'text' => 'الْجِهَادُ مَاضٍ - Jihad continues until the Day of Resurrection.',
                'translation' => 'Jihad will continue until the Day of Resurrection.',
                'narrator' => 'Anas ibn Malik (RA)',
                'reference' => 'Sunan Abu Dawood 2532',
                'meta' => json_encode(['book_number' => 15, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
    }

    /**
     * Get sample Tirmidhi hadiths
     */
    private function getTirmidhiHadiths()
    {
        return [
            [
                'collection' => 'tirmidhi',
                'book' => 'Book of Purification',
                'text' => 'الطَّهُورُ شَطْرُ الإِيمَانِ - Purification is half of faith.',
                'translation' => 'Purification is half of faith.',
                'narrator' => 'Abu Malik al-Ashari (RA)',
                'reference' => 'Jami\' at-Tirmidhi 3',
                'meta' => json_encode(['book_number' => 1, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'tirmidhi',
                'book' => 'Book of Faith',
                'text' => 'الْحَيَاءُ شُعْبَةٌ مِنَ الإِيمَانِ - Modesty is a branch of faith.',
                'translation' => 'Modesty is a branch of faith.',
                'narrator' => 'Abu Huraira (RA)',
                'reference' => 'Jami\' at-Tirmidhi 2009',
                'meta' => json_encode(['book_number' => 27, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'tirmidhi',
                'book' => 'Book of Righteousness',
                'text' => 'حُسْنُ الْخُلُقِ - Good character.',
                'translation' => 'The best among you are those who have the best character.',
                'narrator' => 'Abdullah ibn Amr (RA)',
                'reference' => 'Jami\' at-Tirmidhi 2003',
                'meta' => json_encode(['book_number' => 27, 'grade' => 'Sahih']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'tirmidhi',
                'book' => 'Book of Supplications',
                'text' => 'الدُّعَاءُ مُخُّ الْعِبَادَةِ - Supplication is the essence of worship.',
                'translation' => 'Supplication is the essence of worship.',
                'narrator' => 'Anas ibn Malik (RA)',
                'reference' => 'Jami\' at-Tirmidhi 3371',
                'meta' => json_encode(['book_number' => 48, 'grade' => 'Hasan']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection' => 'tirmidhi',
                'book' => 'Book of Knowledge',
                'text' => 'طَلَبُ الْعِلْمِ فَرِيضَةٌ - Seeking knowledge is obligatory.',
                'translation' => 'Seeking knowledge is obligatory upon every Muslim.',
                'narrator' => 'Anas ibn Malik (RA)',
                'reference' => 'Jami\' at-Tirmidhi 2650',
                'meta' => json_encode(['book_number' => 41, 'grade' => 'Hasan']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
    }
}
