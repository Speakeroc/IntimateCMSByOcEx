<?php

namespace Database\Seeders;

use App\Models\location\City;
use App\Models\location\Metro;
use App\Models\location\Zone;
use App\Models\posts\Post;
use App\Models\posts\PostContent;
use App\Models\posts\Tags;
use App\Models\system\Getters;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PostSeeder extends Seeder
{
    private Getters $getters;

    public function __construct() {
        $this->getters = new Getters;
    }

    public function run() {
        $this->clearPostsDirectory();
        $this->getters->clearFolder(public_path('images/cache'));
        $this->getters->clearFolder(public_path('images/posts'));
        $this->getters->clearFolder(public_path('images/temp'));
        $this->getters->clearFolder(storage_path('logs'));

        //Names
        $names = ['Анна', 'Екатерина', 'Мария', 'Ольга', 'Анастасия', 'Дарья', 'София', 'Елена', 'Татьяна', 'Ирина', 'Виктория', 'Ксения', 'Полина', 'Наталья', 'Евгения', 'Алиса', 'Светлана', 'Людмила', 'Маргарита', 'Кристина',];

        //Description
        $descriptions = [
            'Постараюсь выполнить все твои желания, чтобы наша встреча запомнилась тебе только хорошим... Сексуальна, всегда в хорошем настроении! Постараюсь выполнить все твои желания, чтобы наша встреча запомнилась тебе только хорошим. Моя ласковая киска жаждет посетителя, а ротик уже совсем остался без работы. Только со мной ты узнаешь, какое наслаждение можно получить от оральных ласк. Наше свидание не забудется, поверь!',
            '✨ Привет! Меня зовут Мэгги, и я – веселая и обаятельная спутница, готовая сделать ваш вечер незабываемым! 💖 ✨ Привет! Меня зовут Мэгги, и я – веселая и обаятельная спутница, готовая сделать ваш вечер незабываемым! 💖 🌹 Я обожаю новые знакомства и увлекательные беседы, а также умею создавать уютную атмосферу, где вам будет приятно и комфортно. 🤗 💋 Мой шарм и нежность подарят вам истинное наслаждение, а мои яркие глаза и улыбка заставят ваше сердце биться чаще! 💕 🎉 Будьте уверены, что наш вечер пройдет в атмосферу веселья и романтики. Я люблю уютные вечера при свечах и интересные разговоры на любые темы! 🌙 💃 Давайте сделаем этот момент особенным и запоминающимся! Жду вашего сообщения! ✨💌',
            'Приглашу на горловой минет и чувственный секс 💖☺ Принимаю в своей уютной квартире, без посторонних)) ❤ Я высокая 176 ростом, стройная с шикарной фигурой и очень упругой попой 🍑 Веселая, не глупая, начитанная❣️ Со мной всегда есть о чем поговорить и о чем потрахаться ❤ ❤❤Активная, инициативная, я сама тебя Разболтаю и сама к тебе пристану 😉🥰 Так что ни о каком смущении не может быть и речи 🥰 буду рада, если ты остановишь выбор на мне 🍌 🍓 Бурно кончаю от куни, сосу со слюнками и окрестностями, как в порно 🍑🍌💦💦💦💦',
            'Пожалуйста, прочти это, ведь я так старалась 🥹 (Котики, пожалуйста, пишите в Телеграм, на звонок могу не ответить ☺️) Привет! У меня есть свой канал в Телеграме :) Можешь написать мне личное сообщение по этому адресу: @polinka_moscow24. Имеется сопровождение:) В моем Телеграм канале (https://t.me/polinka_moscow) я публикую информацию о своем графике, условиях встречи, событиях из жизни, своих предпочтениях в сексе, а также новости с фотографиями и стриптизом. Я очень открытый человек. Мы можем весело провести время, расслабиться, пообщаться или провести яркий отдых. Я подберу программу встречи индивидуально под твои потребности в рамках моей компетенции. В наше время включено много разнообразных услуг, таких как классический секс, минет без резинки, кунилингус, поцелуи, массаж и многое другое. Мы также можем сходить куда-нибудь:) У меня есть анализы, которые я сдаю каждые 2 недели, и есть справка. Встречи могут быть как у меня, так и на выезде. Я гарантирую, что на всех фотографиях это именно я. Я не менеджер, а индивидуальный исполнитель, и могу это доказать. Стоимость за час: 20.000р Стоимость за 2 часа: 30.000р Стоимость за 3 часа: 40.000₽ Стоимость за 4 часа: 50.000₽ Стоимость за 5 часов: 60.000₽ Стоимость за ночь: 80.000₽ Если тебе интересно, напиши мне в Телеграм: @polinka_moscow24',
            'В некотором смысле я отличаюсь от многих девушек на сайте… Я сексуальна и энергична , но во мне нет вульгарности , и я никогда не скажу тебе , что ты можешь делать со мной все . Мы будем делать друг с другом только то , что будет приятно нам обоим…) Во время нашей встречи я хочу оставаться собой. Перешагнув порог моих апартаментов, ты увидишь милую нежную игривую и юную девушку. Особое внимание я уделяю уходу за собой. Тебя не оставит равнодушным моя подтянутая спортивная фигура, бархатная кожа, большие выразительные глаза … Я образована , интеллектуально развита , а также эмпатична и тонко чувствую людей. И если мужчина импонирует мне, то я буду милая и естественная. Никакой наигранности. А это залог хорошего и приятного провождения времени. Буду рада видеть тебя у себя в своих дизайнерских апартаментах, также готова приехать к тебе в гости или в отель.) Чтобы договориться о встрече, пиши мне в Telegram или WhatsApp 🖤💋 И да, говорят, в жизни я еще лучше чем на фото…)💎',

            'Яркая, просто безумно сексуальная девушка приглашает Вас в гости для страстного отдыха! В обществе симпатичной, страстной и умелой любовницы Вы отдохнете и душой и телом, Допы обсуждаю',

            'Тебя поразит роскошная упругая попка, точеная фигурка, прекрасные стройные ножки и нежная бархатистая кожа. Ты испытаешь невероятное наслаждение, проникая в мои узенькие и чувственные дырочки. Допы обсуждаю',

            'Готова раздвинуть ножки перед любителем поласкать язычком... От этого завожусь и подарю океан изысканной сексуальной ласки и наслаждения!!!!Допы обсуждаю',

            'Молодая, гибкая, красивая)Приглашаю доброго и порядочного мужчину. Встречу наедине гарантирую!Все фото мои и полностью соответствуют!!! Приходи ко мне и я окуну тебя в мир соблазна',

            'Эстетика и разврат, приправленные игрушками (для меня и тебя), фильмами для взрослых и поперсами, а также потрясающее послевкусие гарантированы! Как в самом горячем порно!',

            'Сексуальная куколка!!! Обожаю секс!!! Звони в любое время. На телефон отвечаю лично Я!!! Элитные апартаменты.Все допы обсуждаю',

            'Яркая, живая, настоящая кокетка... Я люблю дразнить и вводить мужчин в искушение!!! Про таких как я говорят , что хочет и готова всегда! Все допы обсуждаю только при встрече',

            'Очень милая и женственная, ухоженная! Встречаюсь в люкс апартаментах! Парковка у дома, чистый подъезд. Великолепно выгляжу и невероятно приятно ласкаю, дарю незабываемые удовольствия и осуществляю любые желания мужчин! Допы обсуждаю',

            'Твоя королева. Идеальный сервис, все обустроила для Вашего отдыха! Во дворе бесплатная парковка в любое время суток.Фото мои 100%Приезжай)Буду ждать только тебя!)Также выезжаю на выезд.Все допы обсуждаю при встрече',

            'Я гарантирую легкое комфортное общение, непритворное внимание к любым капризам и всю свою плотскую нежность. Обладаю всеми качествами идеальной любовницы - сексапильным телом, страстным темпераментом, ненасытным секс-аппетитом и буйной фантазией.',
        ];

        for ($i = 1; $i <= 100; $i++) {
            $photo_int = rand(0, 3);
            $selfie_int = rand(0, 3);
            $video_int = rand(0, 3);
            //User Id
            $user_id = Users::inRandomOrder()->value('id');

            //Uniq Uid
            $uniq_uid = $this->generateUniqueId($user_id.'_%s_%s');

            //City
            $cityId = City::inRandomOrder()->first();

            //Zone
            $rand_zone = rand(1, 20);
            if ($rand_zone > 5 && $rand_zone < 10) {
                $zoneId = Zone::where('city_id', $cityId->id)->inRandomOrder()->first()->id ?? null;
            } else {
                $zoneId = null;
            }

            //Metro
            $rand_metro = rand(1, 20);
            if ($rand_metro > 5 && $rand_metro < 10) {
                $metroId = Metro::where('city_id', $cityId->id)->inRandomOrder()->first()->id ?? null;
            } else {
                $metroId = null;
            }

            //Express
            $express = rand(0, 1);

            $moderation_status = 1;

            //Diamond
            $diamond = rand(0, 1);
            $diamond_date = null;
            if ($diamond && $moderation_status == 1) {
                $diamond_date = date('Y-m-d H:i:s', strtotime("+".rand(1, 26)." days ".rand(0, 23)." hours ".rand(0, 59)." minutes ".rand(0, 59)." seconds"));
            }

            //VIP
            $vip = rand(0, 1);
            $vip_date = null;
            if ($vip && $moderation_status == 1) {
                $vip_date = date('Y-m-d H:i:s', strtotime("+".rand(1, 26)." days ".rand(0, 23)." hours ".rand(0, 59)." minutes ".rand(0, 59)." seconds"));
            }

            //Color
            $color = rand(0, 1);
            $color_two = rand(0, 1);
            $color_date = null;
            if ($color && $moderation_status == 1 && $color_two) {
                $color_date = date('Y-m-d H:i:s', strtotime("+".rand(1, 26)." days ".rand(0, 23)." hours ".rand(0, 59)." minutes ".rand(0, 59)." seconds"));
            }

            //Verify
            $verify = rand(0, 1);

            //Client age
            $client_age = ["min" => rand(18, 30), "max" => rand(31, 65)];

            //Call type and time
            $call_time_type = rand(0, 1);
            if ($call_time_type) {
                $call_time = [];
            } else {
                $call_time = ["time_to" => rand(0, 12), "time_from" => rand(13, 24)];
            }

            //Call time answering to
            $answering_tos = [['id' => 1], ['id' => 2], ['id' => 3]];
            $count = rand(1, count($answering_tos));
            if ($count > 0) {
                $randomKeys = array_rand($answering_tos, $count);
                $randomKeys = (array) $randomKeys;
                foreach ($randomKeys as $key) {
                    $call_time['answering_to'][] = $answering_tos[$key]['id'];
                }
            } else {
                $call_time['answering_to'] = [];
            }

            //Language Skills
            $language_skills = app('post_language_skills');
            $language_skill = [];
            $maxCount = ceil(count($language_skills) * 0.6);
            $count = rand(1, $maxCount);
            $randomKeys = array_rand($language_skills, $count);
            $randomKeys = (array) $randomKeys;
            foreach ($randomKeys as $key) {
                $language_skill[] = (string)$language_skills[$key]['id'];
            }
            $language_skill = array_unique($language_skill);

            //Body Art
            $body_arts = app('post_body_art');
            $body_art = [];
            $maxCount = ceil(count($body_arts) * 0.2);
            $count = rand(1, $maxCount);
            $randomKeys = array_rand($body_arts, $count);
            $randomKeys = (array) $randomKeys;
            foreach ($randomKeys as $key) {
                $body_art[] = (string)$body_arts[$key]['id'];
            }
            $body_art = array_unique($body_art);

            //Services for
            $services_fours = app('post_services_for');
            $services_for = [];
            $maxCount = ceil(count($services_fours) * 0.5);
            $count = rand(1, $maxCount);
            $randomKeys = array_rand($services_fours, $count);
            $randomKeys = (array) $randomKeys;
            foreach ($randomKeys as $key) {
                $services_for[] = (string)$services_fours[$key]['id'];
            }
            $services_for = array_unique($services_for);

            //Visit Place
            $visit_places = app('post_visit_places');
            $visit_place = [];
            if (rand(0, 1)) {
                $count = rand(1, count($visit_places));
                $randomKeys = array_rand($visit_places, $count);
                foreach ((array)$randomKeys as $key) {
                    $visit_place[] = (string)$visit_places[$key]['id'];
                }
            }
            $visit_place = array_unique($visit_place);

            //Tags
            $tags = Tags::get();
            $all_tags = [];
            foreach ($tags as $tag_item) {
                $all_tags[] = ['id' => $tag_item['id']];
            }
            $tags = [];
            if (!empty($all_tags)) {
                $count = rand(1, (count($all_tags) - 20));
                $randomKeys = (array) array_rand($all_tags, $count);
                foreach ($randomKeys as $key) {
                    $tags[] = (string)$all_tags[$key]['id'];
                }
                $tags = array_unique($tags);
            }

            //Messengers
            $messengers = [];
            $telegram = rand(0,1);
            if ($telegram) {
                $messengers['telegram'] = ['status' => 1, 'type' => (rand(0,1)) ? 'link' : 'login', 'content' => 'intimatecms'];
            } else {
                $messengers['telegram'] = ['status' => 0, 'type' => 'link', 'content' => null];
            }
            $whatsapp = rand(0,1);
            if ($whatsapp) {
                $messengers['whatsapp'] = ['status' => 1, 'content' => '+7956'.rand(100,999).rand(10,99).rand(10,99)];
            } else {
                $messengers['whatsapp'] = ['status' => 0, 'content' => null];
            }
            $instagram = rand(0,1);
            if ($instagram) {
                $messengers['instagram'] = ['status' => 1, 'content' => 'https://www.instagram.com/intimate_cms/'];
            } else {
                $messengers['instagram'] = ['status' => 0, 'content' => null];
            }
            $polee = rand(0,1);
            if ($polee) {
                $messengers['polee'] = ['status' => 1, 'content' => 'https://polee.me/intimate_cms'];
            } else {
                $messengers['polee'] = ['status' => 0, 'content' => null];
            }

            $lat1 = $cityId->latitude;
            $lon1 = $cityId->longitude;
            $lat2 = ($lat1 + 0.1);
            $lon2 = ($lon1 - 0.1);
            $lat3 = ($lat2 - 0.1);
            $lon3 = ($lon2 + 0.1);
            $lat4 = ($lat2 - 0.1);
            $lon4 = ($lon2 + 0.1);
            $minLat = min($lat1, $lat2, $lat3, $lat4);
            $maxLat = max($lat1, $lat2, $lat3, $lat4);
            $minLon = min($lon1, $lon2, $lon3, $lon4);
            $maxLon = max($lon1, $lon2, $lon3, $lon4);
            $latitude = mt_rand($minLat * 1000000, $maxLat * 1000000) / 1000000;
            $longitude = mt_rand($minLon * 1000000, $maxLon * 1000000) / 1000000;

            $post = Post::create([
                'user_id' => rand(1, 4),
                'uniq_uid' => $uniq_uid,
                'name' => $names[rand(0, (count($names) - 1))],
                'city_id' => $cityId->id,
                's_individuals' => rand(0, 1),
                's_premium' => rand(0, 1),
                's_health' => rand(0, 1),
                's_elite' => rand(0, 1),
                's_bdsm' => rand(0, 1),
                's_masseuse' => rand(0, 1),
                'phone' => '+7 ('.rand(100, 999).') '.rand(100, 999).'-'.rand(10, 99).'-'.rand(10, 99),
                'zone_id' => $zoneId,
                'metro_id' => $metroId,
                'messengers' => json_encode($messengers),
                'tags' => json_encode($tags),
                'call_time_type' => $call_time_type,
                'call_time' => json_encode($call_time),
                'client_age' => json_encode($client_age),
                'delete_code' => '252325',
                'age' => rand(18, 46),
                'weight' => rand(40, 200),
                'cloth' => rand(36, 55),
                'height' => rand(150, 250),
                'breast' => rand(1, 12),
                'shoes' => rand(30, 46),
                'nationality' => rand(1, 20),
                'body_type' => rand(1, 5),
                'hair_color' => rand(1, 6),
                'hairy' => rand(1, 3),
                'body_art' => json_encode($body_art),
                'services_for' => json_encode($services_for),
                'language_skills' => json_encode($language_skill),
                'visit_places' => json_encode($visit_place),
                'services' => $this->generateServices(),
                'latitude' => $latitude,
                'longitude' => $longitude,
                'price_day_in_one' => round(rand(3000, 100000), -3),
                'price_day_in_two' => round(rand(3000, 100000), -3),
                'price_day_out_one' => round(rand(5000, 200000), -3),
                'price_day_out_two' => round(rand(5000, 200000), -3),
                'price_night_in_one' => round(rand(5000, 500000), -3),
                'price_night_in_night' => round(rand(5000, 500000), -3),
                'price_night_out_one' => round(rand(5000, 900000), -3),
                'price_night_out_night' => round(rand(5000, 900000), -3),
                'express' => $express,
                'express_price' => ($express) ? round(rand(1000, 10000), -3) : null,
                'description' => $descriptions[rand(0, (count($descriptions) - 1))],
                'diamond' => $diamond,
                'diamond_date' => $diamond_date,
                'vip' => $vip,
                'vip_date' => $vip_date,
                'color' => $color,
                'color_date' => $color_date,
                'verify' => rand(0, 1),
                'up_date' => Carbon::now()->subDays(rand(0, 30)),
                'moderation_id' => $moderation_status,
                'moderator_id' => 1,
                'publish' => 1,
                'publish_date' => Carbon::now()->addDays(rand(0, 30))->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                'created_at' => Carbon::now()->subDays(rand(0, 30))->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                'updated_at' => Carbon::now()->subDays(rand(0, 30))->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
            ]);

            $post_id = $post->id;

            $content_main = 'images/posts/'.$uniq_uid.'/main';
            $content_verify = 'images/posts/'.$uniq_uid.'/verify';
            $content_photo = 'images/posts/'.$uniq_uid.'/photo';
            $content_selfie = 'images/posts/'.$uniq_uid.'/selfie';
            $content_video = 'images/posts/'.$uniq_uid.'/video';

            //Main Photo
            $image_main = $this->moveTempToFolder('/images/demo/post/'.rand(1, 10).'.png', $content_main, rand(199, 9999).'_'.$user_id.'_'.$uniq_uid);
            if ($image_main) {
                PostContent::create(['post_id' => $post_id, 'user_id' => $user_id, 'file' => $image_main, 'type' => 'main']);
            }

            //Verify Photo
            if ($verify) {
                $image_verify = $this->moveTempToFolder('/images/demo/post/'.rand(1, 10).'.png', $content_verify, rand(199, 9999).'_'.$user_id.'_'.$uniq_uid);
                if ($image_verify) {
                    PostContent::create(['post_id' => $post_id, 'user_id' => $user_id, 'file' => $image_verify, 'type' => 'verify']);
                }
            }

            //All Photo
            if ($photo_int) {
                for ($photo_i = 1;$photo_i <= $photo_int;$photo_i++) {
                    $image_photo = $this->moveTempToFolder('/images/demo/post/'.rand(1, 10).'.png', $content_photo, rand(199, 9999).'_'.$user_id.'_'.$uniq_uid);
                    if ($image_photo) {
                        PostContent::create(['post_id' => $post_id, 'user_id' => $user_id, 'file' => $image_photo, 'type' => 'photo']);
                    }
                }
            }

            //All Selfie
            if ($selfie_int) {
                for ($selfie_i = 1;$selfie_i <= $selfie_int;$selfie_i++) {
                    $image_selfie = $this->moveTempToFolder('/images/demo/post/'.rand(1, 10).'.png', $content_selfie, rand(199, 9999).'_'.$user_id.'_'.$uniq_uid);
                    if ($image_selfie) {
                        PostContent::create(['post_id' => $post_id, 'user_id' => $user_id, 'file' => $image_selfie, 'type' => 'selfie']);
                    }
                }
            }

            //All Video
            //if ($video_int) {
            //    $video_int = rand(1, 5);
            //    for ($video_i = 1;$video_i <= $video_int;$video_i++) {
            //        $image_video = $this->moveTempToFolder('/images/demo/video/'.rand(1, 11).'.mp4', $content_video, rand(199, 9999).'_'.$user_id.'_'.$uniq_uid);
            //        if ($image_video) {
            //            PostContent::create(['post_id' => $post_id, 'user_id' => $user_id, 'file' => $image_video, 'type' => 'video']);
            //        }
            //    }
            //}
        }
    }

    public function clearPostsDirectory()
    {
        $directory = public_path('/images/posts');
        if (File::exists($directory) && File::isDirectory($directory)) {
            File::cleanDirectory($directory);
        }
    }

    public function moveTempToFolder($image, $new_path, $target): ?string
    {
        $image_path = public_path($image);
        $directory = public_path($new_path);

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $files = File::files($directory);
        foreach ($files as $file) {
            if (preg_match('/\b' . preg_quote(mb_strtolower($target), '/') . '\b/', mb_strtolower($file->getFilename()))) {
                File::delete($file->getRealPath());
            }
        }

        $new_image_name = mb_strtolower($target) . '.' . File::extension($image_path);
        $new_image_path = $directory . '/' . $new_image_name;

        if (File::exists($image_path)) {
            File::copy($image_path, $new_image_path);
            $normalizedPath = str_replace('\\', '/', $new_image_path);
            $relativePath = strstr($normalizedPath, 'images');
            return '/' . $relativePath;
        } else {
            return null;
        }
    }

    public function generateUniqueId($template = '%s_%s'): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $partsCount = substr_count($template, '%s');
        $parts = [];
        for ($i = 0; $i < $partsCount; $i++) {
            $parts[] = substr(str_shuffle($characters), 0, 4);
        }
        return vsprintf($template, $parts);
    }

    public function generateServices(): bool|string
    {
        $gen_services = [];

        foreach (app('post_services') as $services) {
            $texts = ['За доп оплату', 'Доп', 'Послушная девочка! Лайт!', 'Обожаю сосать глубоко!', 'по симпатии', null, null, null, null, null];
            foreach ($services['data'] as $service) {
                if ($service['id'] != 'title') {
                    $condition = rand(1, 4);
                    $description = null;
                    $price = null;
                    if ($condition == 1) {
                        $description = $texts[rand(0, 9)];
                    }
                    if ($condition == 2) {
                        $description = $texts[rand(0, 9)];
                    }
                    if ($condition == 3) {
                        $description = $texts[rand(0, 9)];
                        $result = rand(1000, 10000);
                        $price = (int)round($result / 500) * 500;
                    }
                    $gen_services[$service['id']] = ['condition' => $condition, 'description' => (string)$description, 'price' => $price];
                }
            }
        }
        return json_encode($gen_services);
    }
}
