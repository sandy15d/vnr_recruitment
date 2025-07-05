<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\CaseStudyAnswer;
use App\Models\CaseStudyQuestion;

class CaseStudyAnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CaseStudyAnswer::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'answer' => $this->faker->text(),
            'case_study_question_id' => CaseStudyQuestion::factory(),
        ];
    }
}
