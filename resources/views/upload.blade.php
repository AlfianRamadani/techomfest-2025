<!doctype html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('files/main.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="flex flex-col gap-7 justify-center items-center h-screen font-[poppins] pt-20" id="main_body">

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

            <div class="w-full">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Makanan(Opsional)</label>
                <select name="kategori_makanan"
                    class="border-2 border-gray-200 rounded-2xl px-4 py-2.5 w-full focus:outline-none focus:border-blue-300 focus:ring-2 focus:ring-blue-100 transition-all duration-200 bg-white cursor-pointer hover:border-gray-300 appearance-none bg-[url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e')] bg-[length:1.2em] bg-[right_0.7rem_center] bg-no-repeat pr-10">
                    <option value="" disabled selected>Pilih kategori makanan</option>
                    @foreach ($foodCategories as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col items-center gap-2">

                <div class="flex gap-3 items-center border-2  px-3 py-2.5 rounded-2xl border-gray-200">

                    <button type="submit"
                        class="bg-blue-300 text-white rounded-full p-2 cursor-pointer hover:bg-blue-200 !bg-gray-500 !cursor-default"
                        id="send_image"
                        onclick="this.disabled=true; this.innerHTML='<svg class=\'animate-spin size-6\' xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\'><circle class=\'opacity-25\' cx=\'12\' cy=\'12\' r=\'10\' stroke=\'currentColor\' stroke-width=\'4\'></circle><path class=\'opacity-75\' fill=\'currentColor\' d=\'M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z\'></path></svg>'; this.form.submit();">
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
