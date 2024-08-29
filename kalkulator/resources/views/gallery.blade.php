<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gallery</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .card {
            text-align: center;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .card-body {
            padding: 20px;
        }
        input {
            margin: 5px;
        }
        .btn {
            margin: 5px;
        }
        #imageGallery img {
            width: 300px; /* Set your desired width */
            height: 300px; /* Set your desired height */
            object-fit: cover; /* This ensures the image is scaled and cropped to fill the container */
            margin: 10px;
            border-radius: 10px; /* Optional: for rounded corners */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card mx-auto" style="max-width: 400px;">
            <h2 class="mb-4" style="padding-top:10px">Generate Image</h2>
            <div class="card-body">
                <form id="imageForm">
                    @csrf
                    <input type="number" id="imageCount" class="form-control mb-2" placeholder="Jumlah gambar yang ingin di generate" required>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Generate</button>
                    </div>
                </form>
            </div>
        </div>
        <br>
{{-- 
        <form action="/upload-image" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="image" required>
            <button type="submit" class="btn btn-success">Upload Image</button>
        </form>
         --}}

        <div class="card mx-auto" style="max-width: 1000px;">
            <h2>Image Gallery</h2>
            <div id="imageGallery" class="card-body"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#imageForm').submit(function(event) {
                event.preventDefault();
                let count = $('#imageCount').val();
                
                $.ajax({
                    url: '/generate-images',
                    method: 'POST',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        count: count
                    },
                    success: function(response) {
                        let gallery = $('#imageGallery');
                        gallery.empty(); // Bersihkan galeri gambar sebelum menambahkan yang baru
                        response.images.forEach(function(image) {
                            gallery.append('<img src="' + image.url + '" class="img-thumbnail" alt="Generated Image">');
                        });
                    },
                    error: function(xhr) {
                        alert('An error occurred: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>
    
    
</body>
</html>
