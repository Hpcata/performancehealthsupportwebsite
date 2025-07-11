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
                    <div class="tab-main-box">
                        <div class="step-tab-box nutrition-form" id="div1" style="display: block;">
                            <div class="card">
                                <div class="p-3 card-header bg-white">
                                <div class="d-flex align-items-center">
                                    <h5 class="m-0">1. Do you think these foods are <strong class="text-primary">high</strong> or <strong class="text-primary">low</strong> in <strong class="text-primary">carbohydrate</strong>? </h5>
                                    <span class="ms-2 general-error-message text-danger"> </span>
                                </div>
                                    <input type="hidden" name="questions[nutrition-Q-1]" value="Do you think these foods are high or low in carbohydrate?" />
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
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][chicken]" value="0" id="Chicken-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][chicken]" value="1" id="Chicken-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][chicken]" value="0" id="Chicken-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Baked beans</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][baked_beans]" value="1" id="Bakedbeans-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][baked_beans]" value="0" id="Bakedbeans-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][baked_beans]" value="0" id="Bakedbeans-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Grain bread</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][grain_bread]" value="1" id="GrainBread-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][grain_bread]" value="0" id="GrainBread-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][grain_bread]" value="0" id="GrainBread-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Avocado</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][avocado]" value="0" id="Avocado-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][avocado]" value="1" id="Avocado-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][avocado]" value="0" id="Avocado-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Weet-bix</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][weet_bix]" value="1" id="Weet-bix-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][weet_bix]" value="0" id="Weet-bix-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][weet_bix]" value="0" id="Weet-bix-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Fruit yoghurt</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][fruit_yoghurt]" value="1" id="FruitYoghurt-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][fruit_yoghurt]" value="0" id="FruitYoghurt-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][fruit_yoghurt]" value="0" id="FruitYoghurt-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Crumpets</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][crumpets]" value="1" id="Crumpets-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][crumpets]" value="0" id="Crumpets-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][crumpets]" value="0" id="Crumpets-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Cream</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][cream]" value="0" id="Cream-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][cream]" value="1" id="Cream-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-1][cream]" value="0" id="Cream-3"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step-tab-box nutrition-form" id="div2" style="display: block;">
                            <div class="card">
                                <div class="p-3 card-header bg-white">
                                <div class="d-flex align-items-center">
                                    <h5 class="m-0">2. Do you think these foods are <strong class="text-primary">high</strong> or <strong class="text-primary">low</strong> in <strong class="text-primary">protein</strong>?
                                    <span class="ms-2 general-error-message text-danger"> </span>
                                </div>
                                    <input type="hidden" name="questions[nutrition-Q-2]" value="Do you think these foods are high or low in protein?" />
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
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][salmon]" value="1" id="Salmon-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][salmon]" value="0" id="Salmon-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][salmon]" value="0" id="Salmon-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Baked beans</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][baked_beans]" value="1" id="Bakedbeans-11"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][baked_beans]" value="0" id="Bakedbeans-12"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][baked_beans]" value="0" id="Bakedbeans-13"></td>
                                                </tr>
                                                <tr>
                                                    <td>Fruit</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][fruit]" value="0" id="Fruit-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][fruit]" value="1" id="Fruit-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][fruit]" value="0" id="Fruit-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Hummus</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][hummus]" value="0" id="Hummus-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][hummus]" value="1" id="Hummus-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][hummus]" value="0" id="Hummus-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Cornflakes cereal</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][cornflakes_cereal]" value="0" id="CornflakesCereal-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][cornflakes_cereal]" value="1" id="CornflakesCereal-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][cornflakes_cereal]" value="0" id="CornflakesCereal-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Almonds</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][almonds]" value="1" id="Almonds-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][almonds]" value="0" id="Almonds-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][almonds]" value="0" id="Almonds-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Flavoured milk</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][flavoured_milk]" value="1" id="FlavouredMilk-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][flavoured_milk]" value="0" id="FlavouredMilk-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][flavoured_milk]" value="0" id="FlavouredMilk-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Ice cream</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][ice_cream]" value="0" id="IceCream-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][ice_cream]" value="1" id="IceCream-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][ice_cream]" value="0" id="IceCream-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Almond/oat milk</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][almond_oat_milk]" value="0" id="Almond-oat-milk-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][almond_oat_milk]" value="1" id="Almond-oat-milk-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-2][almond_oat_milk]" value="0" id="Almond-oat-milk-3"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step-tab-box nutrition-form" id="div3" style="display: block;">
                            <div class="card">
                                <div class="p-3 card-header bg-white">
                                <div class="d-flex align-items-center">
                                    <h5 class="m-0">3. Do you think these foods are <strong class="text-primary">high</strong> or <strong class="text-primary">low</strong> in <strong class="text-primary">fat</strong>? 
                                    <span class="ms-2 general-error-message text-danger"> </span>
                                </div>
                                    <input type="hidden" name="questions[nutrition-Q-3]" value="Do you think these foods are high or low in fat?" />
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
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-3][avocado]" value="1" id="Avocado-11"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-3][avocado]" value="0" id="Avocado-12"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-3][avocado]" value="0" id="Avocado-13"></td>
                                                </tr>
                                                <tr>
                                                    <td>Baked beans</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-3][backed_beans]" value="0" id="BakedBeans-21"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-3][backed_beans]" value="1" id="BakedBeans-22"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-3][backed_beans]" value="0" id="BakedBeans-23"></td>
                                                </tr>
                                                <tr>
                                                    <td>Cottage cheese</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-3][cottage_cheese]" value="0" id="CottageCheese-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-3][cottage_cheese]" value="1" id="CottageCheese-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-3][cottage_cheese]" value="0" id="CottageCheese-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Peanut butter</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-3][peanut_butter]" value="1" id="PeanutButter-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-3][peanut_butter]" value="0" id="PeanutButter-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-3][peanut_butter]" value="0" id="PeanutButter-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Crumpets</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-3][crumpets]" value="0" id="Crumpets-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-3][crumpets]" value="1" id="Crumpets-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-3][crumpets]" value="0" id="Crumpets-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Cheddar/Tatsy cheese</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-3][cheddar_tatsy_cheese]" value="1" id="CheddarTatsyCheese-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-3][cheddar_tatsy_cheese]" value="0" id="CheddarTatsyCheese-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-3][cheddar_tatsy_cheese]" value="0" id="CheddarTatsyCheese-3"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step-tab-box nutrition-form" id="div4" style="display: block;">
                            <div class="card">
                                <div class="p-3 card-header bg-white">
                                <div class="d-flex align-items-center">
                                    <h5 class="m-0">4. Do you think these foods are <strong class="text-primary">high</strong> or <strong class="text-primary">low</strong> in <strong class="text-primary">healthy fats</strong>?</h5>
                                    <span class="ms-2 general-error-message text-danger"> </span>
                                </div>
                                    <input type="hidden" name="questions[nutrition-Q-4]" value="Do you think these foods are high or low in healthy fat?" />
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
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][butter]" value="0" id="Butter-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][butter]" value="1" id="Butter-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][butter]" value="0" id="Butter-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Extra virgin olive oil</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][extra_virgin_olive_oil]" value="1" id="OliveOil-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][extra_virgin_olive_oil]" value="0" id="OliveOil-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][extra_virgin_olive_oil]" value="0" id="OliveOil-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Whole milk</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][whole_milk]" value="0" id="WholeMilk-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][whole_milk]" value="1" id="WholeMilk-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][whole_milk]" value="0" id="WholeMilk-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Potato crisps</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][potato_crisps]" value="0" id="PotatoCrisps-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][potato_crisps]" value="1" id="PotatoCrisps-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][potato_crisps]" value="0" id="PotatoCrisps-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Salmon</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][salmon]" value="1" id="Salmon-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][salmon]" value="0" id="Salmon-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][salmon]" value="0" id="Salmon-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Dark chocolate</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][dark_chocolate]" value="0" id="DarkChocolate-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][dark_chocolate]" value="1" id="DarkChocolate-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][dark_chocolate]" value="0" id="DarkChocolate-3"></td>
                                                </tr>
                                                <tr>
                                                    <td>Macadamia nuts</td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][macadamia_nuts]" value="1" id="MacadamiaNuts-1"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][macadamia_nuts]" value="0" id="MacadamiaNuts-2"></td>
                                                    <td class="text-center"><input class="form-check-input" type="radio" name="ans[nutrition-Q-4][macadamia_nuts]" value="0" id="MacadamiaNuts-3"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step-tab-box nutrition-form" id="div5" style="display: block;">
                            <div class="card">
                                <div class="card">
                                    <div class="p-3 card-header bg-white">
                                    <div class="d-flex align-items-center">
                                        <h5 class="m-0">5. Which of these foods has the most iron?</h5>
                                        <span class="ms-2 general-error-message text-danger"> </span>
                                        </div>
                                        <input type="hidden" name="questions[nutrition-Q-5]" value="Which of these foods has the most iron?" />
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row px-2">
                                            <!-- First Row -->
                                            <div class="col-md-4">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-5][food_most_iron]" value="0" id="food1">
                                                    <label class="form-check-label" for="food1">
                                                        Spinach, cooked, 1/2 cup
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-5][food_most_iron]" value="0" id="food2">
                                                    <label class="form-check-label" for="food2">
                                                        Brown rice, cooked, 1 cup
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-5][food_most_iron]" value="1" id="food3">
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
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-5][food_most_iron]" value="0" id="food4">
                                                    <label class="form-check-label" for="food4">
                                                        Tuna, small tin, 90g
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-5][food_most_iron]" value="0" id="food5">
                                                    <label class="form-check-label" for="food5">
                                                        Almonds/cashews, ~30 nuts
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-5][food_most_iron]" value="0" id="food6">
                                                    <label class="form-check-label" for="food6">
                                                        Unsure
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 card-header bg-white">
                                        <h5 class="m-0">6. Approximately how many decisions do we make every day about what we eat? </h5>
                                        <input type="hidden" name="questions[nutrition-Q-6]" value="Approximately how many decisions do we make every day about what we eat?" />
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row px-2 align-items-center">
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-6][every_day_decisions_eat]" value="0" id="decision1">
                                                    <label class="form-check-label" for="decision1">
                                                    50-100
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-6][every_day_decisions_eat]" value="0" id="decision2">
                                                    <label class="form-check-label" for="decision2">
                                                    100-150
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-6][every_day_decisions_eat]" value="0" id="decision3">
                                                    <label class="form-check-label" for="decision3">
                                                    150-200
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-6][every_day_decisions_eat]" value="1" id="decision4">
                                                    <label class="form-check-label" for="decision4">
                                                    Over 200
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-6][every_day_decisions_eat]" value="0" id="decision5">
                                                    <label class="form-check-label" for="decision5">
                                                        Unsure
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 card-header bg-white">
                                        <h5 class="m-0">7. Which of the following is NOT a 'Macronutrient'? </h5>
                                        
                                        <input type="hidden" name="questions[nutrition-Q-7]" value="Which of the following is NOT a 'Macronutrient'?" />
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row px-2 align-items-center">
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-7][macronutrient]" value="1" id="Macronutrien1">
                                                    <label class="form-check-label" for="Macronutrien1">
                                                    Iron 
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-7][macronutrient]" value="0" id="Macronutrien2">
                                                    <label class="form-check-label" for="Macronutrien2">
                                                    Carbohydrate
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-7][macronutrient]" value="0" id="Macronutrien3">
                                                    <label class="form-check-label" for="Macronutrien3">
                                                    Protein 
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-7][macronutrient]" value="0" id="Macronutrien4">
                                                    <label class="form-check-label" for="Macronutrien4">
                                                    Alcohol 
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-7][macronutrient]" value="0" id="Macronutrien5">
                                                    <label class="form-check-label" for="Macronutrien5">
                                                    Fat 
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-7][macronutrient]" value="0" id="Macronutrien6">
                                                    <label class="form-check-label" for="Macronutrien6">
                                                        Unsure
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 card-header bg-white">
                                        <h5 class="m-0">8. Which of these foods has the most calcium? </h5>
                                        <input type="hidden" name="questions[nutrition-Q-8]" value="Which of these foods has the most calcium?" />
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row px-2 align-items-center">
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-8][most_calcium]" value="0" id="calciu1">
                                                    <label class="form-check-label" for="calciu1">
                                                    Baby spinach, 1 cup
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-8][most_calcium]" value="1" id="calcium2">
                                                    <label class="form-check-label" for="calcium2">
                                                    Firm tofu, 100g
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-8][most_calcium]" value="0" id="calciu3">
                                                    <label class="form-check-label" for="calciu3">
                                                    Tuna, small tin, 90 g  
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-8][most_calcium]" value="0" id="calcium4">
                                                    <label class="form-check-label" for="calcium4">
                                                    Almonds, 1/2 cup  
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-8][most_calcium]" value="0" id="calcium5">
                                                    <label class="form-check-label" for="calcium5">
                                                        Unsure
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 card-header bg-white">
                                        <h5 class="m-0">9. Which of these foods has the most fibre? </h5>
                                        <input type="hidden" name="questions[nutrition-Q-9]" value="Which of these foods has the most fibre?" />
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row px-2 align-items-center">
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-9][most_fibre]" value="0" id="fibre1">
                                                    <label class="form-check-label" for="fibre1">
                                                    Banana, 1 large
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-9][most_fibre]" value="1" id="fibre2">
                                                    <label class="form-check-label" for="fibre2">
                                                    Raw oats, 1/2 cup
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-9][most_fibre]" value="0" id="fibre3">
                                                    <label class="form-check-label" for="fibre3">
                                                    Cashews, 1 handful 
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-9][most_fibre]" value="0" id="fibre4">
                                                    <label class="form-check-label" for="fibre4">
                                                    Broccoli, 1/2 cup 
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-floating my-3 col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ans[nutrition-Q-9][most_fibre]" value="0" id="fibre5">
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
                        <div class="step-tab-box sports-form" id="div6" style="display: block;">
                            <div class="card">
                                <div class="p-3 card-header bg-white">
                                    <div class="d-flex align-items-center">
                                        <h5 class="m-0">1. Compared to a non-athlete, how much total protein (per day) can an athlete need?</h5>
                                        <span class="text-danger general-error-message"></span>
                                    </div>
                                    <input type="hidden" name="questions[sports-nutrition-Q-1]" value="Compared to a non-athlete, how much total protein (per day) can an athlete need?" />
                                </div>
                                <div class="card-body p-0">
                                    <div class="row px-2">
                                        <div class="col-md-6">
                                            <!-- Left Column -->
                                            <div class="form-floating my-3">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-1]" value="0" id="protein1">
                                                    <label class="form-check-label" for="protein1">
                                                        A very similar amount
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-1]" value="1" id="protein2">
                                                    <label class="form-check-label" for="protein2">
                                                        Up to 2 times (2x) more
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-1]" value="0" id="protein3">
                                                    <label class="form-check-label" for="protein3">
                                                        3-4 times (3-4x) more
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Right Column -->
                                            <div class="form-floating my-3">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-1]" value="0" id="protein4">
                                                    <label class="form-check-label" for="protein4">
                                                        5 times (5x) more
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-1]" value="0" id="protein5">
                                                    <label class="form-check-label" for="protein5">
                                                        Unsure
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 card-header bg-white">
                                        <h5 class="m-0">2. Which of the following are signs that you are not eating enough to meet your training needs? </h5>
                                        <input type="hidden" name="questions[sports-nutrition-Q-2]" value="Which of the following are signs that you are not eating enough to meet your training needs?" />
                                    </div>
                                    <div class="row px-2">
                                        <!-- Left Column -->
                                        <div class="col-md-6">
                                            <div class="form-floating my-3">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="checkbox" value="1" name="ans[sports-nutrition-Q-2][]" id="diagnosed1">
                                                    <label class="form-check-label" for="diagnosed1">
                                                        Loss of appetite
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="checkbox" value="1" name="ans[sports-nutrition-Q-2][]" id="diagnosed2">
                                                    <label class="form-check-label" for="diagnosed2">
                                                        More injuries and/or illness
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="checkbox" value="1" name="ans[sports-nutrition-Q-2][]" id="diagnosed3">
                                                    <label class="form-check-label" for="diagnosed3">
                                                        Poor performance or recovery
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Right Column -->
                                        <div class="col-md-6">
                                            <div class="form-floating my-3">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="checkbox" value="1" name="ans[sports-nutrition-Q-2][]" id="diagnosed4">
                                                    <label class="form-check-label" for="diagnosed4">
                                                        Weight loss
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="checkbox" value="1" name="ans[sports-nutrition-Q-2][]" id="diagnosed5">
                                                    <label class="form-check-label" for="diagnosed5">
                                                        Menstrual cycle changes (if not on the pill)
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="checkbox" value="0" name="ans[sports-nutrition-Q-2][]" id="diagnosed6">
                                                    <label class="form-check-label" for="diagnosed6">
                                                        Unsure
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 card-header bg-white">
                                        <h5 class="m-0">3. On a heavy training day (training twice a day or high-intensity workouts) which foods should be increased? </h5>
                                        <input type="hidden" name="questions[sports-nutrition-Q-3]" value="On a heavy training day (training twice a day or high-intensity workouts) which foods should be increased?" />
                                    </div>
                                    <div class="row px-2">
                                        <!-- Left Column -->
                                        <div class="col-md-6">
                                            <div class="form-floating my-3">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-3]" value="0" id="bloodTest1">
                                                    <label class="form-check-label" for="bloodTest1">
                                                        Protein-based foods like dairy, eggs, meat, tofu
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-3]" value="0" id="bloodTest2">
                                                    <label class="form-check-label" for="bloodTest2">
                                                        Take away foods
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-3]" value="0" id="bloodTest3">
                                                    <label class="form-check-label" for="bloodTest3">
                                                        Lollies, chips and chocolate bars
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Right Column -->
                                        <div class="col-md-6">
                                            <div class="form-floating my-3">
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-3]" value="1" id="bloodTest4">
                                                    <label class="form-check-label" for="bloodTest4">
                                                        Carbohydrate-based foods like rice, pasta, bread
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-3]" value="0" id="bloodTest5">
                                                    <label class="form-check-label" for="bloodTest5">
                                                        Fat-containing foods like avocado, nuts
                                                    </label>
                                                </div>
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-3]" value="0" id="bloodTest6">
                                                    <label class="form-check-label" for="bloodTest6">
                                                        Unsure
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step-tab-box sports-form" id="div7" style="display: block;">
                            <div class="card">
                                <div class="card">
                                    <div class="p-3 card-header bg-white">
                                        <div class="d-flex align-items-center">
                                            <h5 class="m-0">4. What is the most important role for 'Protein' in the body?</h5>
                                            <span class="text-danger general-error-message"></span>
                                        </div>
                                        <input type="hidden" name="questions[sports-nutrition-Q-4]" value="What is the most important role for 'Protein' in the body?" />
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row px-2">
                                            <!-- Left Column -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-4]" value="0" id="bodyTest1">
                                                        <label class="form-check-label" for="bodyTest1">
                                                            Fuel for low to moderate intensity exercise
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-4]" value="0" id="bodyTest2">
                                                        <label class="form-check-label" for="bodyTest2">
                                                            Fuel for moderate to high intensity exercise
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-4]" value="0" id="bodyTest3">
                                                        <label class="form-check-label" for="bodyTest3">
                                                            Delivery of oxygen to muscles
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-4]" value="1" id="bodyTest4">
                                                        <label class="form-check-label" for="bodyTest4">
                                                            Muscle growth and repair
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Right Column -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-4]" value="0" id="bodyTest5">
                                                        <label class="form-check-label" for="bodyTest5">
                                                            A healthy digestive system
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-4]" value="0" id="bodyTest6">
                                                        <label class="form-check-label" for="bodyTest6">
                                                            Strong bones
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-4]" value="0" id="bodyTest7">
                                                        <label class="form-check-label" for="bodyTest7">
                                                            Hydration
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[sports-nutrition-Q-4]" value="0" id="bodyTest8">
                                                        <label class="form-check-label" for="bodyTest8">
                                                            Unsure
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-3 card-header bg-white">
                                            <h5 class="m-0">5. Which of the following statements about the role of carbohydrates is NOT correct?</h5>
                                            <input type="hidden" name="questions[sports-nutrition-Q-5]" value="Which of the following statements about the role of carbohydrates is NOT correct?" />
                                        </div>
                                        <div class="row px-2">
                                            <!-- Left Column -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-5][]" value="-1" id="carbTest1">
                                                        <label class="form-check-label" for="carbTest1">
                                                            Support decision making
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-5][]" value="-1" id="carbTest2">
                                                        <label class="form-check-label" for="carbTest2">
                                                            Helping maintain competition performance levels
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-5][]" value="-1" id="carbTest3">
                                                        <label class="form-check-label" for="carbTest3">
                                                        Assists fuelling and recovery from training sessions 
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Right Column -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-5][]" value="1" id="carbTest4">
                                                        <label class="form-check-label" for="carbTest4">
                                                        Major factor for gaining body fat
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-5][]" value="1" id="carbTest5">
                                                        <label class="form-check-label" for="carbTest5">
                                                        Increases inflammation in the body
                                                        </label>
                                                    </div>

                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-5][]" value="0" id="carbTest6">
                                                        <label class="form-check-label" for="carbTest6">
                                                            Unsure
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-3 card-header bg-white">
                                            <h5 class="m-0">6. What main fuels do muscles use during training?</h5>
                                            <input type="hidden" name="questions[sports-nutrition-Q-6]" value="What main fuels do muscles use during training?" />
                                        </div>
                                        <div class="row px-2">
                                            <!-- Left Column -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-6][]" value="-1" id="training1">
                                                        <label class="form-check-label" for="training1">
                                                        Protein
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-6][]" value="1" id="training2">
                                                        <label class="form-check-label" for="training2">
                                                        Carbs
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-6][]" value="1" id="training3">
                                                        <label class="form-check-label" for="training3">
                                                        Fat
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Right Column -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-6][]" value="-1" id="training4">
                                                        <label class="form-check-label" for="training4">
                                                        Iron
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-6][]" value="-1" id="training5">
                                                        <label class="form-check-label" for="training5">
                                                        Water
                                                        </label>
                                                    </div>

                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-6][]" value="0" id="training6">
                                                        <label class="form-check-label" for="training6">
                                                            Unsure
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-3 card-header bg-white">
                                            <h5 class="m-0">7. Which statements about iron are correct? </h5>
                                            <input type="hidden" name="questions[sports-nutrition-Q-7]" value="Which statements about iron are correct?" />
                                        </div>
                                        <div class="row px-2">
                                            <!-- Left Column -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-7][]" value="1" id="statement1">
                                                        <label class="form-check-label" for="statement1">
                                                        Females need over twice the amount of iron per day as men
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-7][]" value="1" id="statement2">
                                                        <label class="form-check-label" for="statement2">
                                                        Vegetarian athletes are higher risk of low iron as plants less iron in the food and it's harder to absorb
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-7][]" value="1" id="statement3">
                                                        <label class="form-check-label" for="statement3">
                                                        Female athletes are higher risk of low iron due to losing extra iron through periods
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Right Column -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-7][]" value="-1" id="statement4">
                                                        <label class="form-check-label" for="statement4">
                                                        Iron deficiency improves over time as the athletete matures
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[sports-nutrition-Q-7][]" value="0" id="statement5">
                                                        <label class="form-check-label" for="statement5">
                                                            Unsure
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="step-tab-box supplement-form" id="div8" style="display: block;">
                            <div class="card">
                                <div class="card">
                                    <div class="p-3 card-header bg-white">
                                        <div class="d-flex align-items-center">
                                            <h5 class="m-0">1. Which of the following statements about 'supplements' are true?  </h5>
                                            <span class="text-danger general-error-message"></span>
                                        </div>
                                        <input type="hidden" name="questions[supplements-Q-1]" value="Which of the following statements about 'supplements' are true?" />
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row px-2">
                                            <!-- Left Column -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" value="-1" name="ans[supplements-Q-1][]" id="supplements1">
                                                        <label class="form-check-label" for="supplements1">
                                                        All athletes should use supplements to perform at their best 
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" value="-1" name="ans[supplements-Q-1][]" id="supplements2">
                                                        <label class="form-check-label" for="supplements2">
                                                        It is not possible to consume enough nutrients through eating food alone (without supplements)
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" value="1" name="ans[supplements-Q-1][]" id="supplements3">
                                                        <label class="form-check-label" for="supplements3">
                                                        Athletes should check with a Sports Dietitian before taking supplements                                                
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Right Column -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" value="1" name="ans[supplements-Q-1][]" id="supplements4">
                                                        <label class="form-check-label" for="supplements4">
                                                        Eating a wide range of foods provides most athletes with the vitamins and minerals they need 
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" value="-1" name="ans[supplements-Q-1][]" id="supplements5">
                                                        <label class="form-check-label" for="supplements5">
                                                        Most supplements available in Australia are safe for athletes to use 
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" value="0" name="ans[supplements-Q-1][]" id="supplements6">
                                                        <label class="form-check-label" for="supplements6">
                                                            Unsure
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-3 card-header bg-white">
                                            <h5 class="m-0">2. When choosing a supplement, you should? </h5>
                                            <input type="hidden" name="questions[supplements-Q-2]" value="When choosing a supplement, you should?" />
                                        </div>
                                        <div class="row px-2">
                                            <!-- Left Column -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[supplements-Q-2]" value="-1" id="athletes1">
                                                        <label class="form-check-label" for="athletes1">
                                                        Use supplements used by professional athletes
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[supplements-Q-2]" value="-1" id="athletes2">
                                                        <label class="form-check-label" for="athletes2">
                                                        Check with a mate for their opinion                                                     </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[supplements-Q-2]" value="1" id="athletes3">
                                                        <label class="form-check-label" for="athletes3">
                                                        Choose a product that has had third party batch testing                                                     
                                                    </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Right Column -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[supplements-Q-2]" value="-1" id="athletes4">
                                                        <label class="form-check-label" for="athletes4">
                                                        Check with a naturopath 
                                                        </label>
                                                    </div>
                                                    <!-- <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[supplements-Q-2]" value="0.5" id="carbTest4">
                                                        <label class="form-check-label" for="carbTest4">
                                                        Check with a Sports Dietitian 
                                                        </label>
                                                    </div> -->
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[supplements-Q-2]" value="-1" id="athletes5">
                                                        <label class="form-check-label" for="athletes5">
                                                        Ask staff at the local supplement store 
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="radio" name="ans[supplements-Q-2]" value="0" id="athletes6">
                                                        <label class="form-check-label" for="athletes6">
                                                        Unsure 
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-3 card-header bg-white">
                                            <h5 class="m-0">3. Regarding vitamin and minerals supplements, which statements are true? </h5>
                                            <input type="hidden" name="questions[supplements-Q-3]" value="Regarding vitamin and minerals supplements, which statements are true?" />
                                        </div>
                                        <div class="row px-2">
                                            <!-- Left Column -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[supplements-Q-3][]" value="-1" id="vitamin1">
                                                        <label class="form-check-label" for="vitamin1">
                                                            They are safe for all athletes to use 
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[supplements-Q-3][]" value="1" id="vitamin2">
                                                        <label class="form-check-label" for="vitamin2">
                                                        Can assist athletes to correct a deficiency diagnosed by a Medical professional
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[supplements-Q-3][]" value="-1" id="vitamin3">
                                                        <label class="form-check-label" for="vitamin3">
                                                        Vegetarians and vegans are not at risk of vitamin and mineral deficiences                                                    
                                                    </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Right Column -->
                                            <div class="col-md-6">
                                                <div class="form-floating my-3">
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[supplements-Q-3][]" value="1" id="vitamin4">
                                                        <label class="form-check-label" for="vitamin4">
                                                        May be recommended for international competition where food variety is limited 
                                                        </label>
                                                    </div>
                                                    <div class="form-check my-2">
                                                        <input class="form-check-input" type="checkbox" name="ans[supplements-Q-3][]" value="0" id="vitamin5">
                                                        <label class="form-check-label" for="vitamin5">
                                                        Unsure 
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection