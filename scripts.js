function previewImage(event, previewId) {
  const input = event.target;
  const previewBox = document.getElementById(previewId);
  const removeButton = previewBox.nextElementSibling; // Select the remove button

  // Check if a file was selected
  if (input.files && input.files[0]) {
      const reader = new FileReader();

      reader.onload = function(e) {
          previewBox.style.backgroundImage = `url(${e.target.result})`; // Set image as background
          previewBox.style.color = 'transparent'; // Hide the "+" sign
          previewBox.classList.add('has-image'); // Add class for styling
          removeButton.style.display = 'inline-block'; // Show the remove button
          input.style.display = 'none'; // Hide the file input (Choose File button)
      };

      reader.readAsDataURL(input.files[0]); // Read the file to trigger the onload function
  }
}

function removeImage(previewId) {
  const previewBox = document.getElementById(previewId);
  const input = previewBox.previousElementSibling;
  const removeButton = previewBox.nextElementSibling;

  previewBox.style.backgroundImage = 'none'; // Remove the background image
  previewBox.style.color = '#555'; // Reset color to show "+"
  previewBox.classList.remove('has-image'); // Remove image class for styling
  removeButton.style.display = 'none'; // Hide the remove button
  input.style.display = 'inline-block'; // Show the file input (Choose File button)
  input.value = ''; // Clear the file input
}


  
  // Call the function to fetch and populate the make select
  fetchCarMakes();