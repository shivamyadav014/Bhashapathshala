<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Exercise;
use App\Models\ExerciseSubmission;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\User;
use App\Models\UserQuizResult;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /** Stable demo photos (picsum.photos — works without API keys). */
    private string $coursePhotoSpanish = 'https://picsum.photos/id/1018/1200/675';

    private string $coursePhotoFrench = 'https://picsum.photos/id/1067/1200/675';

    private string $coursePhotoGerman = 'https://picsum.photos/id/1031/1200/675';

    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@languageapp.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $instructor1 = User::create([
            'name' => 'Maria Garcia',
            'email' => 'maria@languageapp.com',
            'password' => Hash::make('password123'),
            'role' => 'instructor',
            'bio' => 'Spanish language expert with 10+ years experience',
        ]);

        $instructor2 = User::create([
            'name' => 'Pierre Dupont',
            'email' => 'pierre@languageapp.com',
            'password' => Hash::make('password123'),
            'role' => 'instructor',
            'bio' => 'French language enthusiast and professional translator',
        ]);

        $student1 = User::create([
            'name' => 'John Student',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
        ]);

        $student2 = User::create([
            'name' => 'Emma Wilson',
            'email' => 'emma@example.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
        ]);

        $student3 = User::create([
            'name' => 'Alex Johnson',
            'email' => 'alex@example.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
        ]);

        $spanishCourse = Course::create([
            'title' => 'Spanish for Beginners',
            'description' => "Build a solid foundation in Spanish: everyday greetings, numbers, core verbs, and real-life phrases you can use while traveling or chatting with friends. Each unit blends short explanations, practice exercises, and a progress quiz so you always know how you're doing.",
            'language' => 'Spanish',
            'level' => 'beginner',
            'instructor_id' => $instructor1->id,
            'thumbnail' => $this->coursePhotoSpanish,
            'duration_hours' => 24,
            'total_lessons' => 4,
            'rating' => 4.5,
            'is_published' => true,
        ]);

        $frenchCourse = Course::create([
            'title' => 'French Intermediate Level',
            'description' => "Take your French beyond the basics: richer vocabulary for travel and work, past tenses in conversation, and listening strategies. Includes scenario-based lessons (café, directions, small talk) plus quizzes that mirror real dialogue.",
            'language' => 'French',
            'level' => 'intermediate',
            'instructor_id' => $instructor2->id,
            'thumbnail' => $this->coursePhotoFrench,
            'duration_hours' => 32,
            'total_lessons' => 3,
            'rating' => 4.7,
            'is_published' => true,
        ]);


        $germanCourse = Course::create([
            'title' => 'German Advanced Level',
            'description' => "Polish formal German for study and workplace settings: complex sentences, opinion language, and structured writing. Focus on precision, tone, and listening to native-speed audio descriptions.",
            'language' => 'German',
            'level' => 'advanced',
            'instructor_id' => $instructor1->id,
            'thumbnail' => $this->coursePhotoGerman,
            'duration_hours' => 40,
            'total_lessons' => 2,
            'rating' => 4.3,
            'is_published' => true,
        ]);

        // Additional demo courses
        Course::create([
            'title' => 'Italian for Travelers',
            'description' => 'Essential Italian phrases, travel tips, and cultural etiquette for your next trip to Italy.',
            'language' => 'Italian',
            'level' => 'beginner',
            'instructor_id' => $instructor2->id,
            'thumbnail' => 'https://picsum.photos/id/1043/1200/675',
            'duration_hours' => 18,
            'total_lessons' => 3,
            'rating' => 4.6,
            'is_published' => true,
        ]);
        Course::create([
            'title' => 'Japanese Conversation Basics',
            'description' => 'Master greetings, introductions, and polite expressions for everyday Japanese conversation.',
            'language' => 'Japanese',
            'level' => 'beginner',
            'instructor_id' => $instructor1->id,
            'thumbnail' => 'https://picsum.photos/id/1050/1200/675',
            'duration_hours' => 22,
            'total_lessons' => 4,
            'rating' => 4.8,
            'is_published' => true,
        ]);
        Course::create([
            'title' => 'Mandarin Chinese Essentials',
            'description' => 'Learn tones, pinyin, and survival Mandarin for business and travel.',
            'language' => 'Chinese',
            'level' => 'beginner',
            'instructor_id' => $instructor2->id,
            'thumbnail' => 'https://picsum.photos/id/1062/1200/675',
            'duration_hours' => 28,
            'total_lessons' => 5,
            'rating' => 4.9,
            'is_published' => true,
        ]);
        Course::create([
            'title' => 'Hindi for Daily Life',
            'description' => 'Speak and understand Hindi for shopping, travel, and making friends.',
            'language' => 'Hindi',
            'level' => 'beginner',
            'instructor_id' => $instructor1->id,
            'thumbnail' => 'https://picsum.photos/id/1074/1200/675',
            'duration_hours' => 20,
            'total_lessons' => 3,
            'rating' => 4.4,
            'is_published' => true,
        ]);

        // New demo courses
        Course::create([
            'title' => 'Russian for Beginners',
            'description' => 'Learn the Cyrillic alphabet, basic phrases, and travel essentials for Russian.',
            'language' => 'Russian',
            'level' => 'beginner',
            'instructor_id' => $instructor2->id,
            'thumbnail' => 'https://picsum.photos/id/1084/1200/675',
            'duration_hours' => 25,
            'total_lessons' => 4,
            'rating' => 4.5,
            'is_published' => true,
        ]);
        Course::create([
            'title' => 'Arabic Script and Conversation',
            'description' => 'Master the Arabic script and learn to introduce yourself and ask for directions.',
            'language' => 'Arabic',
            'level' => 'beginner',
            'instructor_id' => $instructor1->id,
            'thumbnail' => 'https://picsum.photos/id/1092/1200/675',
            'duration_hours' => 30,
            'total_lessons' => 5,
            'rating' => 4.7,
            'is_published' => true,
        ]);
        Course::create([
            'title' => 'Portuguese for Business',
            'description' => 'Essential Portuguese for meetings, emails, and business travel.',
            'language' => 'Portuguese',
            'level' => 'intermediate',
            'instructor_id' => $instructor2->id,
            'thumbnail' => 'https://picsum.photos/id/1100/1200/675',
            'duration_hours' => 26,
            'total_lessons' => 3,
            'rating' => 4.6,
            'is_published' => true,
        ]);
        Course::create([
            'title' => 'Korean Everyday Conversation',
            'description' => 'Speak and understand Korean for shopping, eating out, and making friends.',
            'language' => 'Korean',
            'level' => 'beginner',
            'instructor_id' => $instructor1->id,
            'thumbnail' => 'https://picsum.photos/id/1111/1200/675',
            'duration_hours' => 21,
            'total_lessons' => 3,
            'rating' => 4.8,
            'is_published' => true,
        ]);
        Course::create([
            'title' => 'Advanced Spanish Conversation',
            'description' => 'Debate, negotiate, and discuss complex topics in Spanish.',
            'language' => 'Spanish',
            'level' => 'advanced',
            'instructor_id' => $instructor1->id,
            'thumbnail' => 'https://picsum.photos/id/1122/1200/675',
            'duration_hours' => 36,
            'total_lessons' => 4,
            'rating' => 4.9,
            'is_published' => true,
        ]);
        Course::create([
            'title' => 'French for Travel and Tourism',
            'description' => 'Practical French for hotels, airports, and sightseeing.',
            'language' => 'French',
            'level' => 'beginner',
            'instructor_id' => $instructor2->id,
            'thumbnail' => 'https://picsum.photos/id/1133/1200/675',
            'duration_hours' => 19,
            'total_lessons' => 2,
            'rating' => 4.5,
            'is_published' => true,
        ]);

        $lesson1 = Lesson::create([
            'course_id' => $spanishCourse->id,
            'title' => 'Greetings and Introductions',
            'content' => <<<'TXT'
In this lesson you learn the phrases people use every day to say hello, goodbye, and introduce themselves.

Why it matters: greetings set the tone of every conversation. Spanish speakers often use different phrases depending on the time of day and how formal the situation is.

Core phrases (practice each out loud):
• Hola — Hello (any time)
• Buenos días — Good morning (until lunch)
• Buenas tardes — Good afternoon
• Buenas noches — Good evening / night
• ¿Cómo estás? — How are you? (informal)
• ¿Cómo está usted? — How are you? (formal)
• Me llamo… / Soy… — My name is… / I am…
• Mucho gusto — Nice to meet you
• Adiós / Hasta luego — Goodbye / See you later

Tip: Match the energy of the person you are speaking with—mirroring formality helps you sound natural.

Mini dialogue (read twice, then shadow the audio in your head):
— Buenos días, me llamo Ana. ¿Y tú?
— Hola, soy Carlos. Mucho gusto.
— Igualmente.
TXT
            ,
            'notes' => 'Shadow native audio when possible. Record yourself and compare vowel length in “Buenos días” vs “Buenas tardes”.',
            'order' => 1,
            'duration_minutes' => 35,
            'video_url' => 'https://www.youtube.com/results?search_query=spanish+greetings+beginners',
            'cover_image' => 'https://picsum.photos/id/866/1200/675',
            'is_published' => true,
        ]);

        $lesson2 = Lesson::create([
            'course_id' => $spanishCourse->id,
            'title' => 'Numbers and Counting',
            'content' => <<<'TXT'
Numbers unlock prices, times, addresses, and phone calls. Master 0–20 first, then learn the pattern for 30, 40, 50… up to 100.

0–10: cero, uno, dos, tres, cuatro, cinco, seis, siete, ocho, nueve, diez.
11–15: once, doce, trece, catorce, quince.
16–19: dieciséis, diecisiete, etc. (dieci- + root)

Tens: veinte, treinta, cuarenta, cincuenta…
Combining: treinta y cinco = 35 (literally “thirty and five”).

Stress matters: dieciséis has an accent on the last syllable.

Practice loop: say your age, street number, and today’s date in Spanish every morning for one week.
TXT
            ,
            'notes' => 'Clap syllables while counting aloud. Watch out for “veintiuna” agreements when counting feminine nouns.',
            'order' => 2,
            'duration_minutes' => 30,
            'video_url' => null,
            'cover_image' => 'https://picsum.photos/id/625/1200/675',
            'is_published' => true,
        ]);

        $lesson3 = Lesson::create([
            'course_id' => $spanishCourse->id,
            'title' => 'Common Verbs in the Present',
            'content' => <<<'TXT'
Three verbs you'll hear constantly are ser, estar, and tener. Each answers a different kind of question:

• Ser — identity, profession, inherent traits: Soy estudiante. Es lunes.
• Estar — location, temporary states: Estoy cansado. Estamos en Madrid.
• Tener — possession, age, obligations: Tengo dos hermanos. Tengo que estudiar.

Regular -ar verbs (hablar): hablo, hablas, habla, hablamos, habláis, hablan.

Pick five verbs you personally use (vivir, trabajar, estudiar…) and conjugate them for “yo” and “nosotros” until automatic.

Short writing task: five sentences about your week using at least three different verbs from this lesson.
TXT
            ,
            'notes' => 'Drill ser vs estar with adjectives: “está nervioso” (temporary) vs “es amable” (character).',
            'order' => 3,
            'duration_minutes' => 45,
            'video_url' => null,
            'cover_image' => 'https://picsum.photos/id/431/1200/675',
            'is_published' => true,
        ]);

        $lesson4 = Lesson::create([
            'course_id' => $spanishCourse->id,
            'title' => 'Food, Cafés, and Ordering',
            'content' => <<<'TXT'
Learn to navigate a menu, ask for recommendations, and handle allergies politely.

Useful chunks:
• La cuenta, por favor. — The bill, please.
• ¿Qué recomienda? — What do you recommend?
• Sin gluten / Sin nueces — Gluten-free / Without nuts
• Agua con gas / sin gas — Sparkling / still water
• Para mí… — For me… (ordering)

Culture note: Lunch can be late (2–4 pm in many places). “Menu del día” is often great value.

Role-play: You are at a tapas bar. Order two dishes and a drink, then ask for the bill.
TXT
            ,
            'notes' => 'Memorize two allergy phrases before traveling. Practice prices with numbers from Lesson 2.',
            'order' => 4,
            'duration_minutes' => 40,
            'video_url' => null,
            'cover_image' => 'https://picsum.photos/id/292/1200/675',
            'is_published' => true,
        ]);

        $flesson1 = Lesson::create([
            'course_id' => $frenchCourse->id,
            'title' => 'Polite Conversation and Café Talk',
            'content' => <<<'TXT'
Sound natural in Paris or Montréal by layering politeness markers.

Essentials:
• Bonjour / Bonsoir — Hello (day / evening)
• S’il vous plaît / Merci — Please / Thank you
• Excusez-moi — Excuse me
• Je voudrais… — I would like…
• L’addition, s’il vous plaît. — The check, please.

At a café: specify “sur place” (here) or “à emporter” (to go). Sizes often run petit / moyen / grand.

Listen for “vous” vs “tu”—when in doubt in service settings, start with “vous”.
TXT
            ,
            'notes' => 'Practice ordering with prices in euros. Record a 45-second café order.',
            'order' => 1,
            'duration_minutes' => 40,
            'video_url' => null,
            'cover_image' => 'https://picsum.photos/id/325/1200/675',
            'is_published' => true,
        ]);

        $flesson2 = Lesson::create([
            'course_id' => $frenchCourse->id,
            'title' => 'Past Tenses in Stories',
            'content' => <<<'TXT'
Intermediate French leans heavily on passé composé for completed events and imparfait for background.

Quick rule of thumb:
• Passé composé — “then this happened”: Hier, j’ai visité le musée.
• Imparfait — “how things were”: Il faisait froid; les rues étaient calmes.

Time markers help: souvent / toujours → often imparfait; une fois / hier → often passé composé.

Exercise: Write six sentences about a trip—three with passé composé, three with imparfait.
TXT
            ,
            'notes' => 'Create a two-column chart: “background” vs “main events” before writing.',
            'order' => 2,
            'duration_minutes' => 50,
            'video_url' => null,
            'cover_image' => 'https://picsum.photos/id/670/1200/675',
            'is_published' => true,
        ]);

        $flesson3 = Lesson::create([
            'course_id' => $frenchCourse->id,
            'title' => 'Opinions and Agreement',
            'content' => <<<'TXT'
Express agreement, doubt, and polite disagreement—critical for meetings and classroom French.

Phrases:
• Je suis d’accord. — I agree.
• Je ne suis pas sûr(e). — I’m not sure.
• À mon avis… — In my opinion…
• Je comprends, mais… — I understand, but…

Agreement review: past participles agree with preceding direct objects in “avoir” constructions (advanced but useful): Les photos? Je les ai prises.

Debate mini-topic: “Le télétravail est une bonne idée.” Prepare three pros and two cons.
TXT
            ,
            'notes' => 'Pair each opinion phrase with a rising or falling intonation to sound engaged, not blunt.',
            'order' => 3,
            'duration_minutes' => 45,
            'video_url' => null,
            'cover_image' => 'https://picsum.photos/id/804/1200/675',
            'is_published' => true,
        ]);

        $glesson1 = Lesson::create([
            'course_id' => $germanCourse->id,
            'title' => 'Formal Register and Email Openings',
            'content' => <<<'TXT'
Advanced German often appears first in academic and workplace email.

Openings:
• Sehr geehrte Damen und Herren, — Dear Sir or Madam,
• Sehr geehrte Frau [Name], — Dear Ms. …
• Mit freundlichen Grüßen — Kind regards

Sentence frames:
• Ich möchte Sie darüber informieren, dass… — I would like to inform you that…
• Für Rückfragen stehe ich gerne zur Verfügung. — Please contact me if you have questions.

Avoid slang in these contexts. Prefer nominal style: “die Durchführung” rather than “wie wir es machen”.
TXT
            ,
            'notes' => 'Rewrite one informal paragraph into formal Sie-form for homework.',
            'order' => 1,
            'duration_minutes' => 55,
            'video_url' => null,
            'cover_image' => 'https://picsum.photos/id/1076/1200/675',
            'is_published' => true,
        ]);

        $glesson2 = Lesson::create([
            'course_id' => $germanCourse->id,
            'title' => 'Complex Clauses and Opinion Essays',
            'content' => <<<'TXT'
Link ideas with connectors examiners love: obwohl (although), während (while), sodass (so that), zumal (especially since).

Opinion scaffold:
1. Einleitung — state issue
2. Hauptteil — two arguments with examples
3. Schluss — balanced conclusion

Example stem: “Obwohl digitales Lernen flexibel ist, bleibt Präsenzunterricht für Feedback unverzichtbar.”

Write 180–220 words on one controversial education topic; highlight every subclause connector you used.
TXT
            ,
            'notes' => 'Read one DW Nachrichten artikel aloud; underline three subclauses.',
            'order' => 2,
            'duration_minutes' => 60,
            'video_url' => null,
            'cover_image' => 'https://picsum.photos/id/180/1200/675',
            'is_published' => true,
        ]);

        $exercise1 = Exercise::create([
            'lesson_id' => $lesson1->id,
            'title' => 'Greetings Matching Exercise',
            'description' => 'Match Spanish greetings with their English translations.',
            'exercise_type' => 'matching',
            'content' => json_encode([
                'Hola' => 'Hello',
                'Buenos días' => 'Good morning',
                'Buenas noches' => 'Good evening',
                'Adiós' => 'Goodbye',
            ]),
            'difficulty_level' => 1,
            'points' => 10,
        ]);

        $exercise2 = Exercise::create([
            'lesson_id' => $lesson1->id,
            'title' => 'Introduction Speaking Exercise',
            'description' => 'Write or record yourself introducing yourself in Spanish.',
            'exercise_type' => 'speaking',
            'content' => 'Introduce yourself with your name, where you are from, and one hobby.',
            'instructions' => 'Submit a short paragraph (or describe an audio file you uploaded elsewhere).',
            'difficulty_level' => 2,
            'points' => 15,
        ]);

        $exercise3 = Exercise::create([
            'lesson_id' => $lesson2->id,
            'title' => 'Number Listening Exercise',
            'description' => 'Listen to Spanish numbers and write them down as digits.',
            'exercise_type' => 'listening',
            'content' => 'Listen to any Spanish numbers 1–30 audio and transcribe: quince, veintitrés, diecinueve, treinta.',
            'difficulty_level' => 1,
            'points' => 10,
        ]);

        $exercise4 = Exercise::create([
            'lesson_id' => $lesson3->id,
            'title' => 'Verb Conjugation Practice',
            'description' => 'Conjugate the verb in present tense for the subject given.',
            'exercise_type' => 'writing',
            'content' => "Conjugate 'hablar' for: yo, tú, ella, nosotros. Then make one sentence with 'estar' about how you feel today.",
            'difficulty_level' => 2,
            'points' => 12,
        ]);

        Exercise::create([
            'lesson_id' => $lesson4->id,
            'title' => 'Order at a Restaurant',
            'description' => 'Write a short dialogue: you order food, ask for water, and request the bill.',
            'exercise_type' => 'writing',
            'content' => 'Include at least six lines, two questions to the waiter, and phrases from the lesson.',
            'difficulty_level' => 2,
            'points' => 14,
        ]);

        Exercise::create([
            'lesson_id' => $flesson1->id,
            'title' => 'French Greetings Match',
            'description' => 'Match French phrases to English.',
            'exercise_type' => 'matching',
            'content' => json_encode([
                'Bonjour' => 'Hello / Good day',
                'Merci' => 'Thank you',
                'S\'il vous plaît' => 'Please',
                'Au revoir' => 'Goodbye',
            ]),
            'difficulty_level' => 1,
            'points' => 10,
        ]);

        Exercise::create([
            'lesson_id' => $flesson2->id,
            'title' => 'Passé composé vs imparfait',
            'description' => 'Choose the better tense for each sentence starter.',
            'exercise_type' => 'writing',
            'content' => "Explain in 3–4 sentences why you'd pick passé composé or imparfait for: 'Quand j’étais petit…' vs 'Un jour, j’ai…'",
            'difficulty_level' => 3,
            'points' => 16,
        ]);

        Exercise::create([
            'lesson_id' => $glesson1->id,
            'title' => 'Formal email rewrite',
            'description' => 'Rewrite the informal draft into formal German (Sie).',
            'exercise_type' => 'writing',
            'content' => "Informal: 'Hey, kannst du mir das morgen schicken?' Rewrite as a professional request with greeting and closing.",
            'difficulty_level' => 3,
            'points' => 18,
        ]);

        $quiz1 = Quiz::create([
            'course_id' => $spanishCourse->id,
            'title' => 'Spanish Foundations Quiz',
            'description' => 'Covers greetings, numbers, verbs, and café vocabulary from this beginner track.',
            'passing_score' => 70,
            'total_questions' => 10,
            'time_limit_minutes' => 25,
            'show_results_immediately' => true,
            'is_published' => true,
        ]);

        $quizFrench = Quiz::create([
            'course_id' => $frenchCourse->id,
            'title' => 'French Intermediate Checkpoint',
            'description' => 'Politeness, past tenses, and expressing opinions.',
            'passing_score' => 72,
            'total_questions' => 6,
            'time_limit_minutes' => 20,
            'show_results_immediately' => true,
            'is_published' => true,
        ]);

        $quizGerman = Quiz::create([
            'course_id' => $germanCourse->id,
            'title' => 'German Advanced Skills Quiz',
            'description' => 'Formal language, connectors, and essay structure.',
            'passing_score' => 75,
            'total_questions' => 5,
            'time_limit_minutes' => 18,
            'show_results_immediately' => true,
            'is_published' => true,
        ]);

        $spanishQuestions = [
            ['What does "Hola" mean?', 'multiple_choice', ['Hello', 'Goodbye', 'Thank you', 'Please'], 'Hello', '“Hola” is the universal informal hello.', 1],
            ['How do you say "Good morning" in Spanish?', 'multiple_choice', ['Buenos días', 'Buenas noches', 'Buenas tardes', 'Buen día'], 'Buenos días', null, 1],
            ['What is 5 in Spanish?', 'multiple_choice', ['Tres', 'Cuatro', 'Cinco', 'Seis'], 'Cinco', null, 1],
            ['Which verb fits identity? "___ estudiante."', 'multiple_choice', ['Soy', 'Estoy', 'Tengo', 'Voy'], 'Soy', 'Ser describes what someone “is” in identity/profession.', 1],
            ['Spanish uses ¿ and ¡ in written questions and exclamations.', 'true_false', null, 'true', 'Inverted punctuation opens questions/exclamations in standard Spanish.', 1],
            ['“Treinta y ocho” is which number?', 'multiple_choice', ['28', '38', '48', '83'], '38', null, 1],
            ['Me llamo means roughly:', 'short_answer', null, 'My name is', 'Often followed by your name.', 2],
            ['“We speak” (hablar, nosotros)', 'multiple_choice', ['hablan', 'hablamos', 'hablo', 'hablas'], 'hablamos', '-amos for nosotros with regular -ar verbs.', 1],
            ['“Good evening” as a greeting when you arrive at night—common choice:', 'multiple_choice', ['Buenos días', 'Buenas noches', 'Buenas tardes', 'Hola'], 'Buenas noches', 'Context matters; “Buenas noches” works late.', 1],
            ['“Thank you very much” — common phrase:', 'multiple_choice', ['Muchas gracias', 'De nada', 'Lo siento', 'Por favor'], 'Muchas gracias', null, 1],
        ];

        foreach ($spanishQuestions as $i => $row) {
            QuizQuestion::create([
                'quiz_id' => $quiz1->id,
                'question' => $row[0],
                'question_type' => $row[1],
                'options' => $row[2] !== null ? json_encode($row[2]) : null,
                'correct_answer' => $row[3],
                'explanation' => $row[4],
                'points' => $row[5],
                'order' => $i + 1,
            ]);
        }

        $frenchQuestions = [
            ['“Merci” means:', 'multiple_choice', ['Hello', 'Thank you', 'Sorry', 'Goodbye'], 'Thank you', null, 1],
            ['“Je voudrais” expresses:', 'multiple_choice', ['I hate', 'I would like', 'I am', 'I forgot'], 'I would like', null, 1],
            ['Passé composé often pairs with time words like “hier”.', 'true_false', null, 'true', null, 1],
            ['“À mon avis” introduces:', 'multiple_choice', ['A fact', 'An opinion', 'A command', 'A question'], 'An opinion', null, 1],
            ['Formal “you” in a Paris café with staff you don’t know—usually:', 'multiple_choice', ['tu', 'vous', 'on', 'nous'], 'vous', 'Default to vous in service contexts unless invited otherwise.', 1],
            ['“Nous avons fini” — fini is past participle with auxiliary:', 'multiple_choice', ['être only', 'avoir', 'aller', 'faire'], 'avoir', 'Most verbs use avoir in passé composé.', 1],
        ];

        foreach ($frenchQuestions as $i => $row) {
            QuizQuestion::create([
                'quiz_id' => $quizFrench->id,
                'question' => $row[0],
                'question_type' => $row[1],
                'options' => $row[2] !== null ? json_encode($row[2]) : null,
                'correct_answer' => $row[3],
                'explanation' => $row[4],
                'points' => $row[5],
                'order' => $i + 1,
            ]);
        }

        $germanQuestions = [
            ['“Mit freundlichen Grüßen” is a:', 'multiple_choice', ['Greeting opening', 'Closing line', 'Subject line', 'Complaint'], 'Closing line', null, 1],
            ['“Sehr geehrte Damen und Herren” opens:', 'multiple_choice', ['A text to a friend', 'A formal letter/email', 'A poem', 'A tweet'], 'A formal letter/email', null, 1],
            ['“Obwohl” introduces a:', 'multiple_choice', ['Purpose clause', 'Concessive clause', 'Time clause', 'Question'], 'Concessive clause', 'Though / although.', 1],
            ['Nominal style prefers nouns over verbs in formal German.', 'true_false', null, 'true', 'e.g., “Durchführung” vs “wie wir es machen”.', 1],
            ['“zumal” roughly means:', 'multiple_choice', ['although', 'especially since', 'therefore', 'nevertheless'], 'especially since', null, 1],
        ];

        foreach ($germanQuestions as $i => $row) {
            QuizQuestion::create([
                'quiz_id' => $quizGerman->id,
                'question' => $row[0],
                'question_type' => $row[1],
                'options' => $row[2] !== null ? json_encode($row[2]) : null,
                'correct_answer' => $row[3],
                'explanation' => $row[4],
                'points' => $row[5],
                'order' => $i + 1,
            ]);
        }

        $quiz1->update(['total_questions' => QuizQuestion::where('quiz_id', $quiz1->id)->count()]);
        $quizFrench->update(['total_questions' => QuizQuestion::where('quiz_id', $quizFrench->id)->count()]);
        $quizGerman->update(['total_questions' => QuizQuestion::where('quiz_id', $quizGerman->id)->count()]);

        CourseEnrollment::create([
            'user_id' => $student1->id,
            'course_id' => $spanishCourse->id,
            'status' => 'in_progress',
            'completion_percentage' => 45,
            'enrolled_at' => now()->subDays(30),
        ]);

        CourseEnrollment::create([
            'user_id' => $student2->id,
            'course_id' => $frenchCourse->id,
            'status' => 'enrolled',
            'completion_percentage' => 0,
            'enrolled_at' => now()->subDays(5),
        ]);

        CourseEnrollment::create([
            'user_id' => $student1->id,
            'course_id' => $frenchCourse->id,
            'status' => 'completed',
            'completion_percentage' => 100,
            'enrolled_at' => now()->subDays(60),
            'completed_at' => now()->subDays(10),
        ]);

        CourseEnrollment::create([
            'user_id' => $student3->id,
            'course_id' => $spanishCourse->id,
            'status' => 'in_progress',
            'completion_percentage' => 75,
            'enrolled_at' => now()->subDays(20),
        ]);

        LessonProgress::create([
            'user_id' => $student1->id,
            'lesson_id' => $lesson1->id,
            'is_completed' => true,
            'progress_percentage' => 100,
            'started_at' => now()->subDays(30),
            'completed_at' => now()->subDays(29),
        ]);

        LessonProgress::create([
            'user_id' => $student1->id,
            'lesson_id' => $lesson2->id,
            'is_completed' => false,
            'progress_percentage' => 60,
            'started_at' => now()->subDays(28),
        ]);

        LessonProgress::create([
            'user_id' => $student3->id,
            'lesson_id' => $lesson1->id,
            'is_completed' => true,
            'progress_percentage' => 100,
            'started_at' => now()->subDays(20),
            'completed_at' => now()->subDays(19),
        ]);

        LessonProgress::create([
            'user_id' => $student3->id,
            'lesson_id' => $lesson2->id,
            'is_completed' => true,
            'progress_percentage' => 100,
            'started_at' => now()->subDays(18),
            'completed_at' => now()->subDays(17),
        ]);

        LessonProgress::create([
            'user_id' => $student3->id,
            'lesson_id' => $lesson3->id,
            'is_completed' => false,
            'progress_percentage' => 70,
            'started_at' => now()->subDays(15),
        ]);

        ExerciseSubmission::create([
            'user_id' => $student1->id,
            'exercise_id' => $exercise1->id,
            'submission_content' => 'User submission for greetings exercise',
            'score' => 9,
            'feedback' => 'Great job! You got 9 out of 10 correct.',
            'status' => 'graded',
            'submitted_at' => now()->subDays(29),
            'graded_at' => now()->subDays(29),
        ]);

        ExerciseSubmission::create([
            'user_id' => $student1->id,
            'exercise_id' => $exercise2->id,
            'submission_content' => 'User audio recording for introduction',
            'score' => 12,
            'feedback' => 'Excellent pronunciation! Keep it up.',
            'status' => 'graded',
            'submitted_at' => now()->subDays(28),
            'graded_at' => now()->subDays(28),
        ]);

        ExerciseSubmission::create([
            'user_id' => $student3->id,
            'exercise_id' => $exercise1->id,
            'submission_content' => 'User submission for greetings exercise',
            'score' => 10,
            'feedback' => 'Perfect! All answers are correct.',
            'status' => 'graded',
            'submitted_at' => now()->subDays(19),
            'graded_at' => now()->subDays(19),
        ]);

        ExerciseSubmission::create([
            'user_id' => $student3->id,
            'exercise_id' => $exercise3->id,
            'submission_content' => 'User submission for listening exercise',
            'score' => 8,
            'feedback' => 'Good effort. Review numbers 15-20.',
            'status' => 'graded',
            'submitted_at' => now()->subDays(17),
            'graded_at' => now()->subDays(17),
        ]);

        UserQuizResult::create([
            'user_id' => $student1->id,
            'quiz_id' => $quiz1->id,
            'score' => 85,
            'total_questions' => 10,
            'correct_answers' => 8,
            'passed' => true,
            'time_spent_minutes' => 15,
            'completed_at' => now()->subDays(25),
        ]);

        UserQuizResult::create([
            'user_id' => $student3->id,
            'quiz_id' => $quiz1->id,
            'score' => 90,
            'total_questions' => 10,
            'correct_answers' => 9,
            'passed' => true,
            'time_spent_minutes' => 12,
            'completed_at' => now()->subDays(14),
        ]);

        UserQuizResult::create([
            'user_id' => $student3->id,
            'quiz_id' => $quiz1->id,
            'score' => 75,
            'total_questions' => 10,
            'correct_answers' => 7,
            'passed' => true,
            'time_spent_minutes' => 18,
            'completed_at' => now()->subDays(10),
        ]);

        UserQuizResult::create([
            'user_id' => $student1->id,
            'quiz_id' => $quizFrench->id,
            'score' => 88,
            'total_questions' => 6,
            'correct_answers' => 5,
            'passed' => true,
            'time_spent_minutes' => 14,
            'completed_at' => now()->subDays(8),
        ]);
    }
}
