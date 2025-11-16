<!doctype html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('files/main.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="flex flex-col gap-7 justify-center items-center h-screen font-[poppins]" id="main_body">
    <form method="POST" action="{{ route('upload.image') }}" enctype="multipart/form-data">
        @csrf
        <div class="w-fit flex flex-col gap-7 items-center">
            <h1 class="text-xl uppercase font-bold" id="head_up"> Upload/Tangkap Gambar Makanan Anda </h1>
            <div class="border-2 border-gray-200 border-dashed w-[530px] rounded-3xl transition-[width] duration-500 self-start"
                id="border_upload">
                <div class="form-child">
                    <div class="form-image-upload-container">
                        <img src='' class="form-image-preview" style="display: none;">
                        <section
                            class="flex flex-col form-image-information items-center content-center justify-center ">
                            <img src='images/upload-icon.png' alt="" width="70">
                            <section class="fcol">
                                <h5>Drop your image here, or browse</h2>
                                    <h6>Supports : JPEG, PNG</h6>
                            </section>
                        </section>
                        <input type="file" name="image" {{ isset($post->image) ? '' : 'required' }}
                            class="form-image-upload" accept="image/*" capture>
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center gap-2">
                <div class="flex gap-3 items-center border-2  px-3 py-2.5 rounded-2xl border-gray-200">

                    <input type="text" class=" min-w-96 outline-0" placeholder="Tambahkan deskripsi makanan Anda...">

                    <button type="submit"
                        class="bg-blue-300 text-white rounded-full p-2 cursor-pointer hover:bg-blue-200 !bg-gray-500 !cursor-default"
                        id="send_image">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                        </svg>
                    </button>
                </div>
                <span class="text-[0.65rem] font-bold ">*Deskripsi makanan bersifat opsional</span>
            </div>

        </div>
    </form>

    <script src="{{ asset('files/upload.js') }}"></script>
</body>

</html>
