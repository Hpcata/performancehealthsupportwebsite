<h2>New Quiz Submission</h2>
<p><strong>Name:</strong> {{ $user->name ?? 'Anonymous' }}</p>
<p><strong>Email:</strong> {{ $user->email ?? 'Not Provided' }}</p>
<p><strong>Phone:</strong> {{ $user->phone ?? 'Not Provided' }}</p>

<h4>Quiz Result :</h4>
<p>Nutritions : {{ $questionnaire->nutrition_score }} - {{ $questionnaire->nutrition_feedback }}</p>
<p>Supplements : {{ $questionnaire->supplements_score }} - {{ $questionnaire->supplements_feedback }}</p>
<p>Sports : {{ $questionnaire->sports_score }} - {{ $questionnaire->sports_feedback }}</p>

