@extends('backend.layouts.app')

@section('content')
<style>
    #loader {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        background: rgba(255, 255, 255, 0.8);
        padding: 20px;
        border-radius: 10px;
        display: none;
    }
    #loader img {
        width: 50px; /* Adjust size */
        height: 50px;
    }
</style>
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
                <h3 class="fw-bold mb-0">{{ isset($meal) ? 'Edit Meal' : 'Create Meal' }}</h3>
              
                    <a type="button" href="{{ route('admin.meals.index') }}" class="btn btn-primary btn-set-task">Back</a>
              
            </div>
        </div>
    </div>
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                <form action="{{ route('admin.meals.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" required class="form-control mb-3">
                    <button type="submit" class="btn btn-primary">Import Meals</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editFoodModal" tabindex="-1" aria-labelledby="editFoodModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFoodModalLabel">Edit Food</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="dynamicQtyMeasurementContainer"></div>
          
                <div class="nutrition-info mt-3">
                <p><strong>Protein:</strong> <span id="modalProtein">0g </span>, <strong>Carb:</strong> <span id="modalCarbs">0g </span>, <strong>Fat:</strong> <span id="modalFat">0g </span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary save-edit-food" id="save-edit-food">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Save Food Modal -->
<div class="modal" style="display:none;" id="saveMealModal" tabindex="-1" aria-labelledby="saveMealModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="saveMealModalLabel">Save Food</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You have unsaved changes. Do you want to save your changes before you leave?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="leaveWithoutSaving" data-bs-dismiss="modal">No, Continue</button>
                <button type="button" class="btn btn-primary" id="saveChanges">Yes, Save</button>
            </div>
        </div>
    </div>
</div>

<div id="loader" style="display: none;">
    <img src="https://media.tenor.com/On7kvXhzml4AAAAj/loading-gif.gif" alt="Loading..." />
</div>
@endsection
