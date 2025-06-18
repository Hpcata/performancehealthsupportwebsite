<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Generator</title>
    <style>
        body {
            display: flex;
            /* height: 100vh; */
            /* justify-content: left; */
            align-items: center;
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }
        .container {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            width: 100%;
            max-width: 500px;
            text-align: center;
        }
        input[type="text"], button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        img {
            margin-top: 15px;
            width: 100%;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>AI Image Generator</h2>
        <form id="imageForm">
            <input type="text" name="prompt" placeholder="Enter prompt..." required>
            <button type="submit">Generate Image</button>
        </form>
        <div id="loader" style="display: none; text-align: center; margin-top: 15px;">
            <img src="https://i.gifer.com/ZZ5H.gif" alt="Loading..." width="50">
            <p>Calculating nutrition...</p>
        </div>
        <img id="generatedImage" src="" alt="Generated Image" style="display:none;">
    </div>

    <script>
       document.getElementById('imageForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);
            const submitButton = e.target.querySelector('button');
            const loader = document.getElementById('loader');
            const img = document.getElementById('generatedImage');

            // Show loader and disable button
            loader.style.display = 'block';
            submitButton.disabled = true;
            submitButton.textContent = 'Generating...';

            try {
                const response = await fetch('{{ route("generate-image") }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: formData,
                });

                const data = await response.json();
                if (data.image_url) {
                    img.src = data.image_url;
                    img.style.display = 'block';
                } else {
                    alert('Error: Unable to generate image.');
                    img.style.display = 'none';
                }
            } catch (error) {
                alert('Error: Something went wrong while generating the image.');
                img.style.display = 'none';
            } finally {
                // Hide loader and enable button
                loader.style.display = 'none';
                submitButton.disabled = false;
                submitButton.textContent = 'Generate Image';
            }
        });

    </script>
</body>
</html>
