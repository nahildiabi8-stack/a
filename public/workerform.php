<style>
    .pixel-font {
        font-family: 'Press Start 2P', monospace;
    }
</style>


<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/tailwind/output.css" rel="stylesheet">
</head>

<body class="bg-[black] text-white pixel-font pixelated-ui select-none">
    <div>


        <div class="flex flex-col justify-center container mx-auto p-4">
            <h1 class=" text-center text-2xl font-bold mb-4">Create a new worker!</h1>
            <form action="../process/create_worker.php" method="POST" class=" flex flex-col gap-8 bg-gray-800 p-6 rounded-lg shadow-md">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium pb-4">Name of the worker</label>
                    <input type="text" id="name" name="name" required class="w-full px-3 py-2 bg-gray-700 text-blue-500 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="age" class="block text-sm font-medium pb-4">Age of the worker</label>
                    <input type="text" id="age" name="age" required class="w-full px-3 py-2 bg-gray-700 text-blue-500 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="gender" class="block text-sm font-medium pb-4">Gender of the worker</label>
                    <input type="text" id="gender" name="gender" required class="w-full px-3 py-2 bg-gray-700 text-blue-500 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="w-full cursor-pointer bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Create Worker
                </button>
            </form>
</body>

</html>