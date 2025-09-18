<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Image Resizer</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="form-container">
    <h3>🎨 Image Resizer</h3>
    <form id="resizeForm" enctype="multipart/form-data">
      <div class="file-input-wrapper">
        <label for="image" class="file-input-label">
          Choose Image File
        </label>
        <input type="file" name="image" id="image" accept="image/*" required>
         <p id="file-name" class="file_selected_para">No file selected</p> 
    </div>
      
      <div class="dimensions-container">
        <div class="input-group">
          <label for="width">📏 Width (px)</label>
          <input type="number" name="width" id="width" placeholder="Enter Width" required>
        </div>
        <div class="input-group">
          <label for="height">📐 Height (px)</label>
          <input type="number" name="height" id="height" placeholder="Enter Height" required>
        </div>
      </div>
      
      <button type="submit">
        <span class="icon">✨</span>Resize Image
      </button>
    </form>

    <div id="preview"></div>
  </div>
 <!-- ajax call for resize the image -->
  <script>
    document.getElementById('resizeForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const formData = new FormData(this);

      fetch('resize.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          document.getElementById('preview').innerHTML = `
            <h4>Resized Image:</h4>
            <img src="${data.file}" alt="Resized Image">
            <br><a href="${data.file}" download class="btn download_btn">Download</a>
          `;
        } else {
          alert(data.message);
        }
      })
      .catch(err => console.error(err));
    });
  </script>
  <!-- Script for display the selected file name -->
  <script>
		const inputField = document.getElementById('image');
        const fileNameDisplay = document.getElementById('file-name');

        inputField.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            fileNameDisplay.textContent = file.name;
        } else {
            fileNameDisplay.textContent = 'No file selected';
        }
        });

   </script>
</body>
</html>

