<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Nutrition Calculator</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 600px;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #4CAF50;
            text-align: center;
            margin-bottom: 20px;
        }
        textarea, button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            resize: none;
        }
        button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #45a049;
        }
        #nutritionResult {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background-color: #e6f7e6;
            display: none; /* Hidden by default until results show */
        }
        .error {
            color: red;
            background-color: #ffe6e6;
            border-color: #f5c6cb;
        }
        .result-text {
            font-weight: bold;
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>AI Nutrition Calculator</h2>
        
        <!-- Nutrition Form -->
        <form id="nutritionForm">
            <textarea 
                name="query" 
                placeholder="Enter your food details here..." 
                required
                rows="8"
            ></textarea>

            <button type="submit">Calculate Nutrition</button>
        </form>

        <!-- Results Area -->
        <div id="nutritionResult"></div>
        <div id="loader" style="display: none; text-align: center; margin-top: 15px;">
            <img src="https://i.gifer.com/ZZ5H.gif" alt="Loading..." width="50">
            <p>Calculating nutrition...</p>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#nutritionForm').on('submit', function (e) {
                e.preventDefault(); // Prevent default form submission

                const formData = new FormData(this);
                const resultDiv = $('#nutritionResult');
                const submitButton = $('button[type="submit"]');
                const loader = $('#loader');

                // Disable button and show loader
                submitButton.attr('disabled', true).text('Calculating...');
                loader.show();
                resultDiv.hide();

                $.ajax({
                    url: "{{ route('nutrition.calculate') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        if (data.result) {
                            resultDiv
                                .html(`<p class="result-text">${data.result}</p>`)
                                .removeClass('error')
                                .show();
                        } else {
                            resultDiv
                                .html(`<p class="error">Error: Could not calculate.</p>`)
                                .addClass('error')
                                .show();
                        }
                    },
                    error: function () {
                        resultDiv
                            .html(`<p class="error">Error: Unable to connect to the server.</p>`)
                            .addClass('error')
                            .show();
                    },
                    complete: function () {
                        // Re-enable button and hide loader
                        submitButton.attr('disabled', false).text('Calculate Nutrition');
                        loader.hide();
                    }
                });
            });
        });
    </script>
</body>
</html>
