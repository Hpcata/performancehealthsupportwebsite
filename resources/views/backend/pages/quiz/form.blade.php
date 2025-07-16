@extends('backend.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
                <h3 class="fw-bold mb-0">Quiz Form</h3>

                    <a href="{{ route('admin.plans.index') }}" class="btn btn-primary btn-set-task">Back</a>
              
            </div>
        </div>
    </div>
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.quiz.store') }}" id="quizForm">
                        @csrf
                        <!-- Nutrition Section -->
                        <div class="accordion mb-4" id="nutritionAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="nutritionHeading">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#nutritionCollapse" aria-expanded="true" aria-controls="nutritionCollapse">
                                        Nutrition Questions (1-9)
                                    </button>
                                </h2>
                                <div id="nutritionCollapse" class="accordion-collapse collapse show" aria-labelledby="nutritionHeading" data-bs-parent="#nutritionAccordion">
                                    <div class="accordion-body">
                                        @if(isset($questions['nutrition']))
                                            @foreach($questions['nutrition'] as $question)
                                                <div class="step-tab-box nutrition-form" id="div{{ $question->question_index }}">
                                                    <div class="card">
                                                        <div class="p-3 card-header bg-white">
                                                            <div class="d-flex align-items-center">
                                                                <h5 class="m-0 quiz-ques">{{ $question->question_index }}. {{ $question->question_text }}</h5>
                                                                <span class="ms-2 general-error-message text-danger"></span>
                                                            </div>
                                                            <input type="hidden" name="questions[{{ $question->question_index }}][form_slug]" value="nutrition" />
                                                            <input type="hidden" name="questions[{{ $question->question_index }}][question_index]" value="{{ $question->question_index }}" />
                                                            <input type="hidden" name="questions[{{ $question->question_index }}][question_text]" value="{{ $question->question_text }}" />
                                                        </div>
                                                        <div class="card-body p-0">
                                                            @if(strpos($question->question_text, 'high or low') !== false)
                                                                <div class="table-responsive">
                                                                    <table class="table m-0">
                                                                        <thead>
                                                                            <tr>
                                                                                <th></th>
                                                                                <th class="text-center">High</th>
                                                                                <th class="text-center">Low</th>
                                                                                <th class="text-center">Unsure</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @php
                                                                                $options = is_string($question->options) ? json_decode($question->options, true) : $question->options;
                                                                                $correctAnswers = is_string($question->correct_answer) ? json_decode($question->correct_answer, true) : $question->correct_answer;
                                                                            @endphp
                                                                            @foreach($options as $food => $values)
                                                                                <tr>
                                                                                    <td>{{ $food }}</td>
                                                                                    <td class="text-center">
                                                                                        <input class="form-check-input" type="radio" 
                                                                                            name="questions[{{ $question->question_index }}][options][{{ $food }}]" 
                                                                                            value="{{ $values['High'] }}" 
                                                                                            {{ isset($correctAnswers[$food]) && $correctAnswers[$food]['option'] === 'High' ? 'checked' : '' }}>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input class="form-check-input" type="radio" 
                                                                                            name="questions[{{ $question->question_index }}][options][{{ $food }}]" 
                                                                                            value="{{ $values['Low'] }}" 
                                                                                            {{ isset($correctAnswers[$food]) && $correctAnswers[$food]['option'] === 'Low' ? 'checked' : '' }}>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input class="form-check-input" type="radio" 
                                                                                            name="questions[{{ $question->question_index }}][options][{{ $food }}]" 
                                                                                            value="{{ $values['Unsure'] }}" 
                                                                                            {{ isset($correctAnswers[$food]) && $correctAnswers[$food]['option'] === 'Unsure' ? 'checked' : '' }}>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            @else
                                                                <div class="row px-2">
                                                                    <div class="col-md-6">
                                                                        <div class="form-floating my-3">
                                                                            @php
                                                                                $options = is_string($question->options) ? json_decode($question->options, true) : $question->options;
                                                                            @endphp
                                                                            @foreach($options as $option => $value)
                                                                                <div class="form-check my-2">
                                                                                    <input class="form-check-input" type="radio" 
                                                                                        name="questions[{{ $question->question_index }}][options][{{ $option }}]" 
                                                                                        value="{{ $value }}" 
                                                                                        {{ isset($question->correct_answer[$option]) && $question->correct_answer[$option] == $value ? 'checked' : '' }}>
                                                                                    <label class="form-check-label">{{ $option }}</label>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <!-- Original form structure for when no data exists -->
                                            <!-- Question 1 -->
                                            <div class="step-tab-box nutrition-form" id="div1">
                                                <div class="card">
                                                    <div class="p-3 card-header bg-white">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="m-0">1. Do you think these foods are <strong class="text-primary">high</strong> or <strong class="text-primary">low</strong> in <strong class="text-primary">carbohydrate</strong>?</h5>
                                                            <span class="ms-2 general-error-message text-danger"></span>
                                                        </div>
                                                        <input type="hidden" name="questions[1][form_slug]" value="nutrition" />
                                                        <input type="hidden" name="questions[1][question_index]" value="1" />
                                                        <input type="hidden" name="questions[1][question_text]" value="Do you think these foods are high or low in carbohydrate?" />
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="table-responsive">
                                                            <table class="table m-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th class="text-center">High</th>
                                                                        <th class="text-center">Low</th>
                                                                        <th class="text-center">Unsure</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>Chicken</td>
                                                                        <td class="text-center">
                                                                            <input class="form-check-input" type="radio" name="questions[1][options][Chicken]" value="0" id="Chicken-1">
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <input class="form-check-input" type="radio" name="questions[1][options][Chicken]" value="1" id="Chicken-2">
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <input class="form-check-input" type="radio" name="questions[1][options][Chicken]" value="0" id="Chicken-3">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Baked beans</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Baked beans]" value="1" id="Bakedbeans-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Baked beans]" value="0" id="Bakedbeans-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Baked beans]" value="0" id="Bakedbeans-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Grain bread</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Grain bread]" value="1" id="GrainBread-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Grain bread]" value="0" id="GrainBread-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Grain bread]" value="0" id="GrainBread-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Avocado</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Avocado]" value="0" id="Avocado-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Avocado]" value="1" id="Avocado-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Avocado]" value="0" id="Avocado-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Weet-bix</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Weet-bix]" value="1" id="Weet-bix-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Weet-bix]" value="0" id="Weet-bix-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Weet-bix]" value="0" id="Weet-bix-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Fruit yoghurt</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Fruit yoghurt]" value="1" id="FruitYoghurt-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Fruit yoghurt]" value="0" id="FruitYoghurt-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Fruit yoghurt]" value="0" id="FruitYoghurt-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Crumpets</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Crumpets]" value="1" id="Crumpets-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Crumpets]" value="0" id="Crumpets-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Crumpets]" value="0" id="Crumpets-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cream</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Cream]" value="0" id="Cream-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Cream]" value="1" id="Cream-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[1][options][Cream]" value="0" id="Cream-3"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Question 2 -->
                                            <div class="step-tab-box nutrition-form" id="div2">
                                                <div class="card">
                                                    <div class="p-3 card-header bg-white">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="m-0">2. Do you think these foods are <strong class="text-primary">high</strong> or <strong class="text-primary">low</strong> in <strong class="text-primary">protein</strong>?</h5>
                                                            <span class="ms-2 general-error-message text-danger"></span>
                                                        </div>
                                                        <input type="hidden" name="questions[2][form_slug]" value="nutrition" />
                                                        <input type="hidden" name="questions[2][question_index]" value="2" />
                                                        <input type="hidden" name="questions[2][question_text]" value="Do you think these foods are high or low in protein?" />
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th class="text-center">High</th>
                                                                        <th class="text-center">Low</th>
                                                                        <th class="text-center">Unsure</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>Salmon</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Salmon]" value="1" id="Salmon-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Salmon]" value="0" id="Salmon-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Salmon]" value="0" id="Salmon-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Baked beans</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Baked beans]" value="1" id="Bakedbeans-11"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Baked beans]" value="0" id="Bakedbeans-12"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Baked beans]" value="0" id="Bakedbeans-13"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Fruit</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Fruit]" value="0" id="Fruit-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Fruit]" value="1" id="Fruit-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Fruit]" value="0" id="Fruit-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Hummus</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Hummus]" value="0" id="Hummus-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Hummus]" value="1" id="Hummus-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Hummus]" value="0" id="Hummus-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cornflakes cereal</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Cornflakes cereal]" value="0" id="CornflakesCereal-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Cornflakes cereal]" value="1" id="CornflakesCereal-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Cornflakes cereal]" value="0" id="CornflakesCereal-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Almonds</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Almonds]" value="1" id="Almonds-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Almonds]" value="0" id="Almonds-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Almonds]" value="0" id="Almonds-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Flavoured milk</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Flavoured milk]" value="1" id="FlavouredMilk-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Flavoured milk]" value="0" id="FlavouredMilk-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Flavoured milk]" value="0" id="FlavouredMilk-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Ice cream</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Ice cream]" value="0" id="IceCream-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Ice cream]" value="1" id="IceCream-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Ice cream]" value="0" id="IceCream-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Almond/oat milk</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Almond/oat milk]" value="0" id="Almond-oat-milk-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Almond/oat milk]" value="1" id="Almond-oat-milk-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[2][options][Almond/oat milk]" value="0" id="Almond-oat-milk-3"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Question 3 -->
                                            <div class="step-tab-box nutrition-form" id="div3">
                                                <div class="card">
                                                    <div class="p-3 card-header bg-white">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="m-0">3. Do you think these foods are <strong class="text-primary">high</strong> or <strong class="text-primary">low</strong> in <strong class="text-primary">fat</strong>?</h5>
                                                            <span class="ms-2 general-error-message text-danger"></span>
                                                        </div>
                                                        <input type="hidden" name="questions[3][form_slug]" value="nutrition" />
                                                        <input type="hidden" name="questions[3][question_index]" value="3" />
                                                        <input type="hidden" name="questions[3][question_text]" value="Do you think these foods are high or low in fat?" />
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th class="text-center">High</th>
                                                                        <th class="text-center">Low</th>
                                                                        <th class="text-center">Unsure</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>Avocado</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[3][options][Avocado]" value="1" id="Avocado-11"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[3][options][Avocado]" value="0" id="Avocado-12"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[3][options][Avocado]" value="0" id="Avocado-13"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Baked beans</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[3][options][Baked beans]" value="0" id="BakedBeans-21"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[3][options][Baked beans]" value="1" id="BakedBeans-22"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[3][options][Baked beans]" value="0" id="BakedBeans-23"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cottage cheese</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[3][options][Cottage cheese]" value="0" id="CottageCheese-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[3][options][Cottage cheese]" value="1" id="CottageCheese-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[3][options][Cottage cheese]" value="0" id="CottageCheese-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Peanut butter</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[3][options][Peanut butter]" value="1" id="PeanutButter-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[3][options][Peanut butter]" value="0" id="PeanutButter-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[3][options][Peanut butter]" value="0" id="PeanutButter-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Crumpets</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[3][options][Crumpets]" value="0" id="Crumpets-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[3][options][Crumpets]" value="1" id="Crumpets-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[3][options][Crumpets]" value="0" id="Crumpets-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cheddar/Tatsy cheese</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[3][options][Cheddar/Tatsy cheese]" value="1" id="CheddarTatsyCheese-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[3][options][Cheddar/Tatsy cheese]" value="0" id="CheddarTatsyCheese-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[3][options][Cheddar/Tatsy cheese]" value="0" id="CheddarTatsyCheese-3"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Question 4 -->
                                            <div class="step-tab-box nutrition-form" id="div4">
                                                <div class="card">
                                                    <div class="p-3 card-header bg-white">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="m-0">4. Do you think these foods are <strong class="text-primary">high</strong> or <strong class="text-primary">low</strong> in <strong class="text-primary">healthy fats</strong>?</h5>
                                                            <span class="ms-2 general-error-message text-danger"></span>
                                                        </div>
                                                        <input type="hidden" name="questions[4][form_slug]" value="nutrition" />
                                                        <input type="hidden" name="questions[4][question_index]" value="4" />
                                                        <input type="hidden" name="questions[4][question_text]" value="Do you think these foods are high or low in healthy fat?" />
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th class="text-center">High</th>
                                                                        <th class="text-center">Low</th>
                                                                        <th class="text-center">Unsure</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>Butter</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Butter]" value="0" id="Butter-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Butter]" value="1" id="Butter-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Butter]" value="0" id="Butter-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Extra virgin olive oil</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Extra virgin olive oil]" value="1" id="OliveOil-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Extra virgin olive oil]" value="0" id="OliveOil-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Extra virgin olive oil]" value="0" id="OliveOil-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Whole milk</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Whole milk]" value="0" id="WholeMilk-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Whole milk]" value="1" id="WholeMilk-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Whole milk]" value="0" id="WholeMilk-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Potato crisps</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Potato crisps]" value="0" id="PotatoCrisps-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Potato crisps]" value="1" id="PotatoCrisps-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Potato crisps]" value="0" id="PotatoCrisps-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Salmon</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Salmon]" value="1" id="Salmon-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Salmon]" value="0" id="Salmon-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Salmon]" value="0" id="Salmon-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Dark chocolate</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Dark chocolate]" value="0" id="DarkChocolate-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Dark chocolate]" value="1" id="DarkChocolate-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Dark chocolate]" value="0" id="DarkChocolate-3"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Macadamia nuts</td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Macadamia nuts]" value="1" id="MacadamiaNuts-1"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Macadamia nuts]" value="0" id="MacadamiaNuts-2"></td>
                                                                        <td class="text-center"><input class="form-check-input" type="radio" name="questions[4][options][Macadamia nuts]" value="0" id="MacadamiaNuts-3"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Question 5 -->
                                            <div class="step-tab-box nutrition-form" id="div5">
                                                <div class="card">
                                                    <div class="card">
                                                        <div class="p-3 card-header bg-white">
                                                            <div class="d-flex align-items-center">
                                                                <h5 class="m-0">5. Which of these foods has the most iron?</h5>
                                                                <span class="ms-2 general-error-message text-danger"></span>
                                                            </div>
                                                            <input type="hidden" name="questions[5][form_slug]" value="nutrition" />
                                                            <input type="hidden" name="questions[5][question_index]" value="5" />
                                                            <input type="hidden" name="questions[5][question_text]" value="Which of these foods has the most iron?" />
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <div class="row px-2">
                                                                <!-- First Row -->
                                                                <div class="col-md-4">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[5][options][Spinach, cooked, 1/2 cup]" value="0" id="food1">
                                                                        <label class="form-check-label" for="food1">
                                                                            Spinach, cooked, 1/2 cup
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[5][options][Brown rice, cooked, 1 cup]" value="0" id="food2">
                                                                        <label class="form-check-label" for="food2">
                                                                            Brown rice, cooked, 1 cup
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[5][options][Grilled steak, 130g]" value="1" id="food3">
                                                                        <label class="form-check-label" for="food3">
                                                                            Grilled steak, 130g
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row px-2">
                                                                <!-- Second Row -->
                                                                <div class="col-md-4">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[5][options][Tuna, small tin, 90g]" value="0" id="food4">
                                                                        <label class="form-check-label" for="food4">
                                                                            Tuna, small tin, 90g
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[5][options][Almonds/cashews, ~30 nuts]" value="0" id="food5">
                                                                        <label class="form-check-label" for="food5">
                                                                            Almonds/cashews, ~30 nuts
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[5][options][Unsure]" value="0" id="food6">
                                                                        <label class="form-check-label" for="food6">
                                                                            Unsure
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Question 6 -->
                                            <div class="step-tab-box nutrition-form" id="div6">
                                                <div class="card">
                                                    <div class="p-3 card-header bg-white">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="m-0">6. Approximately how many decisions do we make every day about what we eat? </h5>
                                                            <input type="hidden" name="questions[6][form_slug]" value="nutrition" />
                                                            <input type="hidden" name="questions[6][question_index]" value="6" />
                                                            <input type="hidden" name="questions[6][question_text]" value="Approximately how many decisions do we make every day about what we eat?" />
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="row px-2 align-items-center">
                                                            <div class="form-floating my-3 col">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="questions[6][options][50-100]" value="0" id="decision1">
                                                                    <label class="form-check-label" for="decision1">
                                                                        50-100
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-floating my-3 col">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="questions[6][options][100-150]" value="0" id="decision2">
                                                                    <label class="form-check-label" for="decision2">
                                                                        100-150
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-floating my-3 col">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="questions[6][options][150-200]" value="0" id="decision3">
                                                                    <label class="form-check-label" for="decision3">
                                                                        150-200
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-floating my-3 col">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="questions[6][options][Over 200]" value="1" id="decision4">
                                                                    <label class="form-check-label" for="decision4">
                                                                        Over 200
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-floating my-3 col">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="questions[6][options][Unsure]" value="0" id="decision5">
                                                                    <label class="form-check-label" for="decision5">
                                                                        Unsure
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Question 7 -->
                                            <div class="step-tab-box nutrition-form" id="div7">
                                                <div class="card">
                                                    <div class="p-3 card-header bg-white">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="m-0">7. Which of the following is NOT a 'Macronutrient'? </h5>
                                                            
                                                            <input type="hidden" name="questions[7][form_slug]" value="nutrition" />
                                                            <input type="hidden" name="questions[7][question_index]" value="7" />
                                                            <input type="hidden" name="questions[7][question_text]" value="Which of the following is NOT a 'Macronutrient'?" />
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="row px-2 align-items-center">
                                                            <div class="form-floating my-3 col">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="questions[7][options][Iron]" value="1" id="Macronutrien1">
                                                                    <label class="form-check-label" for="Macronutrien1">
                                                                    Iron 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-floating my-3 col">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="questions[7][options][Carbohydrate]" value="0" id="Macronutrien2">
                                                                    <label class="form-check-label" for="Macronutrien2">
                                                                    Carbohydrate
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-floating my-3 col">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="questions[7][options][Protein]" value="0" id="Macronutrien3">
                                                                    <label class="form-check-label" for="Macronutrien3">
                                                                    Protein 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-floating my-3 col">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="questions[7][options][Alcohol]" value="0" id="Macronutrien4">
                                                                    <label class="form-check-label" for="Macronutrien4">
                                                                    Alcohol 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-floating my-3 col">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="questions[7][options][Fat]" value="0" id="Macronutrien5">
                                                                    <label class="form-check-label" for="Macronutrien5">
                                                                    Fat 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-floating my-3 col">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="questions[7][options][Unsure]" value="0" id="Macronutrien6">
                                                                    <label class="form-check-label" for="Macronutrien6">
                                                                        Unsure
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Question 8 -->
                                            <div class="step-tab-box nutrition-form" id="div8">
                                                <div class="card">
                                                    <div class="card">
                                                        <div class="p-3 card-header bg-white">
                                                            <div class="d-flex align-items-center">
                                                                <h5 class="m-0">8. Which of these foods has the most calcium? </h5>
                                                                <input type="hidden" name="questions[8][form_slug]" value="nutrition" />
                                                                <input type="hidden" name="questions[8][question_index]" value="8" />
                                                                <input type="hidden" name="questions[8][question_text]" value="Which of these foods has the most calcium?" />
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <div class="row px-2 align-items-center">
                                                                <div class="form-floating my-3 col">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="questions[8][options][Baby spinach, 1 cup]" value="0" id="calciu1">
                                                                        <label class="form-check-label" for="calciu1">
                                                                        Baby spinach, 1 cup
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-floating my-3 col">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="questions[8][options][Firm tofu, 100g]" value="1" id="calcium2">
                                                                        <label class="form-check-label" for="calcium2">
                                                                        Firm tofu, 100g
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-floating my-3 col">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="questions[8][options][Tuna, small tin, 90 g]" value="0" id="calciu3">
                                                                        <label class="form-check-label" for="calciu3">
                                                                        Tuna, small tin, 90 g  
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-floating my-3 col">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="questions[8][options][Almonds, 1/2 cup]" value="0" id="calcium4">
                                                                        <label class="form-check-label" for="calcium4">
                                                                        Almonds, 1/2 cup  
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-floating my-3 col">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="questions[8][options][Unsure]" value="0" id="calcium5">
                                                                        <label class="form-check-label" for="calcium5">
                                                                            Unsure
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Question 9 -->
                                            <div class="step-tab-box nutrition-form" id="div9">
                                                <div class="card">
                                                    <div class="card">
                                                        <div class="p-3 card-header bg-white">
                                                            <div class="d-flex align-items-center">
                                                                <h5 class="m-0">9. Which of these foods has the most fibre? </h5>
                                                                <input type="hidden" name="questions[9][form_slug]" value="nutrition" />
                                                                <input type="hidden" name="questions[9][question_index]" value="9" />
                                                                <input type="hidden" name="questions[9][question_text]" value="Which of these foods has the most fibre?" />
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <div class="row px-2 align-items-center">
                                                                <div class="form-floating my-3 col">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="questions[9][options][Banana, 1 large]" value="0" id="fibre1">
                                                                        <label class="form-check-label" for="fibre1">
                                                                        Banana, 1 large
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-floating my-3 col">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="questions[9][options][Raw oats, 1/2 cup]" value="1" id="fibre2">
                                                                        <label class="form-check-label" for="fibre2">
                                                                        Raw oats, 1/2 cup
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-floating my-3 col">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="questions[9][options][Cashews, 1 handful]" value="0" id="fibre3">
                                                                        <label class="form-check-label" for="fibre3">
                                                                        Cashews, 1 handful 
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-floating my-3 col">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="questions[9][options][Broccoli, 1/2 cup]" value="0" id="fibre4">
                                                                        <label class="form-check-label" for="fibre4">
                                                                        Broccoli, 1/2 cup 
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-floating my-3 col">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="questions[9][options][Unsure]" value="0" id="fibre5">
                                                                        <label class="form-check-label" for="fibre5">
                                                                            Unsure
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sports Section -->
                        <div class="accordion mb-4" id="sportsAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="sportsHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sportsCollapse" aria-expanded="false" aria-controls="sportsCollapse">
                                        Sports Questions (1-7)
                                    </button>
                                </h2>
                                <div id="sportsCollapse" class="accordion-collapse collapse" aria-labelledby="sportsHeading" data-bs-parent="#sportsAccordion">
                                    <div class="accordion-body">
                                        @if(isset($questions['sports']))
                                            @foreach($questions['sports'] as $question)
                                                <div class="step-tab-box sports-form" id="sports{{ $question->question_index }}">
                                                    <div class="card">
                                                        <div class="p-3 card-header bg-white">
                                                            <div class="d-flex align-items-center">
                                                                <h5 class="m-0">{{ $question->question_index }}. {{ $question->question_text }}</h5>
                                                                <span class="text-danger general-error-message"></span>
                                                            </div>
                                                            <input type="hidden" name="questions[{{ $question->question_index }}][form_slug]" value="sports" />
                                                            <input type="hidden" name="questions[{{ $question->question_index }}][question_index]" value="{{ $question->question_index }}" />
                                                            <input type="hidden" name="questions[{{ $question->question_index }}][question_text]" value="{{ $question->question_text }}" />
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <div class="row px-2">
                                                                <div class="col-md-6">
                                                                    <div class="form-floating my-3">
                                                                        @foreach($question->options as $option => $value)
                                                                            <div class="form-check my-2">
                                                                                <input class="form-check-input" type="radio" 
                                                                                    name="questions[{{ $question->question_index }}][options][{{ $option }}]" 
                                                                                    value="{{ $value }}" 
                                                                                    {{ isset($question->correct_answer[$option]) && $question->correct_answer[$option] == $value ? 'checked' : '' }}>
                                                                                <label class="form-check-label">{{ $option }}</label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <!-- Question 1 -->
                                            <div class="step-tab-box sports-form" id="sports1">
                                                <div class="card">
                                                    <div class="p-3 card-header bg-white">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="m-0">1. Compared to a non-athlete, how much total protein (per day) can an athlete need?</h5>
                                                            <span class="text-danger general-error-message"></span>
                                                        </div>
                                                        <input type="hidden" name="questions[1][form_slug]" value="sports" />
                                                        <input type="hidden" name="questions[1][question_index]" value="1" />
                                                        <input type="hidden" name="questions[1][question_text]" value="Compared to a non-athlete, how much total protein (per day) can an athlete need?" />
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="row px-2">
                                                            <div class="col-md-6">
                                                                <div class="form-floating my-3">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[1][options][A very similar amount]" value="0" id="post1">
                                                                        <label class="form-check-label" for="post1">A very similar amount</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[1][options][Up to 2 times (2x) more]" value="1" id="post2">
                                                                        <label class="form-check-label" for="post2">Up to 2 times (2x) more</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[1][options][3-4 times (3-4x) more]" value="0" id="post3">
                                                                        <label class="form-check-label" for="post3">3-4 times (3-4x) more</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-floating my-3">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[1][options][5 times (5x) more]" value="0" id="post4">
                                                                        <label class="form-check-label" for="post4">5 times (5x) more</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[1][options][Unsure]" value="0" id="post4">
                                                                        <label class="form-check-label" for="post4">Unsure</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Question 2 -->
                                            <div class="step-tab-box sports-form" id="sports2">
                                                <div class="card">
                                                    <div class="p-3 card-header bg-white">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="m-0">2. Which of the following are signs that you are not eating enough to meet your training needs?</h5>
                                                            <span class="text-danger general-error-message"></span>
                                                        </div>
                                                        <input type="hidden" name="questions[2][form_slug]" value="sports" />
                                                        <input type="hidden" name="questions[2][question_index]" value="2" />
                                                        <input type="hidden" name="questions[2][question_text]" value="Which of the following are signs that you are not eating enough to meet your training needs?" />
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="row px-2">
                                                            <div class="col-md-6">
                                                                <div class="form-floating my-3">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[2][options][Loss of appetite]" value="1" id="signs1">
                                                                        <label class="form-check-label" for="signs1">Loss of appetite</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[2][options][More injuries and/or illness]" value="1" id="signs2">
                                                                        <label class="form-check-label" for="signs2">More injuries and/or illness</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[2][options][Poor performance or recovery]" value="1" id="signs3">
                                                                        <label class="form-check-label" for="signs3">Poor performance or recovery</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-floating my-3">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[2][options][Weight loss]" value="1" id="signs4">
                                                                        <label class="form-check-label" for="signs4">Weight loss</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[2][options][Menstrual cycle changes (if not on the pill)]" value="1" id="signs5">
                                                                        <label class="form-check-label" for="signs5">Menstrual cycle changes (if not on the pill)</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[2][options][Unsure]" value="0" id="signs6">
                                                                        <label class="form-check-label" for="signs6">Unsure</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Question 3 -->
                                            <div class="step-tab-box sports-form" id="sports3">
                                                <div class="card">
                                                    <div class="p-3 card-header bg-white">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="m-0">3. On a heavy training day (training twice a day or high-intensity workouts) which foods should be increased?</h5>
                                                            <span class="text-danger general-error-message"></span>
                                                        </div>
                                                        <input type="hidden" name="questions[3][form_slug]" value="sports" />
                                                        <input type="hidden" name="questions[3][question_index]" value="3" />
                                                        <input type="hidden" name="questions[3][question_text]" value="On a heavy training day (training twice a day or high-intensity workouts) which foods should be increased?" />
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="row px-2">
                                                            <div class="col-md-6">
                                                                <div class="form-floating my-3">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[3][options][Protein-based foods like dairy, eggs, meat, tofu]" value="0" id="training1">
                                                                        <label class="form-check-label" for="training1">Protein-based foods like dairy, eggs, meat, tofu</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[3][options][Take away foods]" value="0" id="training2">
                                                                        <label class="form-check-label" for="training2">Take away foods</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[3][options][Lollies, chips and chocolate bars]" value="0" id="training3">
                                                                        <label class="form-check-label" for="training3">Lollies, chips and chocolate bars</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-floating my-3">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[3][options][Carbohydrate-based foods like rice, pasta, bread]" value="1" id="training4">
                                                                        <label class="form-check-label" for="training4">Carbohydrate-based foods like rice, pasta, bread</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[3][options][Fat-containing foods like avocado, nuts]" value="0" id="training5">
                                                                        <label class="form-check-label" for="training5">Fat-containing foods like avocado, nuts</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[3][options][Unsure]" value="0" id="training6">
                                                                        <label class="form-check-label" for="training6">Unsure</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Question 4 -->
                                            <div class="step-tab-box sports-form" id="sports4">
                                                <div class="card">
                                                    <div class="p-3 card-header bg-white">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="m-0">4. What is the most important role for 'Protein' in the body?</h5>
                                                            <span class="text-danger general-error-message"></span>
                                                        </div>
                                                        <input type="hidden" name="questions[4][form_slug]" value="sports" />
                                                        <input type="hidden" name="questions[4][question_index]" value="4" />
                                                        <input type="hidden" name="questions[4][question_text]" value="What is the most important role for 'Protein' in the body?" />
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="row px-2">
                                                            <div class="col-md-6">
                                                                <div class="form-floating my-3">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[4][options][Fuel for low to moderate intensity exercise]" value="0" id="protein1">
                                                                        <label class="form-check-label" for="protein1">Fuel for low to moderate intensity exercise</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[4][options][Fuel for moderate to high intensity exercise]" value="0" id="protein2">
                                                                        <label class="form-check-label" for="protein2">Fuel for moderate to high intensity exercise</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[4][options][Delivery of oxygen to muscles]" value="0" id="protein3">
                                                                        <label class="form-check-label" for="protein3">Delivery of oxygen to muscles</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[4][options][Muscle growth and repair]" value="1" id="protein4">
                                                                        <label class="form-check-label" for="protein4">Muscle growth and repair</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-floating my-3">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[4][options][A healthy digestive system]" value="0" id="protein5">
                                                                        <label class="form-check-label" for="protein5">A healthy digestive system</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[4][options][Strong bones]" value="0" id="protein6">
                                                                        <label class="form-check-label" for="protein6">Strong bones</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[4][options][Hydration]" value="0" id="protein7">
                                                                        <label class="form-check-label" for="protein7">Hydration</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[4][options][Unsure]" value="0" id="protein8">
                                                                        <label class="form-check-label" for="protein8">Unsure</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Question 5 -->
                                            <div class="step-tab-box sports-form" id="sports5">
                                                <div class="card">
                                                    <div class="p-3 card-header bg-white">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="m-0">5. Which of the following statements about the role of carbohydrates is NOT correct?</h5>
                                                            <span class="text-danger general-error-message"></span>
                                                        </div>
                                                        <input type="hidden" name="questions[5][form_slug]" value="sports" />
                                                        <input type="hidden" name="questions[5][question_index]" value="5" />
                                                        <input type="hidden" name="questions[5][question_text]" value="Which of the following statements about the role of carbohydrates is NOT correct?" />
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="row px-2">
                                                            <div class="col-md-6">
                                                                <div class="form-floating my-3">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[5][options][Support decision making]" value="-1" id="carb1">
                                                                        <label class="form-check-label" for="carb1">Support decision making</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[5][options][Helping maintain competition performance levels]" value="-1" id="carb2">
                                                                        <label class="form-check-label" for="carb2">Helping maintain competition performance levels</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[5][options][Assists fuelling and recovery from training sessions]" value="-1" id="carb3">
                                                                        <label class="form-check-label" for="carb3">Assists fuelling and recovery from training sessions</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-floating my-3">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[5][options][Major factor for gaining body fat]" value="1" id="carb4">
                                                                        <label class="form-check-label" for="carb4">Major factor for gaining body fat</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[5][options][Increases inflammation in the body]" value="1" id="carb5">
                                                                        <label class="form-check-label" for="carb5">Increases inflammation in the body</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[5][options][Unsure]" value="0" id="carb6">
                                                                        <label class="form-check-label" for="carb6">Unsure</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Question 6 -->
                                            <div class="step-tab-box sports-form" id="sports6">
                                                <div class="card">
                                                    <div class="p-3 card-header bg-white">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="m-0">6. What main fuels do muscles use during training?</h5>
                                                            <span class="text-danger general-error-message"></span>
                                                        </div>
                                                        <input type="hidden" name="questions[6][form_slug]" value="sports" />
                                                        <input type="hidden" name="questions[6][question_index]" value="6" />
                                                        <input type="hidden" name="questions[6][question_text]" value="What main fuels do muscles use during training?" />
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="row px-2">
                                                            <div class="col-md-6">
                                                                <div class="form-floating my-3">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[6][options][Protein]" value="-1" id="fuel1">
                                                                        <label class="form-check-label" for="fuel1">Protein</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[6][options][Carbs]" value="1" id="fuel2">
                                                                        <label class="form-check-label" for="fuel2">Carbs</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[6][options][Fat]" value="1" id="fuel3">
                                                                        <label class="form-check-label" for="fuel3">Fat</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-floating my-3">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[6][options][Iron]" value="-1" id="fuel4">
                                                                        <label class="form-check-label" for="fuel4">Iron</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[6][options][Water]" value="-1" id="fuel5">
                                                                        <label class="form-check-label" for="fuel5">Water</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[6][options][Unsure]" value="0" id="fuel6">
                                                                        <label class="form-check-label" for="fuel6">Unsure</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Question 7 -->
                                            <div class="step-tab-box sports-form" id="sports7">
                                                <div class="card">
                                                    <div class="p-3 card-header bg-white">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="m-0">7. Which statements about iron are correct?</h5>
                                                            <span class="text-danger general-error-message"></span>
                                                        </div>
                                                        <input type="hidden" name="questions[7][form_slug]" value="sports" />
                                                        <input type="hidden" name="questions[7][question_index]" value="7" />
                                                        <input type="hidden" name="questions[7][question_text]" value="Which statements about iron are correct?" />
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="row px-2">
                                                            <div class="col-md-6">
                                                                <div class="form-floating my-3">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[7][options][Females need over twice the amount of iron per day as men]" value="-1" id="iron1">
                                                                        <label class="form-check-label" for="iron1">Females need over twice the amount of iron per day as men</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[7][options][Vegetarian athletes are higher risk of low iron as plants less iron in the food and it's harder to absorb]" value="1" id="iron2">
                                                                        <label class="form-check-label" for="iron2">Vegetarian athletes are higher risk of low iron as plants less iron in the food and it's harder to absorb</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[7][options][Female athletes are higher risk of low iron due to losing extra iron through periods]" value="1" id="iron3">
                                                                        <label class="form-check-label" for="iron3">Female athletes are higher risk of low iron due to losing extra iron through periods</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-floating my-3">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[7][options][Iron deficiency improves over time as the athletete matures]" value="-1" id="iron4">
                                                                        <label class="form-check-label" for="iron4">Iron deficiency improves over time as the athletete matures</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[7][options][Unsure]" value="0" id="iron5">
                                                                        <label class="form-check-label" for="iron5">Unsure</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Supplements Section -->
                        <div class="accordion mb-4" id="supplementsAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="supplementsHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#supplementsCollapse" aria-expanded="false" aria-controls="supplementsCollapse">
                                        Supplements Questions (1-3)
                                    </button>
                                </h2>
                                <div id="supplementsCollapse" class="accordion-collapse collapse" aria-labelledby="supplementsHeading" data-bs-parent="#supplementsAccordion">
                                    <div class="accordion-body">
                                        @if(isset($questions['supplements']))
                                            @foreach($questions['supplements'] as $question)
                                                <div class="step-tab-box supplements-form" id="supplements{{ $question->question_index }}">
                                                    <div class="card">
                                                        <div class="p-3 card-header bg-white">
                                                            <div class="d-flex align-items-center">
                                                                <h5 class="m-0">{{ $question->question_index }}. {{ $question->question_text }}</h5>
                                                                <span class="text-danger general-error-message"></span>
                                                            </div>
                                                            <input type="hidden" name="questions[{{ $question->question_index }}][form_slug]" value="supplements" />
                                                            <input type="hidden" name="questions[{{ $question->question_index }}][question_index]" value="{{ $question->question_index }}" />
                                                            <input type="hidden" name="questions[{{ $question->question_index }}][question_text]" value="{{ $question->question_text }}" />
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <div class="row px-2">
                                                                <div class="col-md-6">
                                                                    <div class="form-floating my-3">
                                                                        @foreach($question->options as $option => $value)
                                                                            <div class="form-check my-2">
                                                                                <input class="form-check-input" type="checkbox" 
                                                                                    name="questions[{{ $question->question_index }}][options][{{ $option }}]" 
                                                                                    value="{{ $value }}" 
                                                                                    {{ isset($question->correct_answer[$option]) && $question->correct_answer[$option] == $value ? 'checked' : '' }}>
                                                                                <label class="form-check-label">{{ $option }}</label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <!-- Question 1 -->
                                            <div class="step-tab-box supplement-form" id="supplement1">
                                                <div class="card">
                                                    <div class="p-3 card-header bg-white">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="m-0">1. Which of the following statements about 'supplements' are true?</h5>
                                                            <span class="text-danger general-error-message"></span>
                                                        </div>
                                                        <input type="hidden" name="questions[1][form_slug]" value="supplements" />
                                                        <input type="hidden" name="questions[1][question_index]" value="1" />
                                                        <input type="hidden" name="questions[1][question_text]" value="Which of the following statements about 'supplements' are true?" />
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="row px-2">
                                                            <div class="col-md-6">
                                                                <div class="form-floating my-3">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[1][options][All athletes should use supplements to perform at their best]" value="-1" id="supplements1">
                                                                        <label class="form-check-label" for="supplements1">All athletes should use supplements to perform at their best</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[1][options][It is not possible to consume enough nutrients through eating food alone (without supplements)]" value="-1" id="supplements2">
                                                                        <label class="form-check-label" for="supplements2">It is not possible to consume enough nutrients through eating food alone (without supplements)</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[1][options][Athletes should check with a Sports Dietitian before taking supplements]" value="1" id="supplements3">
                                                                        <label class="form-check-label" for="supplements3">Athletes should check with a Sports Dietitian before taking supplements</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-floating my-3">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[1][options][Eating a wide range of foods provides most athletes with the vitamins and minerals they need]" value="1" id="supplements4">
                                                                        <label class="form-check-label" for="supplements4">Eating a wide range of foods provides most athletes with the vitamins and minerals they need</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[1][options][Most supplements available in Australia are safe for athletes to use]" value="-1" id="supplements5">
                                                                        <label class="form-check-label" for="supplements5">Most supplements available in Australia are safe for athletes to use</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[1][options][Unsure]" value="0" id="supplements6">
                                                                        <label class="form-check-label" for="supplements6">Unsure</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Question 2 -->
                                            <div class="step-tab-box supplement-form" id="supplement2">
                                                <div class="card">
                                                    <div class="p-3 card-header bg-white">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="m-0">2. When choosing a supplement, you should?</h5>
                                                            <span class="text-danger general-error-message"></span>
                                                        </div>
                                                        <input type="hidden" name="questions[2][form_slug]" value="supplements" />
                                                        <input type="hidden" name="questions[2][question_index]" value="2" />
                                                        <input type="hidden" name="questions[2][question_text]" value="When choosing a supplement, you should?" />
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="row px-2">
                                                            <div class="col-md-6">
                                                                <div class="form-floating my-3">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[2][options][Use supplements used by professional athletes]" value="-1" id="athletes1">
                                                                        <label class="form-check-label" for="athletes1">Use supplements used by professional athletes</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[2][options][Check with a mate for their opinion]" value="-1" id="athletes2">
                                                                        <label class="form-check-label" for="athletes2">Check with a mate for their opinion</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[2][options][Choose a product that has had third party batch testing]" value="1" id="athletes3">
                                                                        <label class="form-check-label" for="athletes3">Choose a product that has had third party batch testing</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-floating my-3">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[2][options][Check with a naturopath]" value="-1" id="athletes4">
                                                                        <label class="form-check-label" for="athletes4">Check with a naturopath</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[2][options][Ask staff at the local supplement store]" value="-1" id="athletes5">
                                                                        <label class="form-check-label" for="athletes5">Ask staff at the local supplement store</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="radio" name="questions[2][options][Unsure]" value="0" id="athletes6">
                                                                        <label class="form-check-label" for="athletes6">Unsure</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Question 3 -->
                                            <div class="step-tab-box supplement-form" id="supplement3">
                                                <div class="card">
                                                    <div class="p-3 card-header bg-white">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="m-0">3. Regarding vitamin and minerals supplements, which statements are true?</h5>
                                                            <span class="text-danger general-error-message"></span>
                                                        </div>
                                                        <input type="hidden" name="questions[3][form_slug]" value="supplements" />
                                                        <input type="hidden" name="questions[3][question_index]" value="3" />
                                                        <input type="hidden" name="questions[3][question_text]" value="Regarding vitamin and minerals supplements, which statements are true?" />
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="row px-2">
                                                            <div class="col-md-6">
                                                                <div class="form-floating my-3">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[3][options][They are safe for all athletes to use]" value="-1" id="vitamin1">
                                                                        <label class="form-check-label" for="vitamin1">They are safe for all athletes to use</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[3][options][Can assist athletes to correct a deficiency diagnosed by a Medical professional]" value="1" id="vitamin2">
                                                                        <label class="form-check-label" for="vitamin2">Can assist athletes to correct a deficiency diagnosed by a Medical professional</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[3][options][Vegetarians and vegans are not at risk of vitamin and mineral deficiences]" value="-1" id="vitamin3">
                                                                        <label class="form-check-label" for="vitamin3">Vegetarians and vegans are not at risk of vitamin and mineral deficiences</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-floating my-3">
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[3][options][May be recommended for international competition where food variety is limited]" value="1" id="vitamin4">
                                                                        <label class="form-check-label" for="vitamin4">May be recommended for international competition where food variety is limited</label>
                                                                    </div>
                                                                    <div class="form-check my-2">
                                                                        <input class="form-check-input" type="checkbox" name="questions[3][options][Unsure]" value="0" id="vitamin5">
                                                                        <label class="form-check-label" for="vitamin5">Unsure</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Save Quiz</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#quizForm').on('submit', function(e) {
            e.preventDefault();
            
            // Collect form data
            const formData = {
                questions: []
            };

            // Helper function to safely extract option name
            function getOptionName(name) {
                const match = name.match(/questions\[\d+\]\[options\]\[(.*?)\]/);
                return match ? match[1] : null;
            }

            // Helper function to check if question is high/low format
            function isHighLowQuestion(questionText) {
                return questionText.toLowerCase().includes('high or low');
            }

            // Helper function to check if it's one of the first 4 nutrition questions
            function isFirstFourNutritionQuestions(questionIndex, formSlug) {
                return formSlug === 'nutrition' && parseInt(questionIndex) <= 4;
            }

            // Debug: Log all step-tab-box elements
            console.log('Found step-tab-box elements:', $('.step-tab-box').length);

            // Collect questions
            $('.step-tab-box').each(function(index) {
                const questionDiv = $(this);
                
                // Debug: Log current question div
                console.log('Processing question div:', index + 1, questionDiv);

                // Get question data from hidden inputs
                const formSlugInput = questionDiv.find('input[name^="questions"][name$="[form_slug]"]');
                const questionIndexInput = questionDiv.find('input[name^="questions"][name$="[question_index]"]');
                const questionTextInput = questionDiv.find('input[name^="questions"][name$="[question_text]"]');

                // Debug: Log found inputs
                console.log('Found inputs:', {
                    formSlug: formSlugInput.length,
                    questionIndex: questionIndexInput.length,
                    questionText: questionTextInput.length
                });

                if (!formSlugInput.length || !questionIndexInput.length || !questionTextInput.length) {
                    console.log('Skipping question - missing required inputs');
                    return;
                }

                const formSlug = formSlugInput.val();
                const questionIndex = questionIndexInput.val();
                const isFirstFour = isFirstFourNutritionQuestions(questionIndex, formSlug);

                const question = {
                    form_slug: formSlug,
                    question_index: questionIndex,
                    question_text: questionTextInput.val(),
                    options: {},
                    correct_answer: {}
                };

                // Debug: Log question data
                console.log('Question data:', question);

                if (isFirstFour) {
                    // Special handling for first 4 nutrition questions
                    const table = questionDiv.find('table');
                    table.find('tr').each(function() {
                        const row = $(this);
                        const foodName = row.find('td:first').text().trim();
                        if (!foodName) return;

                        // Get the radio buttons for this food
                        const highRadio = row.find('td:nth-child(2) input[type="radio"]');
                        const lowRadio = row.find('td:nth-child(3) input[type="radio"]');
                        const unsureRadio = row.find('td:nth-child(4) input[type="radio"]');

                        // Store the actual values from the radio buttons
                        question.options[foodName] = {
                            High: highRadio.val(),
                            Low: lowRadio.val(),
                            Unsure: unsureRadio.val()
                        };

                        // Store the correct answer with its value
                        if (highRadio.is(':checked')) {
                            question.correct_answer[foodName] = {
                                option: 'High',
                                value: highRadio.val()
                            };
                        } else if (lowRadio.is(':checked')) {
                            question.correct_answer[foodName] = {
                                option: 'Low',
                                value: lowRadio.val()
                            };
                        } else if (unsureRadio.is(':checked')) {
                            question.correct_answer[foodName] = {
                                option: 'Unsure',
                                value: unsureRadio.val()
                            };
                        }
                    });
                } else {
                    // Handle other questions as before
                    const isHighLow = isHighLowQuestion(question.question_text);

                    // Handle radio buttons
                    questionDiv.find('input[type="radio"]').each(function() {
                        const radio = $(this);
                        const name = radio.attr('name');
                        if (!name) return;

                        const optionName = getOptionName(name);
                        if (!optionName) return;

                        const value = radio.val();
                        question.options[optionName] = value;
                        
                        if (radio.is(':checked')) {
                            if (isHighLow) {
                                if (value === '1') {
                                    question.correct_answer[optionName] = value;
                                }
                            } else {
                                question.correct_answer[optionName] = value;
                            }
                        }
                    });

                    // Handle checkboxes
                    questionDiv.find('input[type="checkbox"]').each(function() {
                        const checkbox = $(this);
                        const name = checkbox.attr('name');
                        if (!name) return;

                        const optionName = getOptionName(name);
                        if (!optionName) return;

                        const value = checkbox.val();
                        question.options[optionName] = value;
                        
                        if (checkbox.is(':checked')) {
                            if (isHighLow) {
                                if (value === '1') {
                                    question.correct_answer[optionName] = value;
                                }
                            } else {
                                question.correct_answer[optionName] = value;
                            }
                        }
                    });
                }

                // Debug: Log collected options
                console.log('Collected options:', question.options);
                console.log('Collected correct answers:', question.correct_answer);

                // Only add question if it has options
                if (Object.keys(question.options).length > 0) {
                    formData.questions.push(question);
                    console.log('Added question to formData');
                } else {
                    console.log('Skipping question - no options collected');
                }
            });

            // Sort questions by index
            formData.questions.sort((a, b) => parseInt(a.question_index) - parseInt(b.question_index));

            // Debug: Log final form data
            console.log('Final Form Data:', formData);
            console.log('Number of questions collected:', formData.questions.length);

            // Setup CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            // Submit the form data
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                success: function(response) {
                    alert('Quiz saved successfully!');
                    location.reload(); // Reload to show updated data
                },
                error: function(xhr) {
                    console.error('Error saving quiz:', xhr.responseText);
                    alert('Error saving quiz! Please check the console for details.');
                }
            });
        });
    });
</script>
@endpush
@endsection