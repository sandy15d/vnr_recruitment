<!DOCTYPE html>
<html>
<head>
    <title>S3 File Upload</title>
  
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Upload File to S3</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('s3.upload') }}" method="POST" enctype="multipart/form-data" class="mb-4">
            @csrf
            <div class="mb-4">
                <input type="file" name="file" class="border rounded p-2">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Upload
            </button>
        </form>

        <h2 class="text-xl font-bold mb-2">Uploaded Files</h2>
        <div class="grid grid-cols-3 gap-4">
            @foreach ($files as $file)
                <div class="bg-white p-4 rounded shadow">
                    <a href="{{ $file }}" target="_blank" class="text-blue-500 hover:underline">
                        {{ basename($file) }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>