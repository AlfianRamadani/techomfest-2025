let formImageUpload = document.querySelector(".form-image-upload");
let formImageInfo = document.querySelector(".form-image-information");
let send_button = document.getElementById("send_image");
let is_image_received = false

send_button.addEventListener("click", (e) => {
    if (is_image_received) {
    }
});

formImageUpload.addEventListener("change", function (event) {
    const [file] = event.target.files;
    console.log(file);

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const formImagePreview = document.querySelector(
                ".form-image-preview"
            );
            formImagePreview.src = e.target.result;
            formImagePreview.style.display = "block";
            formImageInfo.style.opacity = 0;
            is_image_received = true
            send_button.classList.remove('!bg-gray-500', '!cursor-default')
        };

        reader.readAsDataURL(file);
    }
});
