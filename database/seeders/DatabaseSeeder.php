<?php

namespace Database\Seeders;

use App\Models\SupportGroup;
use App\Models\Therapist;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedTherapists();
        $this->seedSupportGroups();
    }

    private function seedTherapists(): void
    {
        $therapists = [
            [
                'name' => 'Dr. Aisha Khan',
                'specialization' => 'Clinical Psychologist',
                'years_experience' => 8,
                'rating' => 4.9,
                'availability' => 'Today Available',
                'tags' => ['Anxiety', 'Depression', 'CBT'],
                'icon' => '👩‍⚕️',
                'bg_color' => 'green',
            ],
            [
                'name' => 'Dr. Bilal Raza',
                'specialization' => 'Psychiatrist',
                'years_experience' => 12,
                'rating' => 4.8,
                'availability' => 'Tomorrow Available',
                'tags' => ['Trauma', 'PTSD', 'Online'],
                'icon' => '🧑‍⚕️',
                'bg_color' => 'purple',
            ],
            [
                'name' => 'Sara Malik LMFT',
                'specialization' => 'Family Therapist',
                'years_experience' => 6,
                'rating' => 4.7,
                'availability' => 'Today Available',
                'tags' => ['Relationships', 'Grief', 'Online'],
                'icon' => '👩',
                'bg_color' => 'orange',
            ],
            [
                'name' => 'Dr. Hassan Mirza',
                'specialization' => 'Neuropsychologist',
                'years_experience' => 15,
                'rating' => 5.0,
                'availability' => 'Apr 15',
                'tags' => ['ADHD', 'Stress', 'Sleep'],
                'icon' => '👨‍⚕️',
                'bg_color' => 'blue',
            ],
        ];

        foreach ($therapists as $therapist) {
            Therapist::updateOrCreate(['name' => $therapist['name']], $therapist);
        }
    }

    private function seedSupportGroups(): void
    {
        $groups = [
            [
                'slug' => 'anxiety-warriors',
                'name_en' => 'Anxiety Warriors',
                'name_ur' => 'Anxiety Warriors',
                'description_en' => 'For those managing anxiety. Share experiences in a safe space.',
                'description_ur' => 'Anxiety manage karne walon ke liye safe space. Apne tajrubaat share karein.',
                'member_count' => 243,
                'icon' => '🌿',
                'bg_color' => 'green',
            ],
            [
                'slug' => 'depression-support',
                'name_en' => 'Depression Support',
                'name_ur' => 'Depression Support',
                'description_en' => 'A circle for those navigating depression. Weekly check-ins and peer support.',
                'description_ur' => 'Depression se guzarne walon ka circle. Weekly check-in aur peer support.',
                'member_count' => 189,
                'icon' => '💜',
                'bg_color' => 'purple',
            ],
            [
                'slug' => 'grief-loss',
                'name_en' => 'Grief & Loss',
                'name_ur' => 'Grief & Loss',
                'description_en' => 'Compassion for those grieving. Share your heart without judgment.',
                'description_ur' => 'Nuqsan aur gham mein hamdardi. Bina kisi judgment ke dil ki baat karein.',
                'member_count' => 97,
                'icon' => '🧡',
                'bg_color' => 'orange',
            ],
            [
                'slug' => 'young-adults',
                'name_en' => 'Young Adults (18–25)',
                'name_ur' => 'Young Adults (18–25)',
                'description_en' => 'University stress, identity, career and relationships. You\'re not alone.',
                'description_ur' => 'University stress, identity, career aur relationships. Tum akele nahi ho.',
                'member_count' => 312,
                'icon' => '💙',
                'bg_color' => 'blue',
            ],
        ];

        foreach ($groups as $group) {
            SupportGroup::updateOrCreate(['slug' => $group['slug']], $group);
        }
    }
}
