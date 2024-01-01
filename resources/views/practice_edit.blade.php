<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Practice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            color: #333;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"],
        textarea,
        input[type="number"],
        button {
            padding: 8px;
            margin-bottom: 10px;
            width: 300px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        button {
            background-color: #3498db;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <h1>Edit Practice</h1>

    @if(session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('practices.update', $practice->id) }}">
        @csrf
        @method('PUT')

        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="{{ $practice->title }}"><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description">{{ $practice->description }}</textarea><br>

        <label for="difficulty">Difficulty:</label>
        <input type="text" id="difficulty" name="difficulty" value="{{ $practice->difficulty }}"><br>

        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" value="{{ $practice->subject }}"><br>

        <label for="total_score">Total Score:</label>
        <input type="number" id="total_score" name="total_score" value="{{ $practice->total_score }}"><br><br>

        <button type="submit">Update Practice</button>
    </form>
</body>
</html>
