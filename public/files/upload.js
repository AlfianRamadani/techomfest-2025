let formImageUpload = document.querySelector(".form-image-upload");
let formImageInfo = document.querySelector(".form-image-information");
let send_button = document.getElementById("send_image");
// let is_image_received = false;

send_button.addEventListener("click", (e) => {
    if (is_image_received) {
        // after send
    }
});

formImageUpload.addEventListener("change", async (event) => {
    const [file] = event.target.files;
    if (!file) return;

    const formImagePreview = document.querySelector(".form-image-preview");
    formImagePreview.src = URL.createObjectURL(file);

    const {height, width} = await getImageDimensions(formImagePreview)

    const MAX_WIDTH = 250; 
    const MAX_HEIGHT = 250;

    const widthRatioBlob = await compressImage(formImagePreview, MAX_WIDTH / width, width, height); 
    const heightRatioBlob = await compressImage(formImagePreview, MAX_HEIGHT / height, width, height);

    const compressedBlob = widthRatioBlob.size > heightRatioBlob.size ? heightRatioBlob : widthRatioBlob;
    
    const optimalBlob = compressedBlob; 
    console.log(`Inital Size: ${file.size}. Compressed Size: ${optimalBlob.size}`);

    // ADDITION TO CHANGE FILE INPUT
        const compressedFile = new File([optimalBlob], file.name, {
            type: 'image/webp',
            lastModified: Date.now(),
        });

        console.log("Compressed size:", compressedFile.size);

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(compressedFile);

        formImageUpload.files = dataTransfer.files;    
        URL.revokeObjectURL(formImagePreview);

        formImagePreview.style.display = "block";
        formImageInfo.style.opacity = 0;
        is_image_received = true;
        send_button.classList.remove("!bg-gray-500", "!cursor-default");
});

function getImageDimensions(image) {
    return new Promise((resolve, reject) => {
        image.onload = function (e) {
            const width = this.width;
            const height = this.height;
            resolve({ height, width });
        };
    });
}

function compressImage(image, scale, initalWidth, initalHeight){
    return new Promise((resolve, reject) => {
        const canvas = document.createElement("canvas");

        canvas.width = scale * initalWidth;
        canvas.height = scale * initalHeight;

        const ctx = canvas.getContext("2d");
        ctx.drawImage(image, 0, 0, canvas.width, canvas.height);
        
        ctx.canvas.toBlob((blob) => {
            resolve(blob);
        }, "image/webp"); 
    }); 
}