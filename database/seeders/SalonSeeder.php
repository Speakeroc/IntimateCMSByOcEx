<?php

namespace Database\Seeders;

use App\Models\location\City;
use App\Models\location\Metro;
use App\Models\location\Zone;
use App\Models\posts\Salon;
use App\Models\posts\SalonContent;
use App\Models\system\Getters;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SalonSeeder extends Seeder
{
    private Getters $getters;

    public function __construct() {
        $this->getters = new Getters;
    }

    public function run() {
        $this->getters->clearFolder(public_path('images/salon'));

        //Names
        $names = ['Лагуна', 'Лисья Нора', 'Dream Girls', 'ADAMANT', 'Порнозвездочки *', 'Sexy Baby', 'Салон KAIF', 'LUX GIRLS', 'Мулен Руж на Киевской', '🌟Элитные девушки', 'Империя Любви', 'Bounty Райское Наслаждение', '❤️Страсть❤️', 'ESWE'];

        //Description
        $descriptions = [
            'Скучать не дам. Приезжай и все увидишь сам :-) :-) :-) Я яркая, запоминающаяся, симпатичная, отвязная девушка. Со мной не бывает скучно.Допы обсуждаю',
            'Тебя поразит роскошная упругая попка, точеная фигурка, прекрасные стройные ножки и нежная бархатистая кожа. Ты испытаешь невероятное наслаждение, проникая в мои узенькие и чувственные дырочки. Допы обсуждаю',
            'Очаровательная черное дерево осветлить темный каждый день. Погружение в мой нежный рай....Очарова...',
            'Страстная, ухоженная, загорелая, общительная пригласит в гости мужчину для стастного и волнительного отдыха. Охрана есть, дополнительные услуги оплачиваются отдельно! Тебе понравится! Есть подружка! Жду Вашего звонка!!!',
            'Эстетика и разврат, приправленные игрушками (для меня и тебя), фильмами для взрослых и поперсами, а также потрясающее послевкусие гарантированы! Как в самом горячем порно!',
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

        $addresses = ['ул.Семашко 48', 'ул.Пушкина 24', 'ул.Пушкина 9', 'ул.Пушкина 89', 'ул.Луначарского 54', 'ул.Московская 44', 'ул.Гагарина 66', 'ул.Пушкина 82', 'ул.Куликова 1', 'ул.Кирова 88', 'ул.Ленина 92', 'ул.Куликова 74', 'ул.Куликова 73', 'ул.Энергетиков 64', 'ул.Чапаева 21', 'ул.Пушкина 88', 'ул.Гагарина 6', 'ул.Красная 28', 'ул.Береговая 94', 'ул.Луначарского 10', 'ул.Луначарского 98', 'ул.Кузнецова 80', 'ул.Кузнецова 55', 'ул.Большая 91', 'ул.Крылова 36', 'ул.Чапаева 31', 'ул.Кузнецова 20', 'ул.Зеленая 77', 'ул.Луначарского 65', 'ул.Карла Маркса 15', 'ул.Войкова 85', 'ул.Стадионная 81', 'ул.Зеленая 91', 'ул.Новая 10', 'ул.Гагарина 80', 'ул.Калинина 99', 'ул.Садовая 37', 'ул.Стадионная 72', 'ул.Красная 73', 'ул.Советская 5', 'ул.Московская 80', 'ул.Московская 24', 'ул.Энергетиков 12', 'ул.Гагарина 81', 'ул.Пушкина 46', 'ул.Заводская 74', 'ул.Стадионная 67', 'ул.Калинина 71'];


        for ($i = 1; $i <= 10; $i++) {

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

            //Call type and time
            $work_time_type = rand(0, 1);
            if ($work_time_type) {
                $work_time = ["time_to" => rand(0, 12), "time_from" => rand(13, 24)];
            } else {
                $work_time = [];
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

            $lat1 = $cityId->latitude;
            $lon1 = $cityId->longitude;
            $lat2 = ($lat1 + 0.26);
            $lon2 = ($lon1 - 0.26);
            $lat3 = ($lat2 - 0.26);
            $lon3 = ($lon2 + 0.26);
            $lat4 = ($lat2 - 0.26);
            $lon4 = ($lon2 + 0.26);
            $minLat = min($lat1, $lat2, $lat3, $lat4);
            $maxLat = max($lat1, $lat2, $lat3, $lat4);
            $minLon = min($lon1, $lon2, $lon3, $lon4);
            $maxLon = max($lon1, $lon2, $lon3, $lon4);
            $latitude = mt_rand($minLat * 1000000, $maxLat * 1000000) / 1000000;
            $longitude = mt_rand($minLon * 1000000, $maxLon * 1000000) / 1000000;

            $phone = '+7 ('.rand(100, 999).') '.rand(100, 999).'-'.rand(10, 99).'-'.rand(10, 99);
            $phone_one = '+7 ('.rand(100, 999).') '.rand(100, 999).'-'.rand(10, 99).'-'.rand(10, 99);
            $phone_two = '+7 ('.rand(100, 999).') '.rand(100, 999).'-'.rand(10, 99).'-'.rand(10, 99);

            $salon = Salon::create([
                'user_id' => $user_id,
                'uniq_uid' => $uniq_uid,
                'title' => $names[rand(0, (count($names) - 1))],
                'city_id' => $cityId->id,
                'phone' => $phone,
                'phone_one' => $phone_one,
                'phone_two' => $phone_two,
                'zone_id' => $zoneId,
                'metro_id' => $metroId,
                'messengers' => json_encode($messengers),
                'work_time_type' => $work_time_type,
                'work_time' => json_encode($work_time),
                'delete_code' => '252325',
                'latitude' => $latitude,
                'longitude' => $longitude,
                'price_day_in_one' => rand(1000, 2000),
                'price_day_in_two' => rand(2000, 3000),
                'price_day_out_one' => rand(3000, 4000),
                'price_day_out_two' => rand(4000, 5000),
                'price_night_in_one' => rand(7000, 8000),
                'price_night_in_night' => rand(8000, 9000),
                'price_night_out_one' => rand(9000, 10000),
                'price_night_out_night' => rand(10000, 13000),
                'address' => $addresses[rand(0, (count($addresses) - 1))],
                'desc' => $descriptions[rand(0, (count($descriptions) - 1))],
                'up_date' => Carbon::now()->subDays(rand(0, 30)),
                'moderation_id' => 1,
                'moderator_id' => 1,
                'publish' => 1,
                'publish_date' => Carbon::now()->addDays(rand(0, 30))->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                'created_at' => Carbon::now()->subDays(rand(0, 30))->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                'updated_at' => Carbon::now()->subDays(rand(0, 30))->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
            ]);

            $salon_id = $salon->id;

            $content_main = 'images/salon/'.$uniq_uid.'/main';

            //Main Photo
            $image_main = $this->moveTempToFolder('/images/demo/post/'.rand(1, 47).'.png', $content_main, rand(199, 9999).'_'.$user_id.'_'.$uniq_uid);
            if ($image_main) {
                SalonContent::create(['salon_id' => $salon_id, 'user_id' => $user_id, 'file' => $image_main, 'type' => 'main']);
            }
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
}
