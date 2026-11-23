<?php

namespace Database\Seeders;

use App\Models\God;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->firstOrNew(['username' => 'admin']);

        if (! $admin->exists) {
            $admin->fill([
                'name' => 'Администратор Olympus',
                'email' => 'admin@olympus.test',
                'password' => 'password',
            ]);
            $admin->forceFill([
                'email_verified_at' => now(),
                'is_admin' => true,
            ]);
            $admin->save();
        }

        $demoUsers = [
            [
                'name' => 'Афина',
                'username' => 'athena',
                'email' => 'athena@olympus.test',
            ],
            [
                'name' => 'Посейдон',
                'username' => 'poseidon',
                'email' => 'poseidon@olympus.test',
            ],
        ];

        foreach ($demoUsers as $attributes) {
            $user = User::query()->firstOrNew(['username' => $attributes['username']]);

            if ($user->exists) {
                continue;
            }

            $user->fill($attributes + ['password' => 'password']);
            $user->forceFill(['email_verified_at' => now()]);
            $user->save();
        }

        $gods = [
            [
                'name' => 'Зевс',
                'badge' => 'Бог неба',
                'sphere' => 'Небо, гром и молнии',
                'description' => 'Верховный бог древнегреческого пантеона, повелитель неба, грома и молний.',
                'symbols' => 'молния, орёл, дуб',
                'parents' => 'Кронос и Рея',
                'siblings' => 'Посейдон, Аид, Гера, Деметра, Гестия',
                'spouse' => 'Гера',
                'children' => 'Афина, Аполлон, Артемида, Арес, Гермес, Дионис и другие',
                'temple' => 'храм Зевса в Олимпии',
                'myth' => 'борьба олимпийских богов с титанами',
                'image_path' => 'gods/zeus.jpg',
            ],
            [
                'name' => 'Посейдон',
                'badge' => 'Бог моря',
                'sphere' => 'Моря и землетрясения',
                'description' => 'Бог морей и океанов, способный вызывать бури и землетрясения.',
                'symbols' => 'трезубец, конь, дельфин',
                'parents' => 'Кронос и Рея',
                'siblings' => 'Зевс, Аид, Гера, Деметра, Гестия',
                'spouse' => 'Амфитрита',
                'children' => 'Тритон, Полифем, Пегас и другие',
                'temple' => 'храм Посейдона на мысе Сунион',
                'myth' => 'спор с Афиной за покровительство Аттике',
                'image_path' => 'gods/poseidon.jpg',
            ],
            [
                'name' => 'Аид',
                'badge' => 'Бог подземного мира',
                'sphere' => 'Подземный мир и царство мёртвых',
                'description' => 'Владыка подземного мира и царства мёртвых, один из братьев Зевса.',
                'symbols' => 'Цербер, шлем невидимости, двузубец',
                'parents' => 'Кронос и Рея',
                'siblings' => 'Зевс, Посейдон, Гера, Деметра, Гестия',
                'spouse' => 'Персефона',
                'children' => '—',
                'temple' => 'храм Аида в Элиде',
                'myth' => 'похищение Персефоны',
                'image_path' => 'gods/aid.jpg',
            ],
            [
                'name' => 'Гера',
                'badge' => 'Богиня брака',
                'sphere' => 'Брак и семья',
                'description' => 'Богиня брака и семьи, супруга Зевса и царица олимпийских богов.',
                'symbols' => 'павлин, корона, гранат',
                'parents' => 'Кронос и Рея',
                'siblings' => 'Зевс, Посейдон, Аид, Деметра, Гестия',
                'spouse' => 'Зевс',
                'children' => 'Арес, Геба, Илифия, Гефест',
                'temple' => 'храм Геры в Олимпии',
                'myth' => 'преследование Геракла',
                'image_path' => 'gods/hera.jpg',
            ],
            [
                'name' => 'Афина',
                'badge' => 'Богиня мудрости',
                'sphere' => 'Мудрость и военная стратегия',
                'description' => 'Богиня мудрости, военной стратегии, ремёсел и покровительница города Афины.',
                'symbols' => 'сова, копьё, щит, оливковое дерево',
                'parents' => 'Зевс и Метида',
                'siblings' => 'Аполлон, Артемида, Арес, Гермес, Дионис и другие дети Зевса',
                'spouse' => 'нет',
                'children' => '—',
                'temple' => 'Парфенон в Афинах',
                'myth' => 'спор с Посейдоном за покровительство Афинам',
                'image_path' => 'gods/athena.jpg',
            ],
        ];

        foreach ($gods as $attributes) {
            if (God::withTrashed()->where('name', $attributes['name'])->exists()) {
                continue;
            }

            $admin->gods()->create($attributes + [
                'recorded_at' => today()->toDateString(),
            ]);
        }
    }
}
